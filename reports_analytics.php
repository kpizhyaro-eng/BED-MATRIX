<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - BedMatrix</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2338bdf8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M22 12h-4l-3 9L9 3l-3 9H2'/></svg>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        /* Report Controls */
        .report-controls {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            gap: 20px;
            align-items: flex-end;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            flex-wrap: wrap;
        }

        .control-group { display: flex; flex-direction: column; gap: 8px; }
        .control-label { font-size: 13px; font-weight: 600; color: #64748b; }
        .control-input { padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; min-width: 200px; }
        
        .btn-export {
            background: #0f172a;
            color: white;
            border: none;
            padding: 11px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .btn-export:hover { background: #1e293b; }

        /* Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }

        .chart-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .chart-title { font-size: 16px; font-weight: 700; color: #334155; margin-bottom: 20px; }

        /* Data Table */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px 24px; text-align: left; border-bottom: 1px solid #f1f5f9; }
        th { background-color: #f8fafc; color: #64748b; font-weight: 600; font-size: 13px; text-transform: uppercase; }
        td { color: #334155; font-size: 14px; }
        tr:last-child td { border-bottom: none; }

        /* Dark Mode Styles */
        body.dark-mode { background-color: #0f172a; color: #e2e8f0; }
        .dark-mode .chart-card, .dark-mode .table-card { background-color: #1e293b; border-color: #334155; color: #e2e8f0; }
        .dark-mode .header h1, .dark-mode .chart-title { color: #f8fafc !important; }
        .dark-mode th { background-color: #0f172a; color: #94a3b8; border-bottom-color: #334155; }
        .dark-mode td { color: #e2e8f0; border-bottom-color: #334155; }
        .dark-mode tr:hover td { background-color: #334155; }
        .dark-mode .table-card > div:first-child { color: #f8fafc !important; border-bottom-color: #334155 !important; }

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
            .sidebar, .btn-export, .report-controls, #notification-container { display: none !important; }
            .main-content { padding: 0; margin: 0; overflow: visible; }
            body { background-color: white; height: auto; display: block; }
            .chart-card, .table-card { box-shadow: none; border: 1px solid #e2e8f0; break-inside: avoid; margin-bottom: 20px; }
            .header h1 { color: black !important; }
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
            <li><a href="dashboard.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> <span class="link-text">Dashboard Overview</span></a></li>
            <li><a href="bed_management.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22h20"></path><path d="M2 10h20"></path><path d="M5 10v12"></path><path d="M19 10v12"></path><path d="M4 6h16"></path></svg> <span class="link-text">Bed Management</span></a></li>
            <li><a href="patient_management.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg> <span class="link-text">Patient Admissions</span></a></li>
            <li><a href="discharge_unit.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg> <span class="link-text">Discharge Unit</span></a></li>
            <li><a href="notifications.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg> <span class="link-text">Notifications</span></a></li>
            <li><a href="#" class="active"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg> <span class="link-text">Reports & Analytics</span></a></li>
            <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                <a href="#" id="logoutBtn" style="color: #ef4444;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span class="link-text">Logout</span></a>
            </li>
        </ul>
        <div class="user-profile" onclick="window.location.href='profile.php'">
            <div class="avatar" id="userAvatar">--</div>
            <div>
                <div style="font-size: 14px; font-weight: 600;" id="userName">Guest</div>
                <div style="font-size: 12px; color: #94a3b8;" id="userRole">Staff</div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="header">
            <h1>Reports & Analytics</h1>
        </div>

        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-title">Occupancy by Ward</div>
                <div style="height: 300px; position: relative;">
                    <canvas id="wardChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <div class="chart-title">Bed Status Distribution</div>
                <div style="height: 300px; position: relative;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div style="padding: 20px; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: #334155;">Detailed Bed Utilization</div>
            <table id="reportTable">
                <thead>
                    <tr>
                        <th>Ward Name</th>
                        <th>Total Beds</th>
                        <th>Occupied</th>
                        <th>Available</th>
                        <th>Occupancy Rate</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Populated via JS -->
                </tbody>
            </table>
        </div>
    </main>

    <script>
        <?php require_once 'session_check.php'; ?>

        // Check Dark Mode
        if (localStorage.getItem('nbs_dark_mode') === 'true') {
            document.body.classList.add('dark-mode');
        }

        // Session Management
        const currentUser = <?php echo json_encode($_SESSION['nbs_current_user']); ?>;
        document.getElementById('userName').textContent = currentUser.fullName;
        const roleMap = { 'DOC': 'Doctor', 'ADM': 'Administrator', 'NUR': 'Nurse', 'WMG': 'Ward Manager', 'REC': 'Records Officer' };
        document.getElementById('userRole').textContent = roleMap[currentUser.role] || currentUser.role;
        document.getElementById('userAvatar').textContent = currentUser.fullName.charAt(0);

        document.getElementById('logoutBtn').addEventListener('click', () => {
            fetch('api.php?action=logout')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    }
                });
        });

        // Load Data
        const allBeds = JSON.parse(localStorage.getItem('nbs_beds') || '[]');

        // Process Data for Charts
        const wardStats = {};
        const statusStats = { 'available': 0, 'occupied': 0, 'reserved': 0, 'untidy': 0, 'faulty': 0, 'isolation': 0, 'discharging': 0 };

        allBeds.forEach(bed => {
            // Ward Stats
            if (!wardStats[bed.ward]) wardStats[bed.ward] = { total: 0, occupied: 0 };
            wardStats[bed.ward].total++;
            if (['occupied', 'isolation', 'discharging'].includes(bed.status)) {
                wardStats[bed.ward].occupied++;
            }

            // Status Stats
            if (statusStats[bed.status] !== undefined) statusStats[bed.status]++;
        });

        // Render Charts
        const wardLabels = Object.keys(wardStats);
        const wardOccupancyData = wardLabels.map(w => wardStats[w].occupied);
        
        new Chart(document.getElementById('wardChart'), {
            type: 'bar',
            data: {
                labels: wardLabels,
                datasets: [{
                    label: 'Occupied Beds',
                    data: wardOccupancyData,
                    backgroundColor: '#3b82f6',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusStats).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                datasets: [{
                    data: Object.values(statusStats),
                    backgroundColor: ['#22c55e', '#ef4444', '#eab308', '#3b82f6', '#64748b', '#a855f7', '#f97316'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });

        // Render Table
        const tbody = document.getElementById('tableBody');
        wardLabels.forEach(ward => {
            const stats = wardStats[ward];
            const available = stats.total - stats.occupied;
            const rate = Math.round((stats.occupied / stats.total) * 100);
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${ward}</td>
                <td>${stats.total}</td>
                <td>${stats.occupied}</td>
                <td>${available}</td>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="flex: 1; height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden;">
                            <div style="width: ${rate}%; height: 100%; background: ${rate > 80 ? '#ef4444' : '#3b82f6'};"></div>
                        </div>
                        <span style="font-size: 12px; font-weight: 600;">${rate}%</span>
                    </div>
                </td>
                `;
                tbody.appendChild(row);
            });
        }

        loadReportData();
    </script>
</body>
</html>