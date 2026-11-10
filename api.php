<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid API action.'];

try {
    $conn = get_db_connection();
    $action = $_POST['action'] ?? $_GET['action'] ?? null;
    $input = json_decode(file_get_contents('php://input'), true);

    switch ($action) {
        case 'login':
            $staffId = $_POST['staffId'] ?? '';
            $password = $_POST['password'] ?? '';

            $stmt = $conn->prepare("SELECT * FROM users WHERE staffId = ?");
            $stmt->execute([$staffId]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                unset($user['password']); // Never store the password hash in the session
                $_SESSION['nbs_current_user'] = $user;
                $response = ['success' => true, 'user' => $user];
            } else {
                $response['message'] = 'Invalid Staff ID or Password.';
            }
            break;

        case 'register':
            $data = $input;
            // Check if user already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE staffId = ? OR email = ?");
            $stmt->execute([$data['staffId'], $data['email']]);
            if ($stmt->fetch()) {
                $response['message'] = 'A user with this Staff ID or Email already exists.';
                break;
            }

            // Hash the password for secure storage
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (staffId, fullName, email, password, role, contact, nationality) VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $data['staffId'],
                $data['fullName'],
                $data['email'],
                $hashedPassword,
                $data['role'],
                $data['contact'],
                $data['nationality']
            ]);

            $response = ['success' => true, 'message' => 'Registration successful!'];
            break;

        case 'logout':
            session_destroy();
            $response = ['success' => true];
            break;

        case 'get_beds':
            $stmt = $conn->query("SELECT * FROM beds ORDER BY ward, id");
            $beds = $stmt->fetchAll();
            $response = ['success' => true, 'beds' => $beds];
            break;

        case 'update_bed_status':
            $bedId = $_POST['bedId'] ?? null;
            $newStatus = $_POST['newStatus'] ?? null;
            $newPatientVal = $_POST['newPatientVal'] ?? 'Empty';

            $conn->beginTransaction();
            
            // Get current bed state
            $stmt = $conn->prepare("SELECT * FROM beds WHERE id = ? FOR UPDATE");
            $stmt->execute([$bedId]);
            $bed = $stmt->fetch();

            if (!$bed) {
                throw new Exception('Bed not found.');
            }

            $hadPatient = in_array($bed['status'], ['occupied', 'discharging', 'isolation']);
            $willHavePatient = in_array($newStatus, ['occupied', 'discharging', 'isolation']);

            // If a patient is being cleared from a bed, save their record to history
            if ($hadPatient && !$willHavePatient) {
                $historyStmt = $conn->prepare(
                    "INSERT INTO bed_history (bedId, patientName, admissionDate, dischargeDate, diagnosis) VALUES (?, ?, ?, ?, ?)"
                );
                $historyStmt->execute([
                    $bed['id'],
                    $bed['patient'],
                    $bed['admissionDate'],
                    date('Y-m-d'),
                    $bed['diagnosis'] ?? 'Discharged'
                ]);
            }

            // Update the bed
            $updateStmt = $conn->prepare("UPDATE beds SET status = ?, patient = ? WHERE id = ?");
            $updateStmt->execute([$newStatus, $newPatientVal, $bedId]);

            $conn->commit();

            // Return all beds after update
            $stmt = $conn->query("SELECT * FROM beds ORDER BY ward, id");
            $response = ['success' => true, 'beds' => $stmt->fetchAll()];
            break;

        case 'add_bed':
            $newBed = $input;
            
            $stmt = $conn->prepare("SELECT id FROM beds WHERE id = ?");
            $stmt->execute([$newBed['id']]);
            if ($stmt->fetch()) {
                $response['message'] = 'Bed ID already exists!';
                break;
            }

            $stmt = $conn->prepare("INSERT INTO beds (id, ward, status, patient, type, room) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$newBed['id'], $newBed['ward'], 'available', 'Empty', $newBed['type'], $newBed['room']]);

            $stmt = $conn->query("SELECT * FROM beds ORDER BY ward, id");
            $response = ['success' => true, 'beds' => $stmt->fetchAll()];
            break;

        case 'get_history':
            $stmt = $conn->query("SELECT * FROM bed_history ORDER BY dischargeDate DESC");
            $history = $stmt->fetchAll();
            $response = ['success' => true, 'history' => $history];
            break;

        case 'transfer_patient':
            $data = $input;
            $sourceId = $data['sourceId'];
            $targetId = $data['targetId'];

            $conn->beginTransaction();

            // Lock both beds for the transaction
            $stmt = $conn->prepare("SELECT * FROM beds WHERE id IN (?, ?) FOR UPDATE");
            $stmt->execute([$sourceId, $targetId]);
            $beds = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            if (!isset($beds[$sourceId]) || !isset($beds[$targetId])) {
                throw new Exception('Source or target bed not found.');
            }

            $sourceBed = $beds[$sourceId];
            $targetBed = $beds[$targetId];

            if ($sourceBed['status'] !== 'occupied' || $targetBed['status'] !== 'available') {
                throw new Exception('Invalid transfer: Can only move from Occupied to Available.');
            }

            // 1. Save history for the source bed
            $historyStmt = $conn->prepare("INSERT INTO bed_history (bedId, patientName, admissionDate, dischargeDate, diagnosis) VALUES (?, ?, ?, ?, 'Transferred')");
            $historyStmt->execute([$sourceBed['id'], $sourceBed['patient'], $sourceBed['admissionDate'], date('Y-m-d')]);

            // 2. Update target bed with patient data
            $targetStmt = $conn->prepare(
                "UPDATE beds SET patient = ?, admissionDate = ?, gender = ?, age = ?, status = 'occupied', diagnosis = ?, patientId = ?, phone = ?, nationality = ? WHERE id = ?"
            );
            $targetStmt->execute([
                $sourceBed['patient'], $sourceBed['admissionDate'], $sourceBed['gender'], $sourceBed['age'],
                $sourceBed['diagnosis'], $sourceBed['patientId'], $sourceBed['phone'], $sourceBed['nationality'],
                $targetId
            ]);

            // 3. Reset source bed
            $sourceStmt = $conn->prepare(
                "UPDATE beds SET patient = 'Needs Cleaning', status = 'untidy', admissionDate = NULL, gender = NULL, age = NULL, diagnosis = NULL, patientId = NULL, phone = NULL, nationality = NULL WHERE id = ?"
            );
            $sourceStmt->execute([$sourceId]);

            $conn->commit();

            $stmt = $conn->query("SELECT * FROM beds ORDER BY ward, id");
            $response = ['success' => true, 'beds' => $stmt->fetchAll()];
            break;

        case 'update_profile':
            if (!isset($_SESSION['nbs_current_user'])) {
                throw new Exception("Authentication required.");
            }
            $data = $input;
            $userId = $_SESSION['nbs_current_user']['id'];

            // Password change logic
            if (!empty($data['currentPass']) || !empty($data['newPass'])) {
                if (empty($data['currentPass']) || empty($data['newPass'])) {
                    throw new Exception("To change password, you must provide current and new passwords.");
                }

                $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch();

                if (!$user || !password_verify($data['currentPass'], $user['password'])) {
                    throw new Exception("Current password is incorrect.");
                }

                $newHashedPassword = password_hash($data['newPass'], PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$newHashedPassword, $userId]);
            }

            // Update other details
            $stmt = $conn->prepare(
                "UPDATE users SET email = ?, contact = ?, nationality = ?, profilePic = ? WHERE id = ?"
            );
            $stmt->execute([
                $data['email'],
                $data['contact'],
                $data['nationality'],
                $data['profilePic'] ?? $_SESSION['nbs_current_user']['profilePic'], // Keep old pic if new one isn't sent
                $userId
            ]);

            // Refresh session data
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $updatedUser = $stmt->fetch();
            unset($updatedUser['password']);
            $_SESSION['nbs_current_user'] = $updatedUser;

            $response = ['success' => true, 'message' => 'Profile updated successfully!', 'user' => $updatedUser];
            break;

        case 'admit_patient':
            $data = $input;
            $ward = $data['ward'];

            $conn->beginTransaction();

            // Find and lock an available bed in the specified ward
            $stmt = $conn->prepare("SELECT id FROM beds WHERE ward = ? AND status = 'available' LIMIT 1 FOR UPDATE");
            $stmt->execute([$ward]);
            $availableBed = $stmt->fetch();

            if (!$availableBed) {
                throw new Exception("No available beds found in {$ward}.");
            }

            $bedId = $availableBed['id'];

            // Update the bed with the new patient's information
            $stmt = $conn->prepare(
                "UPDATE beds SET 
                    status = ?, patient = ?, age = ?, gender = ?, diagnosis = ?, 
                    conditionStatus = ?, admissionDate = ?, patientId = ?, phone = ?, 
                    nationality = ?, dob = ?, allergies = ?, medications = ?, 
                    emergencyContactName = ?, emergencyContactPhone = ?
                WHERE id = ?"
            );

            $patientId = 'P-' . substr(time(), -6);
            $admissionDate = date('Y-m-d');

            $stmt->execute([
                $data['status'] ?? 'occupied',
                $data['patient'],
                $data['age'],
                $data['gender'],
                $data['diagnosis'],
                $data['conditionStatus'] ?? 'Stable',
                $admissionDate,
                $patientId,
                $data['phone'] ?? null,
                $data['nationality'] ?? null,
                empty($data['dob']) ? null : $data['dob'],
                $data['allergies'] ?? null,
                $data['medications'] ?? null,
                $data['emergencyContactName'] ?? null,
                $data['emergencyContactPhone'] ?? null,
                $bedId
            ]);

            $conn->commit();

            $stmt = $conn->query("SELECT * FROM beds ORDER BY ward, id");
            $response = ['success' => true, 'beds' => $stmt->fetchAll(), 'bedId' => $bedId];
            break;

        case 'edit_patient':
            $data = $input;
            $bedId = $data['bedId'];

            $stmt = $conn->prepare(
                "UPDATE beds SET 
                    patient = ?, age = ?, gender = ?, diagnosis = ?, 
                    conditionStatus = ?, phone = ?, nationality = ?
                WHERE id = ?"
            );
            $stmt->execute([
                $data['patient'], $data['age'], $data['gender'], $data['diagnosis'],
                $data['conditionStatus'], $data['phone'], $data['nationality'], $bedId
            ]);

            $stmt = $conn->query("SELECT * FROM beds ORDER BY ward, id");
            $response = ['success' => true, 'beds' => $stmt->fetchAll()];
            break;

        case 'get_dashboard_stats':
            $stats = [];

            // 1. Bed status counts
            $stmt = $conn->query("SELECT status, COUNT(*) as count FROM beds GROUP BY status");
            $statusCounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            $stats['totalBeds'] = array_sum($statusCounts);
            $stats['available'] = $statusCounts['available'] ?? 0;
            $stats['occupied'] = ($statusCounts['occupied'] ?? 0) + ($statusCounts['isolation'] ?? 0) + ($statusCounts['discharging'] ?? 0);
            $stats['untidy'] = $statusCounts['untidy'] ?? 0;

            // 2. Admitted today
            $stmt = $conn->prepare("SELECT COUNT(*) FROM beds WHERE admissionDate = CURDATE()");
            $stmt->execute();
            $stats['admittedToday'] = $stmt->fetchColumn();

            // 3. Gender distribution
            $stmt = $conn->query("SELECT gender, COUNT(*) as count FROM beds WHERE status IN ('occupied', 'isolation', 'discharging') AND gender IS NOT NULL GROUP BY gender");
            $stats['gender'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            // 4. Ward capacity
            $stmt = $conn->query("SELECT ward, COUNT(*) as total, SUM(CASE WHEN status IN ('occupied', 'isolation', 'discharging') THEN 1 ELSE 0 END) as occupied FROM beds GROUP BY ward ORDER BY ward");
            $stats['wards'] = $stmt->fetchAll();

            // 5. Recent activity (from history)
            $stmt = $conn->query("SELECT * FROM bed_history ORDER BY dischargeDate DESC, history_id DESC LIMIT 5");
            $stats['activity'] = $stmt->fetchAll();

            // 6. Occupancy trend (last 7 days)
            $occupancyTrend = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $stmt = $conn->prepare("SELECT COUNT(*) FROM bed_history WHERE admissionDate <= ? AND dischargeDate >= ?");
                $stmt->execute([$date, $date]);
                $occupancyTrend[$date] = $stmt->fetchColumn();
            }
            $stats['occupancyTrend'] = $occupancyTrend;

            $response = ['success' => true, 'stats' => $stats];
            break;

        case 'bulk_update_status':
            $data = $input;
            $bedIds = $data['bedIds'] ?? [];
            $newStatus = $data['newStatus'] ?? null;

            if (empty($bedIds) || !$newStatus) {
                throw new Exception("Bed IDs and new status are required.");
            }

            $conn->beginTransaction();

            foreach ($bedIds as $bedId) {
                $stmt = $conn->prepare("SELECT * FROM beds WHERE id = ? FOR UPDATE");
                $stmt->execute([$bedId]);
                $bed = $stmt->fetch();

                if (!$bed) continue; // Skip if bed not found

                // Logic for initiating discharge
                if ($newStatus === 'discharging' && in_array($bed['status'], ['occupied', 'isolation'])) {
                    $updateStmt = $conn->prepare("UPDATE beds SET status = 'discharging' WHERE id = ?");
                    $updateStmt->execute([$bedId]);
                } 
                // Logic for finalizing discharge
                else if ($newStatus === 'untidy' && $bed['status'] === 'discharging') {
                    // 1. Save to history
                    $historyStmt = $conn->prepare(
                        "INSERT INTO bed_history (bedId, patientName, admissionDate, dischargeDate, diagnosis) VALUES (?, ?, ?, ?, ?)"
                    );
                    $historyStmt->execute([
                        $bed['id'], $bed['patient'], $bed['admissionDate'],
                        date('Y-m-d'), $bed['diagnosis'] ?? 'Discharged'
                    ]);

                    // 2. Reset the bed to be untidy and clear all patient data
                    $resetStmt = $conn->prepare(
                        "UPDATE beds SET status = 'untidy', patient = 'Needs Cleaning', admissionDate = NULL, gender = NULL, age = NULL, diagnosis = NULL, patientId = NULL, phone = NULL, nationality = NULL, dob = NULL, allergies = NULL, medications = NULL, emergencyContactName = NULL, emergencyContactPhone = NULL, doctor = NULL WHERE id = ?"
                    );
                    $resetStmt->execute([$bedId]);
                }
            }

            $conn->commit();

            $stmt = $conn->query("SELECT * FROM beds ORDER BY ward, id");
            $response = ['success' => true, 'beds' => $stmt->fetchAll()];
            break;

        case 'get_report_data':
            $reportData = [];

            // 1. Ward stats for table and bar chart
            $stmt = $conn->query("SELECT ward, COUNT(*) as total, SUM(CASE WHEN status IN ('occupied', 'isolation', 'discharging') THEN 1 ELSE 0 END) as occupied FROM beds GROUP BY ward ORDER BY ward");
            $reportData['wardStats'] = $stmt->fetchAll();

            // 2. Status stats for doughnut chart
            $stmt = $conn->query("SELECT status, COUNT(*) as count FROM beds GROUP BY status");
            $reportData['statusStats'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            $response = ['success' => true, 'data' => $reportData];
            break;


        default:
            $response['message'] = "Action '{$action}' not recognized.";
            break;
    }
} catch (PDOException $e) {
    // Database-specific error
    error_log("Database Error: " . $e->getMessage()); // Log error for admin
    $response['message'] = 'A database error occurred. Please try again later.';
    http_response_code(500);
} catch (Exception $e) {
    // General application error
    $response['message'] = $e->getMessage();
    http_response_code(400); // Bad Request for logical errors
}

echo json_encode($response);
?>