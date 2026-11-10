<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed Management - BedMatrix</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2338bdf8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M22 12h-4l-3 9L9 3l-3 9H2'/></svg>">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            height: 100vh;
            background-color: #f1f5f9;
        }

        /* Sidebar Styles (Consistent with Dashboard) */
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

        /* Controls Toolbar */
        .controls-toolbar {
            display: flex;
            gap: 24px;
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 30px;
            flex-wrap: wrap;
            align-items: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .controls-toolbar:hover {
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        .search-container {
            position: relative;
            flex-grow: 1;
            min-width: 280px;
        }

        .control-input {
            width: 100%;
            padding: 14px 14px 14px 48px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s ease;
            outline: none;
            box-sizing: border-box;
            background-color: #f8fafc;
        }

        .control-input:focus {
            background-color: white;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.2s;
        }

        .search-container:focus-within .search-icon {
            color: #3b82f6;
        }

        .control-select {
            padding: 14px 20px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            background-color: #f8fafc;
            transition: all 0.2s ease;
            outline: none;
            min-width: 160px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        .control-select:hover {
            background-color: white;
            border-color: #94a3b8;
        }

        .control-select:focus {
            background-color: white;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .btn-add-bed {
            padding: 14px 28px;
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.2);
        }

        .btn-add-bed:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.3);
        }

        .btn-add-bed:active {
            transform: translateY(0);
        }

        /* Bed Management Specific Styles */
        .legend-bar {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .legend-item { display: flex; align-items: center; gap: 8px; font-size: 14px; color: #64748b; font-weight: 500; cursor: pointer; user-select: none; }
        .dot { width: 10px; height: 10px; border-radius: 50%; }
        .dot.available { background-color: #22c55e; }
        .dot.occupied { background-color: #ef4444; }
        .dot.reserved { background-color: #eab308; }
        .dot.discharging { background-color: #f97316; }
        .dot.untidy { background-color: #3b82f6; }
        .dot.faulty { background-color: #64748b; }
        .dot.isolation { background-color: #a855f7; }

        .ward-section { margin-bottom: 40px; }
        .ward-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }
        .ward-title { font-size: 18px; font-weight: 700; color: #334155; margin: 0; }
        
        .bed-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 20px;
        }

        .bed-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .bed-card[draggable="true"] { cursor: grab; }
        .bed-card[draggable="true"]:active { cursor: grabbing; }

        .bed-card:hover { transform: translateY(-3px); box-shadow: 0 8px 12px rgba(0,0,0,0.1); }

        .bed-icon {
            font-size: 24px;
            margin-bottom: 10px;
            display: inline-block;
            padding: 8px;
            border-radius: 50%;
        }

        .bed-number { font-weight: 700; color: #1e293b; font-size: 16px; margin-bottom: 4px; }
        .patient-name { font-size: 12px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* Status Colors */
        .bed-card.available { border-top: 4px solid #22c55e; }
        .bed-card.available .bed-icon { background-color: #dcfce7; color: #166534; }
        
        .bed-card.occupied { border-top: 4px solid #ef4444; }
        .bed-card.occupied .bed-icon { background-color: #fee2e2; color: #991b1b; }
        
        .bed-card.reserved { border-top: 4px solid #eab308; }
        .bed-card.reserved .bed-icon { background-color: #fef9c3; color: #854d0e; }

        .bed-card.discharging { border-top: 4px solid #f97316; }
        .bed-card.discharging .bed-icon { background-color: #ffedd5; color: #c2410c; }

        .bed-card.untidy { border-top: 4px solid #3b82f6; }
        .bed-card.untidy .bed-icon { background-color: #dbeafe; color: #1e40af; }

        .bed-card.faulty { border-top: 4px solid #64748b; }
        .bed-card.faulty .bed-icon { background-color: #f1f5f9; color: #475569; }

        .bed-card.isolation { border-top: 4px solid #a855f7; }
        .bed-card.isolation .bed-icon { background-color: #f3e8ff; color: #7e22ce; }

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
        .notification.alert { border-left-color: #ef4444; }
        .notification.warning { border-left-color: #f59e0b; }

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
            max-width: 400px;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        #bedModal .modal-card {
            max-width: 750px;
            padding: 15px;
        }

        .modal-overlay.open .modal-card {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h2 { margin: 0; color: #0f172a; font-size: 20px; }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #64748b;
            cursor: pointer;
        }

        /* Enhanced Modal Details Styles */
        .status-banner {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .status-text-group { display: flex; flex-direction: column; }
        .status-label { font-size: 12px; opacity: 0.8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
        .status-value { font-size: 20px; font-weight: 800; text-transform: capitalize; }
        
        .section-header {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            font-weight: 700;
            margin: 15px 0 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-header::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .detail-box {
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s;
        }
        .detail-box:hover { border-color: #cbd5e1; }
        .detail-box .detail-label { font-size: 11px; color: #64748b; margin-bottom: 4px; text-transform: uppercase; font-weight: 600; }
        .detail-box .detail-value { font-size: 14px; color: #0f172a; font-weight: 600; }

        .modal-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .btn-action {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            color: white;
        }
        
        .btn-primary { background-color: #0f172a; }
        .btn-danger { background-color: #ef4444; }
        .btn-success { background-color: #22c55e; }
        .btn-warning { background-color: #eab308; }
        .btn-info { background-color: #3b82f6; }
        
        .btn-action:hover { opacity: 0.9; }

        @media print {
            .sidebar, .header, .legend-bar, #wardsContainer {
                display: none !important;
            }
            .modal-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: auto;
                background: white;
                z-index: 9999;
            }
            .modal-card {
                box-shadow: none;
                border: none;
                width: 100%;
                max-width: 100%;
                transform: none !important;
                padding: 0;
            }
            .close-btn, .modal-actions {
                display: none !important;
            }
            body {
                background-color: white;
                height: auto;
                overflow: visible;
            }
            .main-content {
                padding: 0;
                margin: 0;
            }
        }

        /* Dark Mode Styles */
        body.dark-mode { background-color: #0f172a; color: #e2e8f0; }
        .dark-mode .controls-toolbar, .dark-mode .legend-bar, .dark-mode .modal-card { background-color: #1e293b; border-color: #334155; color: #e2e8f0; }
        .dark-mode .header h1, .dark-mode .modal-header h2, .dark-mode .ward-title { color: #f8fafc !important; }
        .dark-mode input[type="text"], .dark-mode input[type="number"], .dark-mode select { background-color: #334155; border-color: #475569; color: white; }
        .dark-mode .bed-card { background-color: #1e293b; border-color: #334155; }
        .dark-mode .bed-number { color: #f8fafc; }
        .dark-mode .detail-box { background-color: #0f172a; border-color: #334155; }
        .dark-mode .detail-value { color: #f8fafc; }
        .dark-mode .status-banner { border-color: #334155; }
        .dark-mode .section-header { color: #94a3b8; }
        .dark-mode .control-input, .dark-mode .control-select { background-color: #334155; border-color: #475569; color: white; }
        
        /* SVG Icon Sizing for Modal */
        .bed-icon-large svg { width: 48px; height: 48px; }
        .bed-icon svg { width: 24px; height: 24px; }

        /* History Table Styles */
        .history-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .history-table th, .history-table td { padding: 10px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .history-table th { background-color: #f8fafc; color: #64748b; font-weight: 600; }
        .dark-mode .history-table th { background-color: #0f172a; color: #94a3b8; border-bottom-color: #334155; }
        .dark-mode .history-table td { color: #e2e8f0; border-bottom-color: #334155; }
        .empty-history { text-align: center; padding: 20px; color: #94a3b8; }

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
                <a href="dashboard.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span class="link-text">Dashboard Overview</span>
                </a>
            </li>
            <li>
                <a href="bed_management.php" class="active">
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
            <div class="avatar" id="userAvatar">--</div>
            <div>
                <div style="font-size: 14px; font-weight: 600;" id="userName">Guest</div>
                <div style="font-size: 12px; color: #94a3b8;" id="userRole">Staff</div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="header">
            <h1>Bed Management</h1>
        </div>

        <div class="controls-toolbar">
            <div class="search-container">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="bedSearch" class="control-input" placeholder="Search bed or patient...">
                <span id="clearSearch" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; display: none; font-size: 18px;">&times;</span>
            </div>
            <select id="filterWardDropdown" class="control-select">
                <option value="all">All Wards</option>
            </select>
            <select id="filterBedsDropdown" class="control-select">
                <option value="all">Show All</option>
                <option value="available">Available</option>
                <option value="reserved">Reserved</option>
                <option value="occupied">Occupied</option>
                <option value="discharging">Discharging</option>
                <option value="isolation">Isolation</option>
                <option value="untidy">Untidy</option>
                <option value="faulty">Faulty</option>
            </select>
            <select id="sortBedsDropdown" class="control-select">
                <option value="id_asc">Sort by Bed No.</option>
                <option value="name_asc">Sort by Patient Name</option>
                <option value="date_desc">Sort by Admission (Newest)</option>
                <option value="date_asc">Sort by Admission (Oldest)</option>
            </select>
            <button onclick="openAddBedModal()" class="btn-add-bed"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Add New Bed</button>
        </div>

        <div class="legend-bar">
            <div class="legend-item" onclick="filterBeds('all')">Show All</div>
            <div class="legend-item" onclick="filterBeds('available')"><div class="dot available"></div> Available</div>
            <div class="legend-item" onclick="filterBeds('reserved')"><div class="dot reserved"></div> Reserved</div>
            <div class="legend-item" onclick="filterBeds('occupied')"><div class="dot occupied"></div> Occupied</div>
            <div class="legend-item" onclick="filterBeds('discharging')"><div class="dot discharging"></div> Discharging</div>
            <div class="legend-item" onclick="filterBeds('isolation')"><div class="dot isolation"></div> Isolation</div>
            <div class="legend-item" onclick="filterBeds('untidy')"><div class="dot untidy"></div> Untidy</div>
            <div class="legend-item" onclick="filterBeds('faulty')"><div class="dot faulty"></div> Faulty</div>
        </div>

        <div id="wardsContainer"></div>
    </main>

    <!-- Add Bed Modal -->
    <div class="modal-overlay" id="addBedModal">
        <div class="modal-card">
            <div class="modal-header">
                <h2>Add New Bed</h2>
                <button class="close-btn" onclick="closeAddBedModal()">×</button>
            </div>
            <form id="addBedForm">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">Select Ward</label>
                    <select id="newBedWard" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;" required></select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">Bed Number (Suffix)</label>
                    <input type="number" id="newBedNumber" placeholder="e.g. 21" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;" required>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">Bed Type</label>
                    <select id="newBedType" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                        <option>Standard</option>
                        <option>Adjustable</option>
                        <option>ICU Special</option>
                        <option>Pediatric</option>
                    </select>
                </div>
                <button type="submit" class="btn-action btn-primary">Create Bed</button>
            </form>
        </div>
    </div>

    <!-- Bed Details Modal -->
    <div class="modal-overlay" id="bedModal">
        <div class="modal-card">
            <div class="modal-header">
                <h2 id="modalBedTitle">Bed Details</h2>
                <button class="close-btn" onclick="closeBedModal()">×</button>
            </div>
            <div id="modalContent"></div>
            <div id="modalActions" class="modal-actions"></div>
        </div>
    </div>

    <!-- History Modal -->
    <div class="modal-overlay" id="historyModal">
        <div class="modal-card" style="max-width: 650px;">
            <div class="modal-header">
                <h2 id="historyModalTitle">Patient History</h2>
                <button class="close-btn" onclick="closeHistoryModal()">×</button>
            </div>
            <div id="historyContent" style="max-height: 400px; overflow-y: auto;"></div>
            <div class="modal-actions">
                <button class="btn-action btn-primary" onclick="closeHistoryModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        <?php require_once 'session_check.php'; ?>

        // Check Dark Mode
        if (localStorage.getItem('nbs_dark_mode') === 'true') {
            document.body.classList.add('dark-mode');
        }

        // --- Session & Role Management ---
        let currentUser = <?php echo json_encode($_SESSION['nbs_current_user']); ?>;

        // Update Sidebar Profile
        document.getElementById('userName').textContent = currentUser.fullName;
        const roleMap = { 'DOC': 'Doctor', 'ADM': 'Administrator', 'NUR': 'Nurse', 'WMG': 'Ward Manager', 'REC': 'Records Officer' };
        document.getElementById('userRole').textContent = roleMap[currentUser.role] || currentUser.role;
        const initials = currentUser.fullName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
        document.getElementById('userAvatar').textContent = initials;

        document.getElementById('logoutBtn').addEventListener('click', () => {
            fetch('api.php?action=logout')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    }
                });
        });

        // Mock Data for Wards and Beds
        const wards = [
            { name: "General Ward A", beds: 50, prefix: "GW-A" },
            { name: "General Ward B", beds: 50, prefix: "GW-B" },
            { name: "Intensive Care Unit (ICU)", beds: 30, prefix: "ICU" },
            { name: "Emergency Room", beds: 40, prefix: "ER" },
            { name: "Maternity Ward", beds: 30, prefix: "MAT" },
            { name: "Surgical Ward", beds: 40, prefix: "SUR" },
            { name: "Private Ward", beds: 15, prefix: "PVT" }
        ];

        // Populate Ward Filter
        const wardFilterSelect = document.getElementById('filterWardDropdown');
        wards.forEach(w => {
            const opt = document.createElement('option');
            opt.value = w.name;
            opt.textContent = w.name;
            wardFilterSelect.appendChild(opt);
        });
        wardFilterSelect.addEventListener('change', renderWards);

        // State Management
        let allBeds = [];

        // SVG Icons Map
        const bedIcons = {
            available: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>`,
            occupied: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>`,
            untidy: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>`,
            reserved: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
            faulty: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>`,
            discharging: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>`,
            isolation: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>`
        };

        function initApp() {
            fetch('api.php?action=get_beds')
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.beds.length > 0) {
                        allBeds = data.beds;
                        renderWards();
                    } else {
                        // If no beds, this is likely the first run. Seed the database.
                        seedDatabase();
                    }
                });
        }

        function seedDatabase() {
            allBeds = [];
            const maleNames = [
                "James Mensah", "John Boateng", "Michael Owusu", "Daniel Asante", "David Appiah",
                "Samuel Agyeman", "Joseph Osei", "Peter Addo", "Paul Tetteh", "Mark Annan",
                "Andrew Darko", "Stephen Amankwah", "Robert Nyarko", "Thomas Boadu", "Charles Frimpong",
                "Benjamin Yeboah", "Joshua Oppong", "Matthew Acheampong", "Luke Adu", "Simon Quartey",
                "Nicholas Lamptey", "Isaac Koranteng", "Jonathan Mensah", "Eric Boateng", "Victor Owusu",
                "Kelvin Asante", "Dennis Appiah", "Frank Agyeman", "Martin Osei", "Albert Addo",
                "Raymond Tetteh", "Patrick Annan", "Edward Darko", "Calvin Amankwah", "George Nyarko",
                "Henry Boadu", "Alfred Frimpong", "Emmanuel Yeboah", "Ronald Oppong", "Felix Acheampong",
                "Brian Adu", "Victor Quartey", "Leon Lamptey", "Moses Koranteng", "Aaron Mensah",
                "Jeffrey Boateng", "Oliver Owusu", "Allan Asante", "Trevor Appiah", "Stanley Agyeman",
                "Philip Osei", "Kenneth Addo", "Julian Tetteh", "Dominic Annan", "Victor Darko",
                "Adrian Amankwah", "Lawrence Nyarko", "Eugene Boadu", "Harold Frimpong", "Wilfred Yeboah",
                "Percy Oppong", "Clinton Acheampong", "Derrick Adu", "Marvin Quartey", "Ivan Lamptey",
                "Elijah Koranteng", "Leonard Mensah", "Austin Boateng", "Bruce Owusu", "Shawn Asante",
                "Colin Appiah", "Reginald Agyeman", "Tobias Osei", "Herman Addo", "Nelson Tetteh",
                "Wilson Annan", "Terrence Darko", "Maxwell Amankwah", "Gordon Nyarko", "Victor Boadu",
                "Fabian Frimpong", "Nathaniel Yeboah", "Oliver Oppong", "Damian Acheampong", "Miles Adu",
                "Preston Quartey", "Corey Lamptey", "Silas Koranteng", "Jordan Mensah", "Justin Boateng",
                "Lucas Owusu", "Cameron Asante", "Dylan Appiah", "Noel Agyeman", "Byron Osei",
                "Wayne Addo", "Desmond Tetteh", "Arnold Annan", "Zachary Amankwah", "Brandon Mensah",
                "Kyle Boateng", "Evan Owusu", "Scott Asante", "Neil Appiah", "Roger Agyeman",
                "Dean Osei", "Blake Addo", "Sean Tetteh", "Colin Annan", "Troy Darko",
                "Hugh Amankwah", "Keith Nyarko", "Logan Boadu", "Shane Frimpong", "Brett Yeboah",
                "Ryan Oppong", "Cole Acheampong", "Alan Adu", "Jude Quartey", "Victor Lamptey",
                "Marcus Koranteng", "Paul Mensah", "Mark Boateng", "Daniel Owusu", "James Asante",
                "Michael Appiah", "Samuel Agyeman", "Joseph Osei", "Peter Addo", "Paul Tetteh",
                "Mark Annan", "Andrew Darko", "Stephen Amankwah", "Robert Nyarko", "Thomas Boadu",
                "Charles Frimpong", "Benjamin Yeboah", "Joshua Oppong", "Matthew Acheampong", "Luke Adu",
                "Simon Quartey", "Nicholas Lamptey", "Isaac Koranteng", "Jonathan Mensah", "Eric Boateng",
                "Victor Owusu", "Kelvin Asante", "Dennis Appiah", "Frank Agyeman", "Martin Osei",
                "Albert Addo", "Raymond Tetteh", "Patrick Annan", "Edward Darko", "Calvin Amankwah",
                "George Nyarko", "Henry Boadu", "Alfred Frimpong", "Emmanuel Yeboah", "Ronald Oppong",
                "Felix Acheampong", "Brian Adu", "Victor Quartey", "Leon Lamptey", "Moses Koranteng",
                "Aaron Mensah", "Jeffrey Boateng", "Oliver Owusu", "Allan Asante", "Trevor Appiah",
                "Stanley Agyeman", "Philip Osei", "Kenneth Addo", "Julian Tetteh", "Dominic Annan",
                "Victor Darko", "Adrian Amankwah", "Lawrence Nyarko", "Eugene Boadu", "Harold Frimpong",
                "Wilfred Yeboah", "Percy Oppong", "Clinton Acheampong", "Derrick Adu", "Marvin Quartey",
                "Ivan Lamptey", "Elijah Koranteng", "Leonard Mensah", "Austin Boateng", "Bruce Owusu",
                "Shawn Asante", "Colin Appiah", "Reginald Agyeman", "Tobias Osei", "Herman Addo",
                "Nelson Tetteh", "Wilson Annan", "Terrence Darko", "Maxwell Amankwah", "Gordon Nyarko",
                "Victor Boadu", "Fabian Frimpong", "Nathaniel Yeboah", "Oliver Oppong", "Damian Acheampong",
                "Miles Adu", "Preston Quartey", "Corey Lamptey", "Silas Koranteng", "Jordan Mensah",
                "Justin Boateng", "Lucas Owusu", "Cameron Asante", "Dylan Appiah", "Noel Agyeman",
                "Byron Osei", "Wayne Addo", "Desmond Tetteh", "Arnold Annan"
            ];

            const femaleNames = [
                "Grace Mensah", "Mary Boateng", "Sarah Owusu", "Abigail Asante", "Esther Appiah",
                "Hannah Agyeman", "Elizabeth Osei", "Rebecca Addo", "Naomi Tetteh", "Rachel Annan",
                "Sophia Darko", "Deborah Amankwah", "Patricia Nyarko", "Christina Boadu", "Victoria Frimpong",
                "Angela Yeboah", "Janet Oppong", "Margaret Acheampong", "Lydia Adu", "Dorothy Quartey",
                "Priscilla Lamptey", "Joyce Koranteng", "Michelle Mensah", "Emily Boateng", "Vanessa Owusu",
                "Clara Asante", "Diana Appiah", "Faith Agyeman", "Janice Osei", "Linda Addo",
                "Gloria Tetteh", "Florence Annan", "Pauline Darko", "Roselyn Amankwah", "Beatrice Nyarko",
                "Martha Boadu", "Gertrude Frimpong", "Evelyn Yeboah", "Cynthia Oppong", "Alice Acheampong",
                "Irene Adu", "Monica Quartey", "Sharon Lamptey", "Ruth Koranteng", "Amanda Mensah",
                "Jessica Boateng", "Nicole Owusu", "Karen Asante", "Ella Appiah", "Hope Agyeman",
                "Josephine Osei", "Mercy Addo", "Theresa Tetteh", "Veronica Annan", "Anita Darko",
                "Frances Amankwah", "Phyllis Nyarko", "Adelaide Boadu", "Millicent Frimpong", "Paulina Yeboah",
                "Bridget Oppong", "Agnes Acheampong", "Bernice Adu", "Joan Quartey", "Stella Lamptey",
                "Comfort Koranteng", "Olivia Mensah", "Stephanie Boateng", "Natalie Owusu", "Lucy Asante",
                "Debra Appiah", "Grace Agyeman", "Felicia Osei", "Regina Addo", "Christabel Tetteh",
                "Helena Annan", "Mavis Darko", "Maureen Amankwah", "Edna Nyarko", "Victoria Boadu",
                "Eunice Frimpong", "Juliet Yeboah", "Sandra Oppong", "Charlotte Acheampong", "Winifred Adu",
                "Paulina Quartey", "Doris Lamptey", "Constance Koranteng", "Isabella Mensah", "Alexandra Boateng",
                "Victoria Owusu", "Melissa Asante", "Phoebe Appiah", "Peace Agyeman", "Serena Osei",
                "Belinda Addo", "Rosalind Tetteh", "Nora Annan", "Claudia Darko", "Victoria Amankwah",
                "Nancy Nyarko", "Ivy Boadu", "Deborah Frimpong", "Lilian Yeboah", "Doreen Oppong",
                "Madeline Acheampong", "Joy Adu", "Estelle Quartey", "Pearl Lamptey", "Charity Koranteng",
                "Victoria Mensah", "Jennifer Boateng", "Danielle Owusu", "Rebecca Asante", "Stacy Appiah",
                "Charmaine Agyeman", "Amanda Osei", "Angela Addo", "Janette Tetteh", "Sylvia Annan",
                "Catherine Darko", "Rebecca Amankwah", "Geraldine Nyarko", "Rosemary Boadu", "Dorcas Frimpong",
                "Leticia Yeboah", "Linda Oppong", "Therese Acheampong", "Victoria Adu", "Judith Quartey",
                "Hilda Lamptey", "Prudence Koranteng", "Melissa Mensah", "Karen Boateng", "Nicole Owusu",
                "Claudine Asante", "Erica Appiah", "Patience Agyeman", "Matilda Osei", "Verna Addo",
                "Anastasia Tetteh", "Iris Annan", "Selina Darko", "Sophia Amankwah", "Akosua Nyarko",
                "Adwoa Boadu", "Abena Frimpong", "Afia Yeboah", "Ama Oppong", "Esi Acheampong",
                "Naa Adu", "Akua Quartey", "Dede Lamptey", "Korkor Koranteng", "Evelyn Mensah",
                "Ruth Boateng", "Gloria Owusu", "Marian Asante", "Edwina Appiah", "Felicity Agyeman",
                "Irene Osei", "Janet Addo", "Monica Tetteh", "Vivian Annan", "Pauline Darko",
                "Alberta Amankwah", "Naomi Nyarko", "Cecilia Boadu", "Rebecca Frimpong", "Yvonne Yeboah",
                "Priscilla Oppong", "Angela Acheampong", "Maureen Adu", "Lydia Quartey", "Bernadette Lamptey",
                "Victoria Koranteng", "Esther Mensah", "Doris Boateng", "Regina Owusu", "Caroline Asante",
                "Hannah Appiah", "Mildred Agyeman", "Lucy Osei", "Diana Addo", "Gertrude Tetteh",
                "Alice Annan", "Janet Darko", "Philomena Amankwah", "Linda Nyarko", "Mabel Boadu",
                "Shirley Frimpong", "Monica Yeboah", "Cynthia Oppong", "Rita Acheampong", "Joyce Adu",
                "Eleanor Quartey", "Patricia Lamptey", "Sarah Koranteng", "Naomi Mensah", "Rebecca Boateng",
                "Theresa Owusu", "Abigail Asante", "Martha Appiah", "Grace Agyeman", "Josephine Osei",
                "Evelyn Addo", "Angela Tetteh", "Florence Annan", "Beatrice Darko", "Gladys Amankwah",
                "Irene Nyarko", "Mercy Boadu", "Victoria Frimpong", "Roselyn Yeboah", "Agnes Oppong",
                "Joan Acheampong", "Comfort Adu", "Victoria Quartey", "Stella Lamptey", "Esther Koranteng",
                "Charlotte Mensah", "Diana Boateng", "Pauline Owusu", "Judith Asante", "Linda Appiah",
                "Charity Agyeman", "Ruth Osei", "Regina Addo", "Sandra Tetteh", "Monica Annan",
                "Mavis Darko", "Priscilla Amankwah", "Deborah Nyarko", "Florence Boadu", "Evelyn Frimpong",
                "Gladys Yeboah", "Vivian Oppong", "Felicia Acheampong", "Naomi Adu", "Constance Quartey",
                "Bernice Lamptey", "Comfort Koranteng", "Michelle Mensah", "Erica Boateng", "Natalie Owusu",
                "Patricia Asante", "Faith Appiah", "Hope Agyeman", "Lucy Osei", "Angela Addo",
                "Theresa Tetteh", "Rebecca Annan", "Doris Darko", "Cecilia Amankwah", "Agnes Nyarko",
                "Mabel Boadu", "Millicent Frimpong", "Esther Yeboah", "Janet Oppong", "Rita Acheampong",
                "Joyce Adu", "Olivia Quartey", "Sarah Lamptey", "Prudence Koranteng", "Amanda Mensah",
                "Felicity Boateng", "Stephanie Owusu", "Irene Asante", "Monica Appiah", "Peace Agyeman",
                "Grace Osei", "Regina Addo", "Veronica Tetteh", "Helena Annan", "Maureen Darko",
                "Roselyn Amankwah", "Beatrice Nyarko", "Martha Boadu", "Eunice Frimpong", "Juliet Yeboah",
                "Sandra Oppong", "Charlotte Acheampong", "Winifred Adu", "Doris Quartey", "Stella Lamptey",
                "Comfort Koranteng", "Isabella Mensah", "Alexandra Boateng", "Victoria Owusu", "Melissa Asante",
                "Phoebe Appiah", "Patience Agyeman", "Matilda Osei", "Verna Addo", "Anastasia Tetteh",
                "Iris Annan", "Selina Darko", "Sophia Amankwah", "Akosua Nyarko", "Adwoa Boadu"
            ];

            wards.forEach(ward => {
                for(let i = 1; i <= ward.beds; i++) {
                    const rand = Math.random();
                    let status = 'available';
                    let patient = 'Empty';
                    let admissionDate = '-';
                    let gender = '-';
                    let age = '-';

                    if (rand > 0.30) {
                        status = 'occupied';
                        admissionDate = new Date().toISOString().split('T')[0];
                        gender = Math.random() > 0.5 ? 'Male' : 'Female';
                        age = Math.floor(Math.random() * 60) + 18;
                        
                        if (gender === 'Male') {
                            patient = maleNames[Math.floor(Math.random() * maleNames.length)];
                        } else {
                            patient = femaleNames[Math.floor(Math.random() * femaleNames.length)];
                        }
                    } else if (rand > 0.20) {
                        status = 'untidy';
                        patient = 'Needs Cleaning';
                    } else if (rand > 0.15) {
                        status = 'reserved';
                        patient = 'Incoming...';
                    } else if (rand > 0.10) {
                        status = 'discharging';
                        gender = Math.random() > 0.5 ? 'Male' : 'Female';
                        if (gender === 'Male') {
                            patient = maleNames[Math.floor(Math.random() * maleNames.length)];
                        } else {
                            patient = femaleNames[Math.floor(Math.random() * femaleNames.length)];
                        }
                        age = Math.floor(Math.random() * 60) + 18;
                    } else if (rand > 0.05) {
                        status = 'isolation';
                        gender = Math.random() > 0.5 ? 'Male' : 'Female';
                        if (gender === 'Male') {
                            patient = maleNames[Math.floor(Math.random() * maleNames.length)];
                        } else {
                            patient = femaleNames[Math.floor(Math.random() * femaleNames.length)];
                        }
                        age = Math.floor(Math.random() * 60) + 18;
                    } else if (rand > 0.02) {
                        status = 'faulty';
                        patient = 'Maintenance';
                    }

                    allBeds.push({
                        id: `${ward.prefix}-${String(i).padStart(2, '0')}`,
                        ward: ward.name,
                        status: status,
                        patient: patient,
                        type: (i % 2 === 0) ? 'Standard' : 'Adjustable',
                        room: `Room ${Math.ceil(i/4)}`,
                        admissionDate: admissionDate,
                        gender: gender,
                        age: age
                    });
                }
            });

            const mockHistory = [];
            allBeds.forEach(bed => {
                const count = Math.floor(Math.random() * 4);
                for(let k=0; k<count; k++) {
                    const pastDate = new Date();
                    pastDate.setDate(pastDate.getDate() - Math.floor(Math.random() * 100) - 10);
                    const dischargeDate = new Date(pastDate);
                    dischargeDate.setDate(dischargeDate.getDate() + Math.floor(Math.random() * 10) + 1);
                    mockHistory.push({
                        bedId: bed.id,
                        patientName: ['John Doe', 'Jane Smith', 'Alice Brown', 'Bob White'][Math.floor(Math.random()*4)],
                        admissionDate: pastDate.toISOString().split('T')[0],
                        dischargeDate: dischargeDate.toISOString().split('T')[0],
                        diagnosis: ['Flu', 'Fracture', 'Migraine', 'Infection'][Math.floor(Math.random()*4)]
                    });
                }
            });
            mockHistory.sort((a,b) => new Date(b.dischargeDate) - new Date(a.dischargeDate));

            // Post this data to the server to create the initial DB files
            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'seed_data', beds: allBeds, history: mockHistory })
            }).then(() => {
                renderWards(); // Now render with the newly seeded data
            });
        }

        // Listen for storage changes to update UI in real-time
        window.addEventListener('storage', (e) => {
            // This is no longer needed with a server backend
        });

        const container = document.getElementById('wardsContainer');

        function renderWards() {
            container.innerHTML = '';
            
            const selectedWard = document.getElementById('filterWardDropdown').value;
            const sortType = document.getElementById('sortBedsDropdown').value;
            const wardsToRender = selectedWard === 'all' ? wards : wards.filter(w => w.name === selectedWard);

            wardsToRender.forEach(ward => {
                const section = document.createElement('div');
                section.className = 'ward-section';
                
                // Filter beds for this ward
                const wardBeds = allBeds.filter(b => b.ward === ward.name);

                // Sort Beds
                wardBeds.sort((a, b) => {
                    if (sortType === 'name_asc') {
                        // Prioritize occupied beds, then sort by name
                        const aOccupied = ['occupied', 'isolation', 'discharging'].includes(a.status);
                        const bOccupied = ['occupied', 'isolation', 'discharging'].includes(b.status);
                        if (aOccupied && !bOccupied) return -1;
                        if (!aOccupied && bOccupied) return 1;
                        return a.patient.localeCompare(b.patient);
                    }
                    if (sortType === 'date_desc') {
                        const dateA = (a.admissionDate === '-' || !a.admissionDate) ? 0 : new Date(a.admissionDate).getTime();
                        const dateB = (b.admissionDate === '-' || !b.admissionDate) ? 0 : new Date(b.admissionDate).getTime();
                        return dateB - dateA;
                    }
                    if (sortType === 'date_asc') {
                        const dateA = (a.admissionDate === '-' || !a.admissionDate) ? 0 : new Date(a.admissionDate).getTime();
                        const dateB = (b.admissionDate === '-' || !b.admissionDate) ? 0 : new Date(b.admissionDate).getTime();
                        if (dateA === 0) return 1; // Push empty dates to bottom
                        if (dateB === 0) return -1;
                        return dateA - dateB;
                    }
                    // Default: Bed ID
                    return a.id.localeCompare(b.id);
                });
                
                let bedsHtml = '';
                wardBeds.forEach(bed => {
                    const icon = bedIcons[bed.status] || bedIcons['faulty'];

                    // Drag and Drop Attributes
                    let dragAttrs = '';
                    const canTransfer = ['NUR', 'ADM', 'WMG'].includes(currentUser.role);
                    
                    if (canTransfer) {
                        if (bed.status === 'occupied') {
                            dragAttrs = `draggable="true" ondragstart="drag(event)"`;
                        } else if (bed.status === 'available') {
                            dragAttrs = `ondrop="drop(event)" ondragover="allowDrop(event)"`;
                        }
                    }

                    bedsHtml += `
                        <div class="bed-card ${bed.status}" id="${bed.id}" onclick="openBedModal('${bed.id}')" ${dragAttrs}>
                            <div class="bed-icon">${icon}</div>
                            <div class="bed-number">${bed.id}</div>
                            <div class="patient-name">${bed.patient}</div>
                        </div>
                    `;
                });

                section.innerHTML = `
                    <div class="ward-header">
                        <h3 class="ward-title">${ward.name}</h3>
                        <span style="color: #64748b; font-size: 14px;">${wardBeds.length} Beds</span>
                    </div>
                    <div class="bed-grid">
                        ${bedsHtml}
                    </div>
                `;
                container.appendChild(section);
            });
            
            // Re-apply any active search/filter
            const statusFilter = document.getElementById('filterBedsDropdown').value;
            if(statusFilter !== 'all') filterBeds(statusFilter);

            const searchTerm = document.getElementById('bedSearch').value;
            if(searchTerm) document.getElementById('bedSearch').dispatchEvent(new Event('input'));
        }

        // Modal Logic
        const modal = document.getElementById('bedModal');
        const modalTitle = document.getElementById('modalBedTitle');
        const modalContent = document.getElementById('modalContent');
        const modalActions = document.getElementById('modalActions');

        function openBedModal(bedId) {
            const bed = allBeds.find(b => b.id === bedId);
            if(!bed) return;

            modalTitle.textContent = `Bed Details: ${bed.id}`;
            
            // Status Badge Color
            let statusColor = '#64748b';
            if(bed.status === 'available') statusColor = '#22c55e';
            if(bed.status === 'occupied') statusColor = '#ef4444';
            if(bed.status === 'reserved') statusColor = '#eab308';
            if(bed.status === 'discharging') statusColor = '#f97316';
            if(bed.status === 'untidy') statusColor = '#3b82f6';
            if(bed.status === 'faulty') statusColor = '#64748b';
            if(bed.status === 'isolation') statusColor = '#a855f7';

            // Background color for banner (lighter version)
            let bgMap = {
                'available': '#dcfce7', 'occupied': '#fee2e2', 'reserved': '#fef9c3',
                'discharging': '#ffedd5', 'untidy': '#dbeafe', 'faulty': '#f1f5f9', 'isolation': '#f3e8ff'
            };
            let bannerBg = bgMap[bed.status] || '#f1f5f9';

            let detailsHtml = `
                <div class="status-banner" style="background-color: ${bannerBg}; color: ${statusColor};">
                    <div style="font-size: 32px;">${bed.status === 'occupied' ? '👤' : (bed.status === 'available' ? '✅' : '⚠️')}</div>
                    <div class="bed-icon-large">${bedIcons[bed.status] || bedIcons['faulty']}</div>
                    <div class="status-text-group">
                        <span class="status-label">Current Status</span>
                        <span class="status-value">${bed.status}</span>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <div class="section-header">Bed Information</div>
                        <div class="detail-grid">
                            <div class="detail-box"><div class="detail-label">Ward</div><div class="detail-value">${bed.ward}</div></div>
                            <div class="detail-box"><div class="detail-label">Room</div><div class="detail-value">${bed.room}</div></div>
                            <div class="detail-box"><div class="detail-label">Type</div><div class="detail-value">${bed.type}</div></div>
                            <div class="detail-box"><div class="detail-label">ID</div><div class="detail-value">${bed.id}</div></div>
                        </div>
                    </div>
                    <div>
            `;

            if (bed.status === 'occupied' || bed.status === 'discharging' || bed.status === 'isolation') {
                detailsHtml += `
                    <div class="section-header">Patient Information</div>
                    <div class="detail-grid">
                        <div class="detail-box" style="grid-column: span 2;"><div class="detail-label">Patient Name</div><div class="detail-value">${bed.patient}</div></div>
                        <div class="detail-box"><div class="detail-label">Age / Gender</div><div class="detail-value">${bed.age} / ${bed.gender}</div></div>
                        <div class="detail-box"><div class="detail-label">Nationality</div><div class="detail-value">${bed.nationality || '-'}</div></div>
                        <div class="detail-box"><div class="detail-label">Admitted</div><div class="detail-value">${bed.admissionDate}</div></div>
                    </div>
                `;

                if (bed.emergencyContactName || bed.emergencyContactPhone) {
                    detailsHtml += `
                        <div class="section-header">Emergency Contact</div>
                        <div class="detail-grid">
                            <div class="detail-box"><div class="detail-label">Name</div><div class="detail-value">${bed.emergencyContactName || '-'}</div></div>
                            <div class="detail-box"><div class="detail-label">Phone</div><div class="detail-value">${bed.emergencyContactPhone || '-'}</div></div>
                        </div>
                    `;
                }
            } else if (bed.status === 'untidy') {
                detailsHtml += `
                    <div class="section-header">Cleaning Status</div>
                    <div class="detail-grid">
                        <div class="detail-box"><div class="detail-label">Condition</div><div class="detail-value">Needs Cleaning</div></div>
                        <div class="detail-box"><div class="detail-label">Priority</div><div class="detail-value">Normal</div></div>
                    </div>
                `;
            } else if (bed.status === 'faulty') {
                detailsHtml += `
                    <div class="section-header">Maintenance Report</div>
                    <div class="detail-grid">
                        <div class="detail-box"><div class="detail-label">Issue</div><div class="detail-value">Mechanical Failure</div></div>
                        <div class="detail-box"><div class="detail-label">Reported By</div><div class="detail-value">Nurse Station</div></div>
                    </div>
                `;
            } else if (bed.status === 'reserved') {
                detailsHtml += `
                    <div class="section-header">Reservation Details</div>
                    <div class="detail-grid">
                        <div class="detail-box"><div class="detail-label">Reserved For</div><div class="detail-value">Incoming Admission</div></div>
                        <div class="detail-box"><div class="detail-label">Priority</div><div class="detail-value" style="color: #ef4444;">High</div></div>
                    </div>
                `;
            } else {
                 detailsHtml += `
                    <div class="section-header">History</div>
                    <div class="detail-grid">
                        <div class="detail-box" style="grid-column: span 2;"><div class="detail-label">Last Occupant</div><div class="detail-value">Discharged Today</div></div>
                    </div>
                `;
            }

            detailsHtml += `</div></div>`;

            modalContent.innerHTML = detailsHtml;
            
            // Generate Actions based on Role and Status
            generateActions(bed);
            
            modal.classList.add('open');
        }

        function generateActions(bed) {
            let buttons = '';
            const role = currentUser.role;

            // Request Admission: Available -> Reserved (Doctor only)
            if (bed.status === 'available' && role === 'DOC') {
                buttons += `<button class="btn-action btn-warning" onclick="updateBedStatus('${bed.id}', 'reserved', 'Reserved for Admission')">Request Admission</button>`;
            }

            // Occupied -> Discharging (Doctor)
            if (bed.status === 'occupied' && ['DOC', 'ADM'].includes(role)) {
                buttons += `<button class="btn-action btn-warning" onclick="updateBedStatus('${bed.id}', 'discharging', '${bed.patient}')">Initiate Discharge</button>`;
            }

            // Discharging -> Untidy (Nurse)
            if (bed.status === 'discharging' && ['NUR', 'ADM'].includes(role)) {
                buttons += `<button class="btn-action btn-danger" onclick="updateBedStatus('${bed.id}', 'untidy', 'Needs Cleaning')">Patient Left (Mark Untidy)</button>`;
            }

            // Isolation -> Untidy (Nurse)
            if (bed.status === 'isolation' && ['NUR', 'ADM'].includes(role)) {
                buttons += `<button class="btn-action btn-danger" onclick="updateBedStatus('${bed.id}', 'untidy', 'Needs Deep Clean')">End Isolation</button>`;
            }

            // Untidy -> Available (Nurse, Admin, Ward Manager)
            if (bed.status === 'untidy' && ['NUR', 'ADM', 'WMG'].includes(role)) {
                buttons += `<button class="btn-action btn-success" onclick="updateBedStatus('${bed.id}', 'available', 'Empty')">Mark as Ready</button>`;
            }

            // Faulty: Any -> Faulty (Admin only)
            if (role === 'ADM' && bed.status !== 'faulty') {
                buttons += `<button class="btn-action btn-warning" onclick="updateBedStatus('${bed.id}', 'faulty', 'Technician')">Report Fault</button>`;
            }
            
            // Faulty -> Available (Admin only)
            if (role === 'ADM' && bed.status === 'faulty') {
                buttons += `<button class="btn-action btn-success" onclick="updateBedStatus('${bed.id}', 'available', 'Empty')">Fault Resolved</button>`;
            }

            buttons += `<button class="btn-action btn-info" onclick="printBedDetails()">Print</button>`;
            buttons += `<button class="btn-action" style="background-color: #6366f1;" onclick="viewBedHistory('${bed.id}')">View History</button>`;

            buttons += `<button class="btn-action btn-primary" onclick="closeBedModal()">Close</button>`;
            modalActions.innerHTML = buttons;
        }

        function showNotification(message, type) {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            
            container.appendChild(notification);
            
            // Save to LocalStorage for Notifications Tab
            const notifData = {
                message: message,
                type: type,
                timestamp: new Date().toLocaleString()
            };
            const storedNotifs = JSON.parse(localStorage.getItem('nbs_notifications') || '[]');
            storedNotifs.unshift(notifData);
            localStorage.setItem('nbs_notifications', JSON.stringify(storedNotifs));

            // We can also save notifications to the server, but for popups, localStorage is fine.
            // The notifications.php page can then read from localStorage as it does now.

            void notification.offsetWidth;
            notification.classList.add('show');

            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        function updateBedStatus(bedId, newStatus, newPatientVal) {
            const formData = new FormData();
            formData.append('action', 'update_bed_status');
            formData.append('bedId', bedId);
            formData.append('newStatus', newStatus);
            formData.append('newPatientVal', newPatientVal);

            fetch('api.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    allBeds = data.beds;
                    renderWards();
                    closeBedModal();
                    if (newStatus === 'faulty') {
                        showNotification(`⚠️ ALERT: Bed ${bedId} reported as FAULTY! Maintenance team notified.`, 'alert');
                    } else if (newStatus === 'isolation') {
                        showNotification(`☣️ ALERT: Bed ${bedId} is now in ISOLATION mode. Strict protocols apply.`, 'warning');
                    }
                } else {
                    showNotification(data.message || 'Failed to update bed status.', 'alert');
                }
            });
        }

        function printBedDetails() {
            window.print();
        }

        function closeBedModal() {
            modal.classList.remove('open');
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeBedModal();
        });

        function filterBeds(status) {
            const cards = document.querySelectorAll('.bed-card');
            cards.forEach(card => {
                if (status === 'all' || card.classList.contains(status)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Search Functionality
        const searchInput = document.getElementById('bedSearch');
        const clearBtn = document.getElementById('clearSearch');

        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            clearBtn.style.display = term.length > 0 ? 'block' : 'none';
            const cards = document.querySelectorAll('.bed-card');
            
            cards.forEach(card => {
                const bedNum = card.querySelector('.bed-number').textContent.toLowerCase();
                const patient = card.querySelector('.patient-name').textContent.toLowerCase();
                
                if (bedNum.includes(term) || patient.includes(term)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.style.display = 'none';
            searchInput.dispatchEvent(new Event('input')); // Trigger input event to reset list
        });

        // Filter Dropdown Functionality
        document.getElementById('filterBedsDropdown').addEventListener('change', (e) => {
            filterBeds(e.target.value);
        });
        document.getElementById('sortBedsDropdown').addEventListener('change', (e) => {
            renderWards();
        });

        // --- Drag and Drop Logic ---
        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev) {
            ev.dataTransfer.setData("text", ev.target.id);
        }

        function drop(ev) {
            ev.preventDefault();
            const sourceId = ev.dataTransfer.getData("text");
            const targetCard = ev.target.closest('.bed-card');
            
            if (targetCard) {
                transferPatient(sourceId, targetCard.id);
            }
        }

        function transferPatient(sourceId, targetId) {
            if (sourceId === targetId) return;

            const sourceBed = allBeds.find(b => b.id === sourceId);
            const targetBed = allBeds.find(b => b.id === targetId);

            if (!sourceBed || !targetBed || sourceBed.status !== 'occupied' || targetBed.status !== 'available') {
                showNotification('Invalid transfer. Can only move Occupied -> Available.', 'warning');
                return;
            }

            if (confirm(`Transfer ${sourceBed.patient} from ${sourceId} to ${targetId}?`)) {
                fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'transfer_patient', sourceId, targetId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        allBeds = data.beds;
                        renderWards();
                        showNotification(`Transfer Successful: Patient moved to ${targetId}`, 'success');
                    } else {
                        showNotification(data.message || 'Transfer failed.', 'alert');
                    }
                });
            }
        }

        // --- Add Bed Logic ---
        const addBedModal = document.getElementById('addBedModal');
        const addBedForm = document.getElementById('addBedForm');
        const wardSelect = document.getElementById('newBedWard');

        function openAddBedModal() {
            // Check permissions (Admin/Ward Manager only)
            if (!['ADM', 'WMG'].includes(currentUser.role)) {
                showNotification('Access Denied: Only Admins can add beds.', 'alert');
                return;
            }

            wardSelect.innerHTML = '';
            wards.forEach(w => {
                const opt = document.createElement('option');
                opt.value = w.prefix;
                opt.textContent = w.name;
                opt.dataset.wardName = w.name;
                wardSelect.appendChild(opt);
            });
            addBedModal.classList.add('open');
        }

        function closeAddBedModal() {
            addBedModal.classList.remove('open');
        }

        addBedForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const prefix = wardSelect.value;
            const wardName = wardSelect.options[wardSelect.selectedIndex].dataset.wardName;
            const num = document.getElementById('newBedNumber').value;
            const type = document.getElementById('newBedType').value;
            
            const newBed = { 
                id: `${prefix}-${String(num).padStart(2, '0')}`, 
                ward: wardName, 
                status: 'available', 
                patient: 'Empty', 
                type: type, 
                room: 'New Room', 
                admissionDate: '-', gender: '-', age: '-' 
            };

            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'add_bed', ...newBed })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    allBeds = data.beds;
                    renderWards();
                    showNotification(`Bed ${newBed.id} created successfully.`, 'success');
                    closeAddBedModal();
                    addBedForm.reset();
                } else {
                    showNotification(data.message || 'Failed to create bed.', 'alert');
                }
            });
        });

        // --- History Logic ---
        const historyModal = document.getElementById('historyModal');
        
        function viewBedHistory(bedId) {
            document.getElementById('historyModalTitle').textContent = `History: ${bedId}`;
            fetch('api.php?action=get_history')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const bedHistory = data.history.filter(h => h.bedId === bedId).sort((a,b) => new Date(b.dischargeDate) - new Date(a.dischargeDate));
                        const content = document.getElementById('historyContent');
                        if (bedHistory.length === 0) {
                            content.innerHTML = '<div class="empty-history">No history records found for this bed.</div>';
                        } else {
                            let html = `
                                <table class="history-table">
                                    <thead>
                                        <tr>
                                            <th>Patient</th>
                                            <th>Admitted</th>
                                            <th>Discharged</th>
                                            <th>Diagnosis</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;
                            bedHistory.forEach(rec => {
                                html += `
                                    <tr>
                                        <td>${rec.patientName}</td>
                                        <td>${rec.admissionDate}</td>
                                        <td>${rec.dischargeDate}</td>
                                        <td>${rec.diagnosis || '-'}</td>
                                    </tr>
                                `;
                            });
                            html += '</tbody></table>';
                            content.innerHTML = html;
                        }
                        historyModal.classList.add('open');
                    }
                });
        }

        function closeHistoryModal() {
            historyModal.classList.remove('open');
        }

        function saveHistory(bed) {
            const record = {
                bedId: bed.id,
                patientName: bed.patient,
                admissionDate: bed.admissionDate,
                dischargeDate: new Date().toISOString().split('T')[0],
                diagnosis: bed.diagnosis || '-'
            };
            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'save_history', ...record })
            });
        }

        // Initialize
        initApp();
    </script>
</body>
</html>