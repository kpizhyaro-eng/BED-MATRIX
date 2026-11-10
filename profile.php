<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - BedMatrix</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2338bdf8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M22 12h-4l-3 9L9 3l-3 9H2'/></svg>">
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            height: 100vh;
            background-color: #f1f5f9;
        }

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

        .nav-links li { margin-bottom: 8px; position: relative; }

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

        .nav-links a:hover {
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
        .user-profile:hover { background-color: rgba(255, 255, 255, 0.1); }

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

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .header { margin-bottom: 15px; }
        .header h1 { margin: 0; color: #0f172a; font-size: 28px; }

        /* Profile Specifics */
        .profile-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 20px;
            align-items: start;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .profile-card {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            font-size: 36px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 8px; color: #64748b; font-size: 13px; font-weight: 600; text-transform: uppercase; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 15px; box-sizing: border-box; background-color: #f8fafc; }
        .form-control:read-only { color: #334155; }
        .form-control:not(:read-only) { background-color: white; border-color: #94a3b8; }
        .form-control:not(:read-only):focus { outline: none; border-color: #3b82f6; ring: 2px solid rgba(59, 130, 246, 0.1); }

        .btn-save {
            background-color: #0f172a; color: white; border: none; padding: 12px 24px; border-radius: 6px;
            cursor: pointer; font-weight: 600; margin-top: 10px; transition: background 0.2s; width: 100%;
        }
        .btn-save:hover { background-color: #1e293b; }

        /* Notification Styles */
        .notification-container { position: fixed; top: 20px; right: 20px; z-index: 3000; display: flex; flex-direction: column; gap: 10px; }
        .notification { background: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border-left: 4px solid #3b82f6; transform: translateX(120%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); min-width: 300px; font-size: 14px; color: #334155; }
        .notification.show { transform: translateX(0); }
        .notification.success { border-left-color: #22c55e; }
        .notification.error { border-left-color: #ef4444; }

        /* Password Toggle & Upload Styles */
        .password-wrapper { position: relative; }
        .password-wrapper .form-control { padding-right: 40px; }
        .password-toggle {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: #94a3b8; user-select: none; display: flex;
        }
        .password-toggle:hover { color: #3b82f6; }
        
        .avatar-container { position: relative; margin-bottom: 16px; }
        .upload-btn {
            position: absolute; bottom: 0; right: 0; background: #0f172a; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s; border: 2px solid white;
        }
        .upload-btn:hover { background: #3b82f6; }
        #profileImagePreview { display: none; object-fit: cover; }

        /* Dark Mode Styles */
        body.dark-mode { background-color: #0f172a; color: #e2e8f0; }
        .dark-mode .card { background-color: #1e293b; border-color: #334155; color: #e2e8f0; }
        .dark-mode h2, .dark-mode h3, .dark-mode label { color: #f8fafc !important; }
        .dark-mode .form-control { background-color: #334155; border-color: #475569; color: white; }
        .dark-mode .form-control:read-only { color: #94a3b8; background-color: #0f172a; }
        .dark-mode .form-group label { color: #94a3b8 !important; }
        .dark-mode .profile-name { color: white !important; }
        
        /* Toggle Switch */
        .switch { position: relative; display: inline-block; width: 50px; height: 26px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #3b82f6; }
        input:checked + .slider:before { transform: translateX(24px); }

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

        /* Tom Select Customization */
        .ts-wrapper.country-select { width: 110px; flex-shrink: 0; }
        .ts-wrapper.nationality-select { width: 100%; }
        .ts-control {
            padding: 8px 8px !important;
            min-height: unset !important;
            height: 42px;
            border-radius: 6px !important;
            border-color: #cbd5e1 !important;
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
            <li><a href="dashboard.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> <span class="link-text">Dashboard Overview</span></a></li>
            <li><a href="bed_management.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22h20"></path><path d="M2 10h20"></path><path d="M5 10v12"></path><path d="M19 10v12"></path><path d="M4 6h16"></path></svg> <span class="link-text">Bed Management</span></a></li>
            <li><a href="patient_management.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg> <span class="link-text">Patient Admissions</span></a></li>
            <li><a href="discharge_unit.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg> <span class="link-text">Discharge Unit</span></a></li>
            <li><a href="notifications.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg> <span class="link-text">Notifications</span></a></li>
            <li><a href="reports_analytics.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg> <span class="link-text">Reports & Analytics</span></a></li>
            <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                <a href="#" id="logoutBtn" style="color: #ef4444;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span class="link-text">Logout</span></a>
            </li>
        </ul>
        <div class="user-profile">
            <div class="avatar" id="userAvatar">--</div>
            <div>
                <div style="font-size: 14px; font-weight: 600;" id="userName">Guest</div>
                <div style="font-size: 12px; color: #94a3b8;" id="userRole">Staff</div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="header">
            <h1>User Profile</h1>
        </div>

        <div class="profile-grid">
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="card profile-card">
                    <div class="avatar-container">
                        <div class="profile-avatar-large" id="profileAvatar">--</div>
                        <img id="profileImagePreview" class="profile-avatar-large" src="">
                        <label for="avatarUpload" class="upload-btn" title="Upload Photo"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg></label>
                        <input type="file" id="avatarUpload" accept="image/*" style="display: none;">
                    </div>
                    <h2 id="profileName" class="profile-name" style="margin: 0 0 5px 0; color: #0f172a; font-size: 20px;">--</h2>
                    <p id="profileRole" style="margin: 0 0 15px 0; color: #64748b; font-size: 14px;">--</p>
                    <div style="background: #e0f2fe; color: #0369a1; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">ACTIVE STAFF</div>
                </div>

                <div class="card">
                    <h3 style="margin: 0 0 15px 0; color: #0f172a; font-size: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Preferences</h3>
                    <div class="form-group" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0;">
                        <label style="margin: 0;">Dark Mode</label>
                        <label class="switch">
                            <input type="checkbox" id="darkModeToggle">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="margin: 0 0 15px 0; color: #0f172a; font-size: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Account Details</h3>
                <form>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" id="inputName" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Staff ID</label>
                            <input type="text" id="inputId" class="form-control" readonly>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" id="inputEmail" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Nationality</label>
                            <select id="inputNationality" class="nationality-select" placeholder="Select Nationality..."></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <div style="display: flex; gap: 10px;">
                            <select id="inputCountryCode" class="country-select"></select>
                            <input type="tel" id="inputContact" class="form-control" placeholder="xxxxxxxxx">
                        </div>
                    </div>

                    <h3 style="margin: 20px 0 15px 0; color: #0f172a; font-size: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Security</h3>
                    
                    <div class="form-group">
                        <label>Current Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="currentPassword" class="form-control">
                            <span class="password-toggle" onclick="togglePassword('currentPassword', this)"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>New Password</label>
                            <div class="password-wrapper">
                                <input type="password" id="newPassword" class="form-control">
                                <span class="password-toggle" onclick="togglePassword('newPassword', this)"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <div class="password-wrapper">
                                <input type="password" id="confirmPassword" class="form-control">
                                <span class="password-toggle" onclick="togglePassword('confirmPassword', this)"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="saveBtn" class="btn-save">Save Changes</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        <?php require_once 'session_check.php'; ?>

        // Dark Mode Init
        if (localStorage.getItem('nbs_dark_mode') === 'true') {
            document.body.classList.add('dark-mode');
            document.getElementById('darkModeToggle').checked = true;
        }

        // Session Management
        let currentUser = <?php echo json_encode($_SESSION['nbs_current_user']); ?>;

        // Populate Data
        const roleMap = { 'DOC': 'Doctor', 'ADM': 'Administrator', 'NUR': 'Nurse', 'WMG': 'Ward Manager', 'REC': 'Records Officer' };
        const fullRole = roleMap[currentUser.role] || currentUser.role;
        const initials = currentUser.fullName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

        // Sidebar
        document.getElementById('userName').textContent = currentUser.fullName;
        document.getElementById('userRole').textContent = fullRole;
        document.getElementById('userAvatar').textContent = initials;

        // Load Profile Pic if exists
        if (currentUser.profilePic) {
            // Sidebar
            const sbAvatar = document.getElementById('userAvatar');
            sbAvatar.textContent = '';
            sbAvatar.style.backgroundImage = `url(${currentUser.profilePic})`;
            sbAvatar.style.backgroundSize = 'cover';
            
            // Main Profile
            document.getElementById('profileImagePreview').src = currentUser.profilePic;
            document.getElementById('profileImagePreview').style.display = 'block';
            document.getElementById('profileAvatar').style.display = 'none';
        }

        // Main Content
        document.getElementById('profileName').textContent = currentUser.fullName;
        document.getElementById('profileRole').textContent = fullRole;
        document.getElementById('profileAvatar').textContent = initials;

        document.getElementById('inputName').value = currentUser.fullName;
        document.getElementById('inputId').value = currentUser.staffId || 'N/A';
        document.getElementById('inputEmail').value = currentUser.email || 'N/A';
        // Nationality set after TomSelect init
        
        // Parse Contact
        if (currentUser.contact) {
            const parts = currentUser.contact.split(' ');
            if (parts.length > 1) {
                document.getElementById('inputCountryCode').value = parts[0];
                document.getElementById('inputContact').value = parts[1];
            } else {
                document.getElementById('inputContact').value = currentUser.contact;
            }
        }

        document.getElementById('logoutBtn').addEventListener('click', () => {
            fetch('api.php?action=logout')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    }
                });
        });

        // Notification Logic
        function showNotification(message, type) {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            container.appendChild(notification);
            setTimeout(() => notification.classList.add('show'), 10);
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Toggle Password
        window.togglePassword = function(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;
            } else {
                input.type = "password";
                icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
            }
        };

        // Profile Picture Upload
        let newProfilePicBase64 = null;
        document.getElementById('avatarUpload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    showNotification('Image too large. Max 2MB.', 'error');
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(event) {
                    newProfilePicBase64 = event.target.result;
                    // Preview
                    const preview = document.getElementById('profileImagePreview');
                    const initials = document.getElementById('profileAvatar');
                    preview.src = newProfilePicBase64;
                    preview.style.display = 'block';
                    initials.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Dark Mode Toggle
        document.getElementById('darkModeToggle').addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('nbs_dark_mode', 'true');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('nbs_dark_mode', 'false');
            }
        });

        function getRequiredLength(code) {
            const lengths = { "+1": 10, "+7": 10, "+20": 10, "+27": 9, "+30": 10, "+31": 9, "+32": 9, "+33": 9, "+34": 9, "+39": 10, "+44": 10, "+49": 10, "+55": 11, "+61": 9, "+81": 10, "+86": 11, "+91": 10, "+233": 9, "+234": 10 };
            return lengths[code] || 0;
        }

        // Contact Number Input Restriction
        document.getElementById('inputContact').addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/\D/g, '');
            if (this.value.startsWith('0')) this.value = this.value.substring(1);
            const reqLen = getRequiredLength(document.getElementById('inputCountryCode').value);
            const maxLen = reqLen || 15;
            if (this.value.length > maxLen) this.value = this.value.slice(0, maxLen);
        });

        // Save Changes
        document.getElementById('saveBtn').addEventListener('click', () => {
            const btn = document.getElementById('saveBtn');
            btn.disabled = true;
            btn.textContent = 'Saving...';

            const email = document.getElementById('inputEmail').value;
            const nationality = document.getElementById('inputNationality').tomselect.getValue();
            const contactNum = document.getElementById('inputContact').value;
            const countryCode = document.getElementById('inputCountryCode').value;
            const currentPass = document.getElementById('currentPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confirmPass = document.getElementById('confirmPassword').value;

            // Validate Contact Number
            const reqLen = getRequiredLength(countryCode);
            if (contactNum && ((reqLen > 0 && contactNum.length !== reqLen) || (reqLen === 0 && (contactNum.length < 7 || contactNum.length > 15)))) {
                showNotification(`Contact number must be ${reqLen || '7-15'} digits for ${countryCode}.`, 'error');
                btn.disabled = false;
                btn.textContent = 'Save Changes';
                return;
            }
            
            const fullContact = contactNum ? `${countryCode} ${contactNum}` : '';

            // Password Change Logic
            if (newPass && newPass !== confirmPass) {
                showNotification('New passwords do not match.', 'error');
                btn.disabled = false;
                btn.textContent = 'Save Changes';
                return;
            }

            const payload = {
                action: 'update_profile',
                email: email,
                contact: fullContact,
                nationality: nationality,
                currentPass: currentPass,
                newPass: newPass
            };

            if (newProfilePicBase64) {
                payload.profilePic = newProfilePicBase64;
            }

            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Update the local currentUser object with the fresh data from the server
                    currentUser = data.user;
                    // Clear password fields
                    document.getElementById('currentPassword').value = '';
                    document.getElementById('newPassword').value = '';
                    document.getElementById('confirmPassword').value = '';
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(() => showNotification('An unexpected error occurred.', 'error'))
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Save Changes';
            });
        });

        // --- Tom Select Initialization for Country Codes ---
        const countryData = [
            {val: "+1", label: "United States, Canada (+1)", flag: "🇺🇸"},
            {val: "+7", label: "Russia, Kazakhstan (+7)", flag: "🇷🇺"},
            {val: "+20", label: "Egypt (+20)", flag: "🇪🇬"},
            {val: "+27", label: "South Africa (+27)", flag: "🇿🇦"},
            {val: "+30", label: "Greece (+30)", flag: "🇬🇷"},
            {val: "+31", label: "Netherlands (+31)", flag: "🇳🇱"},
            {val: "+32", label: "Belgium (+32)", flag: "🇧🇪"},
            {val: "+33", label: "France (+33)", flag: "🇫🇷"},
            {val: "+34", label: "Spain (+34)", flag: "🇪🇸"},
            {val: "+36", label: "Hungary (+36)", flag: "🇭🇺"},
            {val: "+39", label: "Italy (+39)", flag: "🇮🇹"},
            {val: "+40", label: "Romania (+40)", flag: "🇷🇴"},
            {val: "+41", label: "Switzerland (+41)", flag: "🇨🇭"},
            {val: "+43", label: "Austria (+43)", flag: "🇦🇹"},
            {val: "+44", label: "United Kingdom (+44)", flag: "🇬🇧"},
            {val: "+45", label: "Denmark (+45)", flag: "🇩🇰"},
            {val: "+46", label: "Sweden (+46)", flag: "🇸🇪"},
            {val: "+47", label: "Norway (+47)", flag: "🇳🇴"},
            {val: "+48", label: "Poland (+48)", flag: "🇵🇱"},
            {val: "+49", label: "Germany (+49)", flag: "🇩🇪"},
            {val: "+51", label: "Peru (+51)", flag: "🇵🇪"},
            {val: "+52", label: "Mexico (+52)", flag: "🇲🇽"},
            {val: "+53", label: "Cuba (+53)", flag: "🇨🇺"},
            {val: "+54", label: "Argentina (+54)", flag: "🇦🇷"},
            {val: "+55", label: "Brazil (+55)", flag: "🇧🇷"},
            {val: "+56", label: "Chile (+56)", flag: "🇨🇱"},
            {val: "+57", label: "Colombia (+57)", flag: "🇨🇴"},
            {val: "+58", label: "Venezuela (+58)", flag: "🇻🇪"},
            {val: "+60", label: "Malaysia (+60)", flag: "🇲🇾"},
            {val: "+61", label: "Australia (+61)", flag: "🇦🇺"},
            {val: "+62", label: "Indonesia (+62)", flag: "🇮🇩"},
            {val: "+63", label: "Philippines (+63)", flag: "🇵🇭"},
            {val: "+64", label: "New Zealand (+64)", flag: "🇳🇿"},
            {val: "+65", label: "Singapore (+65)", flag: "🇸🇬"},
            {val: "+66", label: "Thailand (+66)", flag: "🇹🇭"},
            {val: "+81", label: "Japan (+81)", flag: "🇯🇵"},
            {val: "+82", label: "South Korea (+82)", flag: "🇰🇷"},
            {val: "+84", label: "Vietnam (+84)", flag: "🇻🇳"},
            {val: "+86", label: "China (+86)", flag: "🇨🇳"},
            {val: "+90", label: "Turkey (+90)", flag: "🇹🇷"},
            {val: "+91", label: "India (+91)", flag: "🇮🇳"},
            {val: "+92", label: "Pakistan (+92)", flag: "🇵🇰"},
            {val: "+93", label: "Afghanistan (+93)", flag: "🇦🇫"},
            {val: "+94", label: "Sri Lanka (+94)", flag: "🇱🇰"},
            {val: "+95", label: "Myanmar (+95)", flag: "🇲🇲"},
            {val: "+98", label: "Iran (+98)", flag: "🇮🇷"},
            {val: "+211", label: "South Sudan (+211)", flag: "🇸🇸"},
            {val: "+212", label: "Morocco (+212)", flag: "🇲🇦"},
            {val: "+213", label: "Algeria (+213)", flag: "🇩🇿"},
            {val: "+216", label: "Tunisia (+216)", flag: "🇹🇳"},
            {val: "+218", label: "Libya (+218)", flag: "🇱🇾"},
            {val: "+220", label: "Gambia (+220)", flag: "🇬🇲"},
            {val: "+221", label: "Senegal (+221)", flag: "🇸🇳"},
            {val: "+222", label: "Mauritania (+222)", flag: "🇲🇷"},
            {val: "+223", label: "Mali (+223)", flag: "🇲🇱"},
            {val: "+224", label: "Guinea (+224)", flag: "🇬🇳"},
            {val: "+225", label: "Côte d’Ivoire (+225)", flag: "🇨🇮"},
            {val: "+226", label: "Burkina Faso (+226)", flag: "🇧🇫"},
            {val: "+227", label: "Niger (+227)", flag: "🇳🇪"},
            {val: "+228", label: "Togo (+228)", flag: "🇹🇬"},
            {val: "+229", label: "Benin (+229)", flag: "🇧🇯"},
            {val: "+230", label: "Mauritius (+230)", flag: "🇲🇺"},
            {val: "+231", label: "Liberia (+231)", flag: "🇱🇷"},
            {val: "+232", label: "Sierra Leone (+232)", flag: "🇸🇱"},
            {val: "+233", label: "Ghana (+233)", flag: "🇬🇭"},
            {val: "+234", label: "Nigeria (+234)", flag: "🇳🇬"},
            {val: "+235", label: "Chad (+235)", flag: "🇹🇩"},
            {val: "+236", label: "Central African Republic (+236)", flag: "🇨🇫"},
            {val: "+237", label: "Cameroon (+237)", flag: "🇨🇲"},
            {val: "+238", label: "Cape Verde (+238)", flag: "🇨🇻"},
            {val: "+239", label: "São Tomé and Príncipe (+239)", flag: "🇸🇹"},
            {val: "+240", label: "Equatorial Guinea (+240)", flag: "🇬🇶"},
            {val: "+241", label: "Gabon (+241)", flag: "🇬🇦"},
            {val: "+242", label: "Republic of Congo (+242)", flag: "🇨🇬"},
            {val: "+243", label: "Democratic Republic of Congo (+243)", flag: "🇨🇩"},
            {val: "+244", label: "Angola (+244)", flag: "🇦🇴"},
            {val: "+245", label: "Guinea-Bissau (+245)", flag: "🇬🇼"},
            {val: "+246", label: "British Indian Ocean Territory (+246)", flag: "🇮🇴"},
            {val: "+248", label: "Seychelles (+248)", flag: "🇸🇨"},
            {val: "+249", label: "Sudan (+249)", flag: "🇸🇩"},
            {val: "+250", label: "Rwanda (+250)", flag: "🇷🇼"},
            {val: "+251", label: "Ethiopia (+251)", flag: "🇪🇹"},
            {val: "+252", label: "Somalia (+252)", flag: "🇸🇴"},
            {val: "+253", label: "Djibouti (+253)", flag: "🇩🇯"},
            {val: "+254", label: "Kenya (+254)", flag: "🇰🇪"},
            {val: "+255", label: "Tanzania (+255)", flag: "🇹🇿"},
            {val: "+256", label: "Uganda (+256)", flag: "🇺🇬"},
            {val: "+257", label: "Burundi (+257)", flag: "🇧🇮"},
            {val: "+258", label: "Mozambique (+258)", flag: "🇲🇿"},
            {val: "+260", label: "Zambia (+260)", flag: "🇿🇲"},
            {val: "+261", label: "Madagascar (+261)", flag: "🇲🇬"},
            {val: "+262", label: "Réunion, Mayotte (+262)", flag: "🇷🇪"},
            {val: "+263", label: "Zimbabwe (+263)", flag: "🇿🇼"},
            {val: "+264", label: "Namibia (+264)", flag: "🇳🇦"},
            {val: "+265", label: "Malawi (+265)", flag: "🇲🇼"},
            {val: "+266", label: "Lesotho (+266)", flag: "🇱🇸"},
            {val: "+267", label: "Botswana (+267)", flag: "🇧🇼"},
            {val: "+268", label: "Eswatini (Swaziland) (+268)", flag: "🇸🇿"},
            {val: "+269", label: "Comoros (+269)", flag: "🇰🇲"},
            {val: "+290", label: "Saint Helena (+290)", flag: "🇸🇭"},
            {val: "+291", label: "Eritrea (+291)", flag: "🇪🇷"},
            {val: "+297", label: "Aruba (+297)", flag: "🇦🇼"},
            {val: "+298", label: "Faroe Islands (+298)", flag: "🇫🇴"},
            {val: "+299", label: "Greenland (+299)", flag: "🇬🇱"},
            {val: "+350", label: "Gibraltar (+350)", flag: "🇬🇮"},
            {val: "+351", label: "Portugal (+351)", flag: "🇵🇹"},
            {val: "+352", label: "Luxembourg (+352)", flag: "🇱🇺"},
            {val: "+353", label: "Ireland (+353)", flag: "🇮🇪"},
            {val: "+354", label: "Iceland (+354)", flag: "🇮🇸"},
            {val: "+355", label: "Albania (+355)", flag: "🇦🇱"},
            {val: "+356", label: "Malta (+356)", flag: "🇲🇹"},
            {val: "+357", label: "Cyprus (+357)", flag: "🇨🇾"},
            {val: "+358", label: "Finland (+358)", flag: "🇫🇮"},
            {val: "+359", label: "Bulgaria (+359)", flag: "🇧🇬"},
            {val: "+370", label: "Lithuania (+370)", flag: "🇱🇹"},
            {val: "+371", label: "Latvia (+371)", flag: "🇱🇻"},
            {val: "+372", label: "Estonia (+372)", flag: "🇪🇪"},
            {val: "+373", label: "Moldova (+373)", flag: "🇲🇩"},
            {val: "+374", label: "Armenia (+374)", flag: "🇦🇲"},
            {val: "+375", label: "Belarus (+375)", flag: "🇧🇾"},
            {val: "+376", label: "Andorra (+376)", flag: "🇦🇩"},
            {val: "+377", label: "Monaco (+377)", flag: "🇲🇨"},
            {val: "+378", label: "San Marino (+378)", flag: "🇸🇲"},
            {val: "+379", label: "Vatican City (+379)", flag: "🇻🇦"},
            {val: "+380", label: "Ukraine (+380)", flag: "🇺🇦"},
            {val: "+381", label: "Serbia (+381)", flag: "🇷🇸"},
            {val: "+382", label: "Montenegro (+382)", flag: "🇲🇪"},
            {val: "+383", label: "Kosovo (+383)", flag: "🇽🇰"},
            {val: "+385", label: "Croatia (+385)", flag: "🇭🇷"},
            {val: "+386", label: "Slovenia (+386)", flag: "🇸🇮"},
            {val: "+387", label: "Bosnia and Herzegovina (+387)", flag: "🇧🇦"},
            {val: "+389", label: "North Macedonia (+389)", flag: "🇲🇰"},
            {val: "+420", label: "Czech Republic (+420)", flag: "🇨🇿"},
            {val: "+421", label: "Slovakia (+421)", flag: "🇸🇰"},
            {val: "+423", label: "Liechtenstein (+423)", flag: "🇱🇮"},
            {val: "+500", label: "Falkland Islands (+500)", flag: "🇫🇰"},
            {val: "+501", label: "Belize (+501)", flag: "🇧🇿"},
            {val: "+502", label: "Guatemala (+502)", flag: "🇬🇹"},
            {val: "+503", label: "El Salvador (+503)", flag: "🇸🇻"},
            {val: "+504", label: "Honduras (+504)", flag: "🇭🇳"},
            {val: "+505", label: "Nicaragua (+505)", flag: "🇳🇮"},
            {val: "+506", label: "Costa Rica (+506)", flag: "🇨🇷"},
            {val: "+507", label: "Panama (+507)", flag: "🇵🇦"},
            {val: "+508", label: "Saint Pierre and Miquelon (+508)", flag: "🇵🇲"},
            {val: "+509", label: "Haiti (+509)", flag: "🇭🇹"},
            {val: "+590", label: "Guadeloupe, Saint Martin (+590)", flag: "🇬🇵"},
            {val: "+591", label: "Bolivia (+591)", flag: "🇧🇴"},
            {val: "+592", label: "Guyana (+592)", flag: "🇬🇾"},
            {val: "+593", label: "Ecuador (+593)", flag: "🇪🇨"},
            {val: "+594", label: "French Guiana (+594)", flag: "🇬🇫"},
            {val: "+595", label: "Paraguay (+595)", flag: "🇵🇾"},
            {val: "+596", label: "Martinique (+596)", flag: "🇲🇶"},
            {val: "+597", label: "Suriname (+597)", flag: "🇸🇷"},
            {val: "+598", label: "Uruguay (+598)", flag: "🇺🇾"},
            {val: "+599", label: "Curaçao, Sint Maarten (+599)", flag: "🇨🇼"},
            {val: "+670", label: "Timor-Leste (+670)", flag: "🇹🇱"},
            {val: "+672", label: "Australian External Territories (+672)", flag: "🇦🇶"},
            {val: "+673", label: "Brunei (+673)", flag: "🇧🇳"},
            {val: "+674", label: "Nauru (+674)", flag: "🇳🇷"},
            {val: "+675", label: "Papua New Guinea (+675)", flag: "🇵🇬"},
            {val: "+676", label: "Tonga (+676)", flag: "🇹🇴"},
            {val: "+677", label: "Solomon Islands (+677)", flag: "🇸🇧"},
            {val: "+678", label: "Vanuatu (+678)", flag: "🇻🇺"},
            {val: "+679", label: "Fiji (+679)", flag: "🇫🇯"},
            {val: "+680", label: "Palau (+680)", flag: "🇵🇼"},
            {val: "+681", label: "Wallis and Futuna (+681)", flag: "🇼🇫"},
            {val: "+682", label: "Cook Islands (+682)", flag: "🇨🇰"},
            {val: "+683", label: "Niue (+683)", flag: "🇳🇺"},
            {val: "+685", label: "Samoa (+685)", flag: "🇼🇸"},
            {val: "+686", label: "Kiribati (+686)", flag: "🇰🇮"},
            {val: "+687", label: "New Caledonia (+687)", flag: "🇳🇨"},
            {val: "+688", label: "Tuvalu (+688)", flag: "🇹🇻"},
            {val: "+689", label: "French Polynesia (+689)", flag: "🇵🇫"},
            {val: "+690", label: "Tokelau (+690)", flag: "🇹🇰"},
            {val: "+691", label: "Micronesia (+691)", flag: "🇫🇲"},
            {val: "+692", label: "Marshall Islands (+692)", flag: "🇲🇭"},
            {val: "+800", label: "International Freephone (+800)", flag: "📞"},
            {val: "+808", label: "Shared Cost Service (+808)", flag: "📞"},
            {val: "+850", label: "North Korea (+850)", flag: "🇰🇵"},
            {val: "+852", label: "Hong Kong (+852)", flag: "🇭🇰"},
            {val: "+853", label: "Macau (+853)", flag: "🇲🇴"},
            {val: "+855", label: "Cambodia (+855)", flag: "🇰🇭"},
            {val: "+856", label: "Laos (+856)", flag: "🇱🇦"},
            {val: "+870", label: "Inmarsat SNAC (+870)", flag: "🛰️"},
            {val: "+878", label: "Universal Personal Telecommunication (+878)", flag: "📞"},
            {val: "+880", label: "Bangladesh (+880)", flag: "🇧🇩"},
            {val: "+881", label: "Global Mobile Satellite System (+881)", flag: "🛰️"},
            {val: "+882", label: "International Networks (+882)", flag: "📞"},
            {val: "+883", label: "International Networks (+883)", flag: "📞"},
            {val: "+886", label: "Taiwan (+886)", flag: "🇹🇼"},
            {val: "+960", label: "Maldives (+960)", flag: "🇲🇻"},
            {val: "+961", label: "Lebanon (+961)", flag: "🇱🇧"},
            {val: "+962", label: "Jordan (+962)", flag: "🇯🇴"},
            {val: "+963", label: "Syria (+963)", flag: "🇸🇾"},
            {val: "+964", label: "Iraq (+964)", flag: "🇮🇶"},
            {val: "+965", label: "Kuwait (+965)", flag: "🇰🇼"},
            {val: "+966", label: "Saudi Arabia (+966)", flag: "🇸🇦"},
            {val: "+967", label: "Yemen (+967)", flag: "🇾🇪"},
            {val: "+968", label: "Oman (+968)", flag: "🇴🇲"},
            {val: "+970", label: "Palestine (+970)", flag: "🇵🇸"},
            {val: "+971", label: "United Arab Emirates (+971)", flag: "🇦🇪"},
            {val: "+972", label: "Israel (+972)", flag: "🇮🇱"},
            {val: "+973", label: "Bahrain (+973)", flag: "🇧🇭"},
            {val: "+974", label: "Qatar (+974)", flag: "🇶🇦"},
            {val: "+975", label: "Bhutan (+975)", flag: "🇧🇹"},
            {val: "+976", label: "Mongolia (+976)", flag: "🇲🇳"},
            {val: "+977", label: "Nepal (+977)", flag: "🇳🇵"},
            {val: "+992", label: "Tajikistan (+992)", flag: "🇹🇯"},
            {val: "+993", label: "Turkmenistan (+993)", flag: "🇹🇲"},
            {val: "+994", label: "Azerbaijan (+994)", flag: "🇦🇿"},
            {val: "+995", label: "Georgia (+995)", flag: "🇬🇪"},
            {val: "+996", label: "Kyrgyzstan (+996)", flag: "🇰🇬"},
            {val: "+998", label: "Uzbekistan (+998)", flag: "🇺🇿"}
        ];

        new TomSelect("#inputCountryCode", {
            options: countryData,
            valueField: 'val',
            labelField: 'label',
            searchField: 'label',
            maxOptions: null,
            items: ['+233'], // Default to Ghana
            render: {
                option: function(data, escape) {
                    return '<div><span style="margin-right: 10px;">' + data.flag + '</span>' + escape(data.label) + '</div>';
                },
                item: function(data, escape) {
                    return '<div>' + (data.flag ? '<span style="margin-right: 5px;">' + data.flag + '</span>' : '') + escape(data.val) + '</div>';
                }
            }
        });

        // --- Tom Select for Nationality ---
        const nationalities = [
            "Afghan", "Albanian", "Algerian", "American", "Andorran", "Angolan", "Antiguans", "Argentinean", "Armenian", "Australian", "Austrian", "Azerbaijani", "Bahamian", "Bahraini", "Bangladeshi", "Barbadian", "Barbudans", "Batswana", "Belarusian", "Belgian", "Belizean", "Beninese", "Bhutanese", "Bolivian", "Bosnian", "Brazilian", "British", "Bruneian", "Bulgarian", "Burkinabe", "Burmese", "Burundian", "Cambodian", "Cameroonian", "Canadian", "Cape Verdean", "Central African", "Chadian", "Chilean", "Chinese", "Colombian", "Comoran", "Congolese", "Costa Rican", "Croatian", "Cuban", "Cypriot", "Czech", "Danish", "Djibouti", "Dominican", "Dutch", "East Timorese", "Ecuadorean", "Egyptian", "Emirian", "Equatorial Guinean", "Eritrean", "Estonian", "Ethiopian", "Fijian", "Filipino", "Finnish", "French", "Gabonese", "Gambian", "Georgian", "German", "Ghanaian", "Greek", "Grenadian", "Guatemalan", "Guinea-Bissauan", "Guinean", "Guyanese", "Haitian", "Herzegovinian", "Honduran", "Hungarian", "Icelander", "Indian", "Indonesian", "Iranian", "Iraqi", "Irish", "Israeli", "Italian", "Ivorian", "Jamaican", "Japanese", "Jordanian", "Kazakhstani", "Kenyan", "Kittian and Nevisian", "Kuwaiti", "Kyrgyz", "Laotian", "Latvian", "Lebanese", "Liberian", "Libyan", "Liechtensteiner", "Lithuanian", "Luxembourger", "Macedonian", "Malagasy", "Malawian", "Malaysian", "Maldivan", "Malian", "Maltese", "Marshallese", "Mauritanian", "Mauritian", "Mexican", "Micronesian", "Moldovan", "Monacan", "Mongolian", "Moroccan", "Mosotho", "Motswana", "Mozambican", "Namibian", "Nauruan", "Nepalese", "New Zealander", "Ni-Vanuatu", "Nicaraguan", "Nigerian", "Nigerien", "North Korean", "Northern Irish", "Norwegian", "Omani", "Pakistani", "Palauan", "Palestinian", "Panamanian", "Papua New Guinean", "Paraguayan", "Peruvian", "Polish", "Portuguese", "Qatari", "Romanian", "Russian", "Rwandan", "Saint Lucian", "Salvadoran", "Samoan", "San Marinese", "Sao Tomean", "Saudi", "Scottish", "Senegalese", "Serbian", "Seychellois", "Sierra Leonean", "Singaporean", "Slovakian", "Slovenian", "Solomon Islander", "Somali", "South African", "South Korean", "Spanish", "Sri Lankan", "Sudanese", "Surinamer", "Swazi", "Swedish", "Swiss", "Syrian", "Taiwanese", "Tajik", "Tanzanian", "Thai", "Togolese", "Tongan", "Trinidadian or Tobagonian", "Tunisian", "Turkish", "Tuvaluan", "Ugandan", "Ukrainian", "Uruguayan", "Uzbekistani", "Venezuelan", "Vietnamese", "Welsh", "Yemenite", "Zambian", "Zimbabwean"
        ];

        new TomSelect("#inputNationality", {
            options: nationalities.map(n => ({val: n, label: n})),
            valueField: 'val',
            labelField: 'label',
            searchField: 'label',
            create: true,
            maxOptions: null,
            items: [currentUser.nationality || '']
        });

        // Auto-detect User Location
        fetch('https://ipapi.co/json/')
            .then(response => response.json())
            .then(data => {
                if (data.country_calling_code) {
                    const code = data.country_calling_code;
                    const el = document.getElementById('inputCountryCode');
                    // Only update if the current value is the default (+233) to avoid overwriting saved data
                    if (el && el.tomselect && el.tomselect.options[code]) {
                        if (el.tomselect.getValue() === '+233') {
                            el.tomselect.setValue(code);
                        }
                    }
                }
            })
            .catch(error => console.log('Location detection failed:', error));
    </script>
</body>
</html>