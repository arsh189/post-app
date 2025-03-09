<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Default Theme */
        :root {
            --primary-bg: #f8f9fa;
            --primary-text: #343a40;
            --sidebar-bg: #343a40;
            --sidebar-text: white;
            --header-bg: #f8f9fa;
        }
        
        /* Dark Mode */
        [data-theme="dark"] {
            --primary-bg: #181818;
            --primary-text: #f8f9fa;
            --sidebar-bg: #212529;
            --sidebar-text: #ffffff;
            --header-bg: #282c34;
        }

        /* Admin Theme (Blue) */
        [data-theme="admin"] {
            --primary-bg: #e3f2fd;
            --primary-text: #0d47a1;
            --sidebar-bg: #0d47a1;
            --sidebar-text: white;
            --header-bg: #bbdefb;
        }

        /* Super-Admin Theme (Red) */
        [data-theme="super-admin"] {
            --primary-bg: #ffebee;
            --primary-text: #b71c1c;
            --sidebar-bg: #b71c1c;
            --sidebar-text: white;
            --header-bg: #ffcdd2;
        }

        /* Default User Theme */
        [data-theme="user"] {
            --primary-bg: #f8f9fa;
            --primary-text: #343a40;
            --sidebar-bg: #343a40;
            --sidebar-text: white;
            --header-bg: #f8f9fa;
        }

        body {
            background: var(--primary-bg);
            color: var(--primary-text);
            display: flex;
        }
        
        .sidebar {
            width: 250px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            transition: width 0.3s ease-in-out;
        }
        
        .sidebar a {
            color: var(--sidebar-text);
            text-decoration: none;
            padding: 10px;
            display: block;
        }
        
        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .logo {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            padding: 15px 0;
            background: var(--header-bg);
            color: var(--primary-text);
        }
        
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        
        .header {
            background: var(--header-bg);
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
        }
        
        .toggle-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--primary-text);
        }

        /* Dark Mode Toggle Switch */
        .dark-mode-switch {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:checked + .slider:before {
            transform: translateX(24px);
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -250px;
            }
            .sidebar.open {
                left: 0;
            }
        }
    </style>
</head>
<body data-theme="{{ auth()->user()->roles[0]->name }}">

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">{{ Ucfirst(auth()->user()->roles[0]->name)}}
            <!-- <h2>{{ Ucfirst(auth()->user()->roles[0]->name)}}</h2> -->
        </div>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('profile') }}">Profile Picture</a>
        <a href="{{ route('posts.index') }}">Posts</a>
        <a href="{{ route('logout') }}">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="header">
            <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
            <!-- <div class="dark-mode-switch">
                <span>Dark Mode</span>
                <label class="switch">
                    <input type="checkbox" id="toggleDarkMode">
                    <span class="slider"></span>
                </label>
            </div> -->
        </div>

        <div class="container mt-3">
            @yield('content')
        </div>
    </div>
    @yield('scripts')
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        // Get User Role
        let userRole = "{{ auth()->user()->roles[0]->name }}";
        let body = document.body;
        let darkModeToggle = document.getElementById('toggleDarkMode');

        // Check Current Theme
        if (body.getAttribute('data-theme') === 'dark') {
            darkModeToggle.checked = true;
        }

        // Toggle Dark Mode
        darkModeToggle.addEventListener('change', function() {
            if (this.checked) {
                // Enable Dark Mode
                body.setAttribute('data-theme', 'dark');
            } else {
                // Revert to User's Role-Based Theme
                body.setAttribute('data-theme', userRole);
            }
        });
    </script>

</body>
</html>
