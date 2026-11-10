<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard - BedMatrix</title>
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
            background-color: #f1f5f9; /* Light grey background for content area */
        }

        html { scroll-behavior: smooth; }

        /* Sidebar Navigation Styles */
        .sidebar {
            width: 80px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            flex-shrink: 0;
            /* transition: width 0.3s ease; Removed */
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

        /* Text Visibility Control */
        .user-profile div:last-child {
            display: none !important;
        }

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

        .nav-links a:hover .link-text {
            display: block;
            opacity: 1;
        }

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

        /* Main Content Area Styles */
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

        .header h1 {
            margin: 0;
            color: #0f172a;
            font-size: 28px;
        }

        /* Dashboard Widgets Placeholder */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0; /* Start hidden for animation */
            animation: slideUpFade 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Staggered Animation Delays */
        .dashboard-grid .card:nth-child(1) { animation-delay: 0.1s; }
        .dashboard-grid .card:nth-child(2) { animation-delay: 0.2s; }
        .dashboard-grid .card:nth-child(3) { animation-delay: 0.3s; }
        .dashboard-grid .card:nth-child(4) { animation-delay: 0.4s; }
        .dashboard-grid .card:nth-child(5) { animation-delay: 0.5s; }
        
        .dashboard-row .card:nth-child(1) { animation-delay: 0.5s; }
        .dashboard-row .card:nth-child(2) { animation-delay: 0.6s; }
        
        .charts-row .card:nth-child(1) { animation-delay: 0.7s; }
        .charts-row .card:nth-child(2) { animation-delay: 0.8s; }

        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* New Dashboard Sections */
        .dashboard-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 15px;
        }

        .ward-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .ward-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .ward-info { flex: 1; }
        .ward-name { font-weight: 600; color: #0f172a; font-size: 14px; }
        .ward-stats { font-size: 12px; color: #64748b; }

        .progress-container {
            width: 150px;
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-left: 20px;
        }

        .progress-bar {
            height: 100%;
            background-color: #3b82f6;
            border-radius: 4px;
            width: 0; /* Start at 0 for animation */
            animation: fillBar 1.5s ease-out 0.8s forwards; /* Delay matches card entrance */
        }

        @keyframes fillBar {
            to { width: var(--target-width); }
        }

        .recent-activity {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .activity-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        .activity-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .activity-details { flex: 1; }
        .activity-text { color: #334155; margin-bottom: 2px; }
        .activity-time { color: #94a3b8; font-size: 11px; }

        /* Custom Notification Styles */
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 2000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .notification {
            background: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #3b82f6;
            transform: translateX(120%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 300px;
            font-size: 14px;
            color: #334155;
        }

        .notification.show { transform: translateX(0); }
        .notification.success { border-left-color: #22c55e; }
        .notification.error { border-left-color: #ef4444; }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.open {
            display: flex;
            opacity: 1;
        }

        .modal-card {
            background: white;
            width: 100%;
            max-width: 500px;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.open .modal-card {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-header h2 {
            margin: 0;
            color: #0f172a;
            font-size: 20px;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #64748b;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #0f172a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            background-color: #1e293b;
        }

        /* Summary Card Specifics */
        .summary-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .summary-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        /* Dark Mode Styles */
        body.dark-mode { background-color: #0f172a; color: #e2e8f0; }
        .dark-mode .card, .dark-mode .modal-card { background-color: #1e293b; border-color: #334155; color: #e2e8f0; }
        .dark-mode .header h1, .dark-mode .section-title, .dark-mode .modal-header h2, .dark-mode h3 { color: #f8fafc !important; }
        .dark-mode .form-control, .dark-mode select { background-color: #334155; border-color: #475569; color: white; }
        .dark-mode .form-group label { color: #94a3b8; }
        .dark-mode #realTimeClock { background-color: #1e293b; border-color: #334155; color: #e2e8f0; }
        .dark-mode .ward-item, .dark-mode .activity-item { border-bottom-color: #334155; }
        .dark-mode .ward-name, .dark-mode .activity-text { color: #f8fafc; }

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
            .sidebar, #admitBtn, #notification-container { display: none !important; }
            .main-content { padding: 0; margin: 0; overflow: visible; }
            body { background-color: white; height: auto; display: block; }
            .card { box-shadow: none; border: 1px solid #e2e8f0; break-inside: avoid; margin-bottom: 20px; opacity: 1 !important; animation: none !important; }
            .dashboard-grid, .dashboard-row { display: block; }
            .header h1 { color: black !important; }
        }
    </style>
</head>
<body>
    <div id="notification-container" class="notification-container"></div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="logo-text">BedMatrix</span>
            </div>
        </div>
        <ul class="nav-links">
            <li>
                <a href="#" class="active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span class="link-text">Dashboard Overview</span>
                </a>
            </li>
            <li>
                <a href="bed_management.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22h20"></path><path d="M2 10h20"></path><path d="M5 10v12"></path><path d="M19 10v12"></path><path d="M4 6h16"></path></svg>
                    <span class="link-text">Bed Management</span>
                </a>
            </li>
            <li>
                <a href="patient_management.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                    <span class="link-text">Patient Admissions</span>
                </a>
            </li>
            <li>
                <a href="discharge_unit.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg>
                    <span class="link-text">Discharge Unit</span>
                </a>
            </li>
            <li>
                <a href="notifications.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <span class="link-text">Notifications</span>
                </a>
            </li>
            <li>
                <a href="reports_analytics.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    <span class="link-text">Reports & Analytics</span>
                </a>
            </li>
            <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                <a href="#" id="logoutBtn" style="color: #ef4444;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span class="link-text">Logout</span>
                </a>
            </li>
        </ul>
        <div class="user-profile" onclick="window.location.href='profile.php'">
            <div class="avatar" id="userAvatar">JS</div>
            <div>
                <div style="font-size: 14px; font-weight: 600;" id="userName">John Smith</div>
                <div style="font-size: 12px; color: #94a3b8;" id="userRole">Head Nurse</div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="header">
            <h1>Dashboard</h1>
            <div style="display: flex; align-items: center; gap: 15px;">
                <div id="realTimeClock" style="background: white; padding: 10px 16px; border-radius: 8px; font-weight: 600; color: #334155; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; font-size: 14px;"></div>
                <button id="admitBtn" style="padding: 12px 24px; background: #0f172a; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); transition: all 0.2s;">+ Admit Patient</button>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <div class="summary-content">
                    <div>
                        <h3 style="margin: 0 0 5px 0; color: #64748b; font-size: 13px; font-weight: 600; letter-spacing: 0.5px;">TOTAL BEDS</h3>
                        <div style="font-size: 36px; font-weight: 800; color: #0f172a;" id="totalBedsCount">150</div>
                        <div style="font-size: 13px; color: #22c55e; margin-top: 5px;">↑ 2 new added</div>
                    </div>
                    <div class="summary-icon" style="background-color: #f1f5f9; color: #475569;">🛏️</div>
                </div>
            </div>
            <div class="card">
                <div class="summary-content">
                    <div>
                        <h3 style="margin: 0 0 5px 0; color: #64748b; font-size: 13px; font-weight: 600; letter-spacing: 0.5px;">AVAILABLE</h3>
                        <div style="font-size: 36px; font-weight: 800; color: #22c55e;" id="availableBedsCount">18</div>
                        <div style="font-size: 13px; color: #64748b; margin-top: 5px;">17.4% capacity</div>
                    </div>
                    <div class="summary-icon" style="background-color: #dcfce7; color: #166534;">✅</div>
                </div>
            </div>
            <div class="card">
                <div class="summary-content">
                    <div>
                        <h3 style="margin: 0 0 5px 0; color: #64748b; font-size: 13px; font-weight: 600; letter-spacing: 0.5px;">OCCUPIED</h3>
                        <div style="font-size: 36px; font-weight: 800; color: #ef4444;" id="occupiedBedsCount">76</div>
                        <div style="font-size: 13px; color: #ef4444; margin-top: 5px;">High demand</div>
                    </div>
                    <div class="summary-icon" style="background-color: #fee2e2; color: #991b1b;">👤</div>
                </div>
            </div>
            <div class="card">
                <div class="summary-content">
                    <div>
                        <h3 style="margin: 0 0 5px 0; color: #64748b; font-size: 13px; font-weight: 600; letter-spacing: 0.5px;">UNTIDY</h3>
                        <div style="font-size: 36px; font-weight: 800; color: #3b82f6;" id="untidyBedsCount">9</div>
                        <div style="font-size: 13px; color: #3b82f6; margin-top: 5px;">Needs attention</div>
                    </div>
                    <div class="summary-icon" style="background-color: #dbeafe; color: #1e40af;">🧹</div>
                </div>
            </div>
            <div class="card">
                <div class="summary-content">
                    <div>
                        <h3 style="margin: 0 0 5px 0; color: #64748b; font-size: 13px; font-weight: 600; letter-spacing: 0.5px;">ADMITTED TODAY</h3>
                        <div style="font-size: 36px; font-weight: 800; color: #8b5cf6;" id="admittedTodayCount">0</div>
                        <div style="font-size: 13px; color: #8b5cf6; margin-top: 5px;">New arrivals</div>
                    </div>
                    <div class="summary-icon" style="background-color: #f3e8ff; color: #7c3aed;">📅</div>
                </div>
            </div>
        </div>

        <div class="dashboard-row" style="grid-template-columns: 1fr 1fr; margin-bottom: 24px;">
            <div class="card">
                <div class="section-title">Patient Gender Distribution</div>
                <div style="height: 250px; position: relative;">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="section-title">Bed Occupancy Trends (7 Days)</div>
                <div style="height: 250px; position: relative;">
                    <canvas id="occupancyChart"></canvas>
                </div>
            </div>
        </div>

        <div class="dashboard-row">
            <div class="card">
                <div class="section-title">Ward Capacity</div>
                <div class="ward-list" id="wardListContainer">
                    <div style="padding: 20px; text-align: center; color: #94a3b8;">Loading ward data...</div>
                </div>
            </div>
            <div class="card">
                <div class="section-title">Recent Activity</div>
                <ul class="recent-activity" id="recentActivityList">
                    <li class="activity-item" style="justify-content: center; color: #94a3b8;">No recent activity</li>
                </ul>
            </div>
        </div>
    </main>

    <!-- Patient Registration Modal -->
    <div class="modal-overlay" id="admissionModal">
        <div class="modal-card">
            <div class="modal-header">
                <h2>Admit New Patient</h2>
                <button class="close-btn" onclick="closeModal()">×</button>
            </div>
            <form id="admissionForm">
                <div class="form-group">
                    <label>Patient Full Name</label>
                    <input type="text" id="pName" class="form-control" required placeholder="e.g. Jane Doe">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" id="pAge" class="form-control" required placeholder="Age">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select id="pGender" class="form-control">
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Condition / Diagnosis</label>
                    <input type="text" id="pCondition" class="form-control" required placeholder="e.g. Cardiac Arrest">
                </div>
                <div class="form-group">
                    <label>Assign Ward</label>
                    <select id="pWard" class="form-control">
                        <!-- Populated dynamically -->
                    </select>
                </div>
                <button type="submit" class="btn-submit">Register Patient</button>
            </form>
        </div>
    </div>

    <script>
        <?php require_once 'session_check.php'; ?>;

        // Check Dark Mode
        if (localStorage.getItem('nbs_dark_mode') === 'true') {
            document.body.classList.add('dark-mode');
        }

        const btnAdmit = document.getElementById('admitBtn');

        btnAdmit.addEventListener('click', () => {
            // Redirect to the full-featured patient management page for admissions
            window.location.href = 'patient_management.php?action=admit';
        });

        function showNotification(message, type) {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            container.appendChild(notification);
            
            // Save to LocalStorage
            const notifData = { message, type, timestamp: new Date().toLocaleString() };
            const storedNotifs = JSON.parse(localStorage.getItem('nbs_notifications') || '[]');
            storedNotifs.unshift(notifData);
            localStorage.setItem('nbs_notifications', JSON.stringify(storedNotifs));

            setTimeout(() => notification.classList.add('show'), 10);
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        document.getElementById('admissionForm').addEventListener('submit', (e) => {
            e.preventDefault();
            
            const name = document.getElementById('pName').value;
            const age = document.getElementById('pAge').value;
            const gender = document.getElementById('pGender').value;
            const condition = document.getElementById('pCondition').value;
            const ward = document.getElementById('pWard').value;

            const allBeds = JSON.parse(localStorage.getItem('nbs_beds') || '[]');
            
            // Find first available bed in selected ward
            const bedIndex = allBeds.findIndex(b => b.ward === ward && b.status === 'available');

            if (bedIndex > -1) {
                // Update bed data
                allBeds[bedIndex].status = 'occupied';
                allBeds[bedIndex].patient = name;
                allBeds[bedIndex].age = age;
                allBeds[bedIndex].gender = gender;
                allBeds[bedIndex].conditionStatus = 'Stable'; // Default
                allBeds[bedIndex].diagnosis = condition;
                allBeds[bedIndex].admissionDate = new Date().toISOString().split('T')[0];
                allBeds[bedIndex].doctor = 'Dr. Assigned';
                allBeds[bedIndex].patientId = `P-${Date.now().toString().slice(-6)}`;

                localStorage.setItem('nbs_beds', JSON.stringify(allBeds));
                
                playSuccessSound();
                showNotification(`Patient admitted successfully to ${allBeds[bedIndex].id}`, 'success');
                closeModal();
                e.target.reset();
                
                // Refresh Dashboard Stats
                loadDashboardStats();
            } else {
                showNotification(`No available beds found in ${ward}`, 'error');
            }
        });

        // Session Management
        document.addEventListener('DOMContentLoaded', () => {
            const currentUser = <?php echo json_encode($_SESSION['nbs_current_user']); ?>;

            // Update Profile
            document.getElementById('userName').textContent = currentUser.fullName;
            
            // Map Role Codes to Full Names
            const roleMap = {
                'DOC': 'Doctor', 'ADM': 'Administrator', 'NUR': 'Nurse', 
                'WMG': 'Ward Manager', 'REC': 'Records Officer'
            };
            document.getElementById('userRole').textContent = roleMap[currentUser.role] || currentUser.role;

            // Initials
            const initials = currentUser.fullName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
            document.getElementById('userAvatar').textContent = initials;

            // Logout
            document.getElementById('logoutBtn').addEventListener('click', () => {
                fetch('api.php?action=logout')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    }
                });
            });

            initDashboard();
        });

        // Real-time Clock
        function updateClock() {
            const now = new Date();
            const datePart = now.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
            const timePart = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const clockEl = document.getElementById('realTimeClock');
            if (clockEl) {
                clockEl.innerHTML = `<span style="color: #94a3b8; margin-right: 8px;">${datePart}</span>${timePart}`;
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        let genderChart = null;
        let occupancyChart = null;

        function initDashboard() {
            fetch('api.php?action=get_dashboard_stats')
                .then(res => res.json())
                .then(response => {
                    if (!response.success) {
                        showNotification('Failed to load dashboard data.', 'error');
                        return;
                    }
                    const stats = response.stats;

                    // Update Summary Cards
                    document.getElementById('totalBedsCount').textContent = stats.totalBeds || 0;
                    document.getElementById('availableBedsCount').textContent = stats.available || 0;
                    document.getElementById('occupiedBedsCount').textContent = stats.occupied || 0;
                    document.getElementById('untidyBedsCount').textContent = stats.untidy || 0;
                    document.getElementById('admittedTodayCount').textContent = stats.admittedToday || 0;

                    // Update Ward Capacity List
                    const wardListContainer = document.getElementById('wardListContainer');
                    wardListContainer.innerHTML = '';
                    if (stats.wards && stats.wards.length > 0) {
                        stats.wards.forEach(ward => {
                            const percentage = ward.total > 0 ? Math.round((ward.occupied / ward.total) * 100) : 0;
                            let color = '#22c55e'; // Green
                            if (percentage > 50) color = '#f59e0b'; // Orange
                            if (percentage > 80) color = '#ef4444'; // Red

                            const item = document.createElement('div');
                            item.className = 'ward-item';
                            item.innerHTML = `
                                <div class="ward-info">
                                    <div class="ward-name">${ward.ward}</div>
                                    <div class="ward-stats">${ward.occupied} / ${ward.total} Occupied</div>
                                </div>
                                <div class="progress-container">
                                    <div class="progress-bar" style="--target-width: ${percentage}%; background-color: ${color};"></div>
                                </div>
                            `;
                            wardListContainer.appendChild(item);
                        });
                    } else {
                        wardListContainer.innerHTML = '<div style="padding: 20px; text-align: center; color: #94a3b8;">No ward data available.</div>';
                    }

                    // Update Recent Activity
                    const activityList = document.getElementById('recentActivityList');
                    activityList.innerHTML = '';
                    if (stats.activity && stats.activity.length > 0) {
                        stats.activity.forEach(act => {
                            const li = document.createElement('li');
                            li.className = 'activity-item';
                            li.innerHTML = `
                                <div class="activity-icon" style="color: #3b82f6; background-color: #dbeafe;">✓</div>
                                <div class="activity-details">
                                    <div class="activity-text"><b>${act.patientName}</b> was discharged from bed ${act.bedId}.</div>
                                    <div class="activity-time">${act.dischargeDate}</div>
                                </div>
                            `;
                            activityList.appendChild(li);
                        });
                    } else {
                        activityList.innerHTML = '<li class="activity-item" style="justify-content: center; color: #94a3b8;">No recent activity</li>';
                    }

                    // Initialize Charts
                    updateCharts(stats);
                });
        }

        function updateCharts(stats) {
            const maleCount = stats.gender['Male'] || 0;
            const femaleCount = stats.gender['Female'] || 0;

            const ctxGender = document.getElementById('genderChart').getContext('2d');
            if (genderChart) genderChart.destroy();
            genderChart = new Chart(ctxGender, {
                type: 'doughnut',
                data: {
                    labels: ['Male', 'Female'],
                    datasets: [{
                        data: [maleCount, femaleCount],
                        backgroundColor: ['#3b82f6', '#ec4899'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                    },
                    cutout: '70%'
                }
            });

            const ctxOccupancy = document.getElementById('occupancyChart').getContext('2d');
            if (occupancyChart) occupancyChart.destroy();
            occupancyChart = new Chart(ctxOccupancy, {
                type: 'line',
                data: {
                    labels: Object.keys(stats.occupancyTrend),
                    datasets: [{
                        label: 'Occupancy',
                        data: Object.values(stats.occupancyTrend),
                        borderColor: '#0f172a',
                        backgroundColor: 'rgba(15, 23, 42, 0.05)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#0f172a'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    </script>
</body>
</html>