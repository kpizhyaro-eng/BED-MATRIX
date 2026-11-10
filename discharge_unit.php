<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discharge Unit - BedMatrix</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2338bdf8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M22 12h-4l-3 9L9 3l-3 9H2'/></svg>">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            display: flex;
            height: 100vh;
            background-color: #f1f5f9;
        }

        html { scroll-behavior: smooth; }

        /* Sidebar Styles */
        .sidebar {
            width: 80px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            flex-shrink: 0;
            overflow: visible;
            z-index: 50;
        }

        .sidebar-header {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #38bdf8;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            overflow: visible;
            white-space: nowrap;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .nav-links li { 
            margin-bottom: 8px;
            position: relative;
        }

        .nav-links a {
            text-decoration: none;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 15px;
            font-weight: 500;
            white-space: nowrap;
        }

        .nav-links a:hover, .nav-links a.active {
            background-color: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
        }

        .user-profile div:last-child { display: none !important; }

        .logo-text {
            display: block;
            font-weight: 800;
            font-size: 20px;
            background: linear-gradient(45deg, #38bdf8, #818cf8, #c084fc);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: logoShimmer 3s ease infinite;
        }

        @keyframes logoShimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .link-text {
            display: none;
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background-color: #1e293b;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            white-space: nowrap;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-left: 15px;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
        }
        
        .link-text::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: #1e293b;
        }

        .nav-links a:hover .link-text { display: block; opacity: 1; }

        .user-profile {
            margin-top: auto;
            background: rgba(0, 0, 0, 0.2);
            padding: 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            white-space: nowrap;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background-color: #3b82f6;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            color: white;
        }

        .user-profile:hover { background-color: rgba(255, 255, 255, 0.1); }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 { margin: 0; color: #0f172a; font-size: 28px; }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px 24px; text-align: left; border-bottom: 1px solid #f1f5f9; }
        th { background-color: #f8fafc; color: #64748b; font-weight: 600; font-size: 13px; text-transform: uppercase; }
        td { color: #334155; font-size: 14px; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #f8fafc; }

        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
        .status-occupied { background-color: #fee2e2; color: #991b1b; }
        .status-discharging { background-color: #ffedd5; color: #c2410c; }
        .status-isolation { background-color: #f3e8ff; color: #7e22ce; }

        .btn-action {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-discharge { background-color: #f97316; color: white; }
        .btn-finalize { background-color: #22c55e; color: white; }
        .btn-action:hover { opacity: 0.9; }

        /* Search Toolbar */
        .controls-toolbar {
            display: flex;
            gap: 15px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }

        .search-box {
            position: relative;
            flex-grow: 1;
        }

        .search-box input {
            width: 100%;
            padding: 10px 10px 10px 38px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            box-sizing: border-box;
        }

        .search-box svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .filter-select {
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            color: #334155;
            outline: none;
            min-width: 180px;
            cursor: pointer;
            background-color: white;
        }

        .btn-bulk {
            padding: 10px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; color: white; display: flex; align-items: center; gap: 8px;
        }
        .btn-bulk-init { background-color: #f97316; }
        .btn-bulk-fin { background-color: #22c55e; }

        /* Custom Alert Modal */
        #alertModal .modal-card { max-width: 380px; text-align: center; padding: 30px; }
        #alertModal h2 { margin-bottom: 10px; color: #0f172a; }
        #alertModal p { color: #64748b; margin-bottom: 25px; line-height: 1.5; }
        .btn-alert-ok { width: 100%; padding: 12px; background-color: #3b82f6; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }

        /* Modal Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
            display: none; justify-content: center; align-items: center; z-index: 1000;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.open { display: flex; opacity: 1; }
        .modal-card {
            background: white; width: 100%; max-width: 400px; border-radius: 12px; padding: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); transform: translateY(20px); transition: transform 0.3s ease;
        }
        .modal-overlay.open .modal-card { transform: translateY(0); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .modal-header h2 { margin: 0; color: #0f172a; font-size: 18px; }
        .close-btn { background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer; }

        /* Dark Mode Styles */
        body.dark-mode { background-color: #0f172a; color: #e2e8f0; }
        .dark-mode .table-container { background-color: #1e293b; border-color: #334155; color: #e2e8f0; }
        .dark-mode .header h1 { color: #f8fafc !important; }
        .dark-mode .controls-toolbar { background-color: #1e293b; border-color: #334155; }
        .dark-mode .search-box input { background-color: #334155; border-color: #475569; color: white; }
        .dark-mode th { background-color: #0f172a; color: #94a3b8; border-bottom-color: #334155; }
        .dark-mode .filter-select { background-color: #334155; border-color: #475569; color: white; }
        .dark-mode td { color: #e2e8f0; border-bottom-color: #334155; }
        .dark-mode tr:hover td { background-color: #334155; }
        .dark-mode .modal-card { background-color: #1e293b; border-color: #334155; color: #e2e8f0; }
        .dark-mode .modal-header h2 { color: #f8fafc !important; }

        /* Global Polish */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        ::selection { background: #38bdf8; color: white; }
        
        body.dark-mode ::-webkit-scrollbar-thumb { background: #475569; }
        body.dark-mode ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        .main-content { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        @media print {
            .sidebar, .controls-toolbar, .btn-action, input[type="checkbox"], #notification-container { display: none !important; }
            .main-content { padding: 0; margin: 0; overflow: visible; }
            body { background-color: white; height: auto; display: block; }
            .table-container { box-shadow: none; border: 1px solid #e2e8f0; }
        }
    </style>
</head>
<body>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="logo-text">BedMatrix</span>
            </div>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.html"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> <span class="link-text">Dashboard Overview</span></a></li>
            <li><a href="bed_management.html"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22h20"></path><path d="M2 10h20"></path><path d="M5 10v12"></path><path d="M19 10v12"></path><path d="M4 6h16"></path></svg> <span class="link-text">Bed Management</span></a></li>
            <li><a href="patient_management.html"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg> <span class="link-text">Patient Admissions</span></a></li>
            <li><a href="#" class="active"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg> <span class="link-text">Discharge Unit</span></a></li>
            <li><a href="notifications.html"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg> <span class="link-text">Notifications</span></a></li>
            <li><a href="reports_analytics.html"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg> <span class="link-text">Reports & Analytics</span></a></li>
            <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                <a href="#" id="logoutBtn" style="color: #ef4444;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span class="link-text">Logout</span></a>
            </li>
        </ul>
        <div class="user-profile" onclick="window.location.href='profile.html'">
            <div class="avatar" id="userAvatar">--</div>
            <div>
                <div style="font-size: 14px; font-weight: 600;" id="userName">Guest</div>
                <div style="font-size: 12px; color: #94a3b8;" id="userRole">Staff</div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="header">
            <h1>Discharge Unit</h1>
        </div>

        <div class="controls-toolbar">
            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="searchInput" placeholder="Search by patient name, ID, or bed...">
            </div>
            <select id="statusFilter" class="filter-select">
                <option value="all">All Patients</option>
                <option value="discharging">Discharging Only</option>
                <option value="occupied">Occupied</option>
                <option value="isolation">Isolation</option>
            </select>
            <select id="sortFilter" class="filter-select">
                <option value="date_desc">Date: Newest First</option>
                <option value="date_asc">Date: Oldest First</option>
                <option value="name_asc">Name: A-Z</option>
                <option value="name_desc">Name: Z-A</option>
            </select>
            <div style="margin-left: auto; display: flex; gap: 10px;">
                <button class="btn-bulk btn-bulk-init" onclick="bulkAction('initiate')">Bulk Initiate</button>
                <button class="btn-bulk btn-bulk-fin" onclick="bulkAction('finalize')">Bulk Finalize</button>
            </div>
        </div>

        <div class="table-container">
            <table id="dischargeTable">
                <thead>
                    <tr>
                        <th style="width: 40px;"><input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"></th>
                        <th>Patient Name</th>
                        <th>Bed ID</th>
                        <th>Admission Date</th>
                        <th>Doctor</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Populated via JS -->
                </tbody>
            </table>
        </div>
    </main>

    <!-- Confirmation Modal -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-card">
            <div class="modal-header">
                <h2 id="confirmTitle">Confirm Action</h2>
                <button class="close-btn" onclick="closeConfirmModal()">×</button>
            </div>
            <p id="confirmMessage" style="margin-bottom: 24px; color: #64748b; line-height: 1.5;">Are you sure?</p>
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button class="btn-action" style="background-color: #94a3b8; color: white;" onclick="closeConfirmModal()">Cancel</button>
                <button class="btn-action btn-finalize" id="confirmBtn">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Custom Alert Modal -->
    <div class="modal-overlay" id="alertModal">
        <div class="modal-card">
            <h2 id="alertTitle">Alert</h2>
            <p id="alertMessage">Something happened.</p>
            <button class="btn-alert-ok" onclick="closeAlertModal()">OK</button>
        </div>
    </div>

    <script>
        <?php require_once 'session_check.php'; ?>

        // Check Dark Mode
        if (localStorage.getItem('nbs_dark_mode') === 'true') {
            document.body.classList.add('dark-mode');
        }

        // Session Management
        const currentUser = JSON.parse(localStorage.getItem('nbs_current_user'));
        const sessionUser = <?php echo json_encode($_SESSION['nbs_current_user']); ?>;
        
        document.getElementById('userName').textContent = sessionUser.fullName;
        const roleMap = { 'DOC': 'Doctor', 'ADM': 'Administrator', 'NUR': 'Nurse', 'WMG': 'Ward Manager', 'REC': 'Records Officer' };
        document.getElementById('userRole').textContent = roleMap[sessionUser.role] || sessionUser.role;
        document.getElementById('userAvatar').textContent = sessionUser.fullName.charAt(0);

        document.getElementById('logoutBtn').addEventListener('click', () => {
            fetch('api.php?action=logout').then(() => window.location.href = 'login.php');
        });

        // Load Data
        let allBeds = [];

        function renderTable() {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = '';
            
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const sortFilter = document.getElementById('sortFilter').value;

            // Filter for patients who can be discharged (Occupied, Isolation, Discharging)
            let dischargeablePatients = allBeds.filter(b => ['occupied', 'isolation', 'discharging'].includes(b.status));

            if (statusFilter !== 'all') {
                dischargeablePatients = dischargeablePatients.filter(b => b.status === statusFilter);
            }

            if (searchTerm) {
                dischargeablePatients = dischargeablePatients.filter(bed => 
                    bed.patient.toLowerCase().includes(searchTerm) ||
                    (bed.patientId && bed.patientId.toLowerCase().includes(searchTerm)) ||
                    bed.id.toLowerCase().includes(searchTerm)
                );
            }

            // Sorting Logic
            dischargeablePatients.sort((a, b) => {
                if (sortFilter === 'date_desc') {
                    const dateA = (a.admissionDate === '-' || !a.admissionDate) ? 0 : new Date(a.admissionDate).getTime();
                    const dateB = (b.admissionDate === '-' || !b.admissionDate) ? 0 : new Date(b.admissionDate).getTime();
                    return dateB - dateA;
                } else if (sortFilter === 'date_asc') {
                    const dateA = (a.admissionDate === '-' || !a.admissionDate) ? 0 : new Date(a.admissionDate).getTime();
                    const dateB = (b.admissionDate === '-' || !b.admissionDate) ? 0 : new Date(b.admissionDate).getTime();
                    return dateA - dateB;
                } else if (sortFilter === 'name_asc') {
                    return a.patient.localeCompare(b.patient);
                } else if (sortFilter === 'name_desc') {
                    return b.patient.localeCompare(a.patient);
                }
                return 0;
            });

            if (dischargeablePatients.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding: 40px; color: #94a3b8;">No patients found.</td></tr>`;
                return;
            }

            dischargeablePatients.forEach(bed => {
                const row = document.createElement('tr');
                let statusClass = 'status-occupied';
                if (bed.status === 'discharging') statusClass = 'status-discharging';
                if (bed.status === 'isolation') statusClass = 'status-isolation';

                let actionBtn = '';
                if (bed.status === 'discharging') {
                    actionBtn = `<button class="btn-action btn-finalize" onclick="finalizeDischarge('${bed.id}')">Finalize & Clear</button>`;
                } else {
                    actionBtn = `<button class="btn-action btn-discharge" onclick="initiateDischarge('${bed.id}')">Initiate Discharge</button>`;
                }

                row.innerHTML = `
                    <td><input type="checkbox" class="row-checkbox" value="${bed.id}"></td>
                    <td>
                        <div style="font-weight: 600;">${bed.patient}</div>
                        <div style="font-size: 12px; color: #94a3b8;">${bed.patientId || 'ID: --'}</div>
                    </td>
                    <td>${bed.id} <span style="font-size:12px; color:#64748b">(${bed.ward})</span></td>
                    <td>${bed.admissionDate}</td>
                    <td>${bed.doctor}</td>
                    <td><span class="status-badge ${statusClass}">${bed.status.toUpperCase()}</span></td>
                    <td>${actionBtn}</td>
                `;
                tbody.appendChild(row);
            });
        }

        // Modal Logic
        let pendingAction = null;
        let pendingBedId = null;
        let pendingBulkIds = null;

        function openConfirmModal(action, bedId, isBulk = false, count = 0) {
            pendingAction = action;
            const modal = document.getElementById('confirmModal');
            const title = document.getElementById('confirmTitle');
            const msg = document.getElementById('confirmMessage');
            const btn = document.getElementById('confirmBtn');

            if (isBulk) {
                pendingBulkIds = bedId; // bedId is array here
                pendingBedId = null;
                if (action === 'initiate') {
                    title.textContent = 'Bulk Initiate Discharge';
                    msg.textContent = `Initiate discharge for ${count} patients?`;
                    btn.className = 'btn-action btn-discharge';
                    btn.textContent = 'Initiate All';
                } else {
                    title.textContent = 'Bulk Finalize Discharge';
                    msg.textContent = `Finalize discharge for ${count} patients? This will clear their data.`;
                    btn.className = 'btn-action btn-finalize';
                    btn.textContent = 'Finalize All';
                }
            } else {
                pendingBulkIds = null;
                pendingBedId = bedId;
                if (action === 'initiate') {
                    title.textContent = 'Initiate Discharge';
                    msg.textContent = `Are you sure you want to initiate discharge for bed ${bedId}? This will change status to 'Discharging'.`;
                    btn.className = 'btn-action btn-discharge';
                    btn.textContent = 'Initiate';
                } else {
                    title.textContent = 'Finalize Discharge';
                    msg.textContent = `Confirm patient has left bed ${bedId}? This will mark the bed as 'Untidy' and clear patient data.`;
                    btn.className = 'btn-action btn-finalize';
                    btn.textContent = 'Finalize';
                }
            }
            
            modal.classList.add('open');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.remove('open');
            pendingAction = null;
            pendingBedId = null;
            pendingBulkIds = null;
        }

        document.getElementById('confirmBtn').addEventListener('click', () => {
            if (pendingAction) {
                if (pendingAction === 'initiate') {
                    if (pendingBulkIds) performBulkInitiate(pendingBulkIds);
                    else if (pendingBedId) performInitiateDischarge(pendingBedId);
                } else {
                    if (pendingBulkIds) performBulkFinalize(pendingBulkIds);
                    else if (pendingBedId) performFinalizeDischarge(pendingBedId);
                }
            }
            closeConfirmModal();
        });

        // Custom Alert Logic
        function showAlert(message, title = "Notice") {
            document.getElementById('alertTitle').textContent = title;
            document.getElementById('alertMessage').textContent = message;
            document.getElementById('alertModal').classList.add('open');
        }

        function closeAlertModal() {
            document.getElementById('alertModal').classList.remove('open');
        }

        function initiateDischarge(bedId) {
            openConfirmModal('initiate', bedId);
        }

        function performInitiateDischarge(bedId) {
            const bed = allBeds.find(b => b.id === bedId);
            if (!bed) return;
            updateBedStatus(bedId, 'discharging', bed.patient);
        }

        function finalizeDischarge(bedId) {
            openConfirmModal('finalize', bedId);
        }

        function performFinalizeDischarge(bedId) {
            updateBedStatus(bedId, 'untidy', 'Needs Cleaning');
        }

        function updateBedStatus(bedId, newStatus, newPatientVal) {
            const formData = new FormData();
            formData.append('action', 'update_bed_status');
            formData.append('bedId', bedId);
            formData.append('newStatus', newStatus);
            formData.append('newPatientVal', newPatientVal);

            fetch('api.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        allBeds = data.beds;
                        renderTable();
                    } else {
                        showAlert(data.message || 'Failed to update status.', 'Error');
                    }
                });
        }

        // Bulk Actions
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
        }

        function bulkAction(type) {
            const checkboxes = document.querySelectorAll('.row-checkbox:checked');
            const selectedIds = Array.from(checkboxes).map(cb => cb.value);

            if (selectedIds.length === 0) {
                showAlert('Please select at least one patient to perform this action.', 'Selection Required');
                return;
            }

            if (type === 'initiate') {
                const validBeds = allBeds.filter(b => selectedIds.includes(b.id) && ['occupied', 'isolation'].includes(b.status));
                if (validBeds.length === 0) {
                    showAlert('None of the selected patients are eligible for discharge initiation. They must be Occupied or in Isolation.', 'Invalid Selection');
                    return;
                }
                openConfirmModal('initiate', validBeds.map(b => b.id), true, validBeds.length);
            } else if (type === 'finalize') {
                const validBeds = allBeds.filter(b => selectedIds.includes(b.id) && b.status === 'discharging');
                if (validBeds.length === 0) {
                    showAlert('None of the selected patients are eligible for finalization. They must be in Discharging status.', 'Invalid Selection');
                    return;
                }
                openConfirmModal('finalize', validBeds.map(b => b.id), true, validBeds.length);
            }
        }

        function performBulkInitiate(ids) {
            performBulkUpdate(ids, 'discharging');
        }

        function performBulkFinalize(ids) {
            performBulkUpdate(ids, 'untidy');
        }

        function performBulkUpdate(bedIds, newStatus) {
            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'bulk_update_status', bedIds, newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    allBeds = data.beds;
                    renderTable();
                    document.getElementById('selectAll').checked = false;
                } else {
                    showAlert(data.message || 'Bulk update failed.', 'Error');
                }
            });
        }
        
        // Search Listener
        document.getElementById('searchInput').addEventListener('input', renderTable);
        document.getElementById('statusFilter').addEventListener('change', renderTable);
        document.getElementById('sortFilter').addEventListener('change', renderTable);

        // Listen for storage changes
        function initApp() {
            fetch('api.php?action=get_beds')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        allBeds = data.beds;
                        renderTable();
                    }
                });
        }
        initApp();
    </script>
</body>
</html>