<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknisyen Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            transition: all 0.3s;
            width: 280px;
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 0.8rem 1rem;
            border-radius: 4px;
            margin: 0.2rem 0.5rem;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
        }
        .main-content {
            padding: 20px;
            transition: all 0.3s;
        }
        .sidebar-toggler {
            display: none;
            padding: 0.5rem;
            color: black;
            background: none;
            border: none;
        }
        .sidebar-brand {
            color: #fff;
            text-decoration: none;
            font-size: 1.25rem;
            padding: 1rem;
            display: block;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }
        .overlay.show {
            display: block;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -280px;
                width: 280px;
                z-index: 1045;
                height: 100vh;
                margin-left: 0 !important;
            }
            .sidebar.show {
                left: 0;
                margin-left: 0 !important;
            }
            .sidebar-toggler {
                display: block;
                position: fixed;
                top: 0.5rem;
                left: 1rem;
                z-index: 1050;
            }
            .main-content {
                width: 100% !important;
                margin-left: 0 !important;
                padding-top: 60px;
            }
        }
        @media (min-width: 992px) {
            .main-content {
                margin-left: 280px;
                width: calc(100% - 280px);
            }
            .sidebar {
                position: fixed;
                height: 100vh;
            }
        }
    </style>
</head>
<body>
    <button class="sidebar-toggler" type="button" onclick="toggleSidebar()">
        <i class="bi bi-list fs-4"></i>
    </button>

    <div class="overlay" onclick="toggleSidebar()"></div>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="d-flex flex-column h-100">
                <a href="{{ route('technician.dashboard') }}" class="sidebar-brand d-flex align-items-center">
                    <i class="bi bi-tools me-2"></i>
                    Teknisyen Paneli
                </a>
                <hr class="text-white mx-3 my-1">
                <ul class="nav flex-column flex-grow-1">
                    <li class="nav-item">
                        <a href="{{ route('technician.dashboard') }}" 
                            class="nav-link {{ request()->routeIs('technician.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('technician.tasks.index') }}" 
                            class="nav-link {{ request()->routeIs('technician.tasks.*') ? 'active' : '' }}">
                            <i class="bi bi-list-task me-2"></i> Görevlerim
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('technician.messages.index') }}" 
                            class="nav-link {{ request()->routeIs('technician.messages.*') ? 'active' : '' }}">
                            <i class="bi bi-chat-dots me-2"></i> Mesajlar
                        </a>
                    </li>
                </ul>

                <div class="mt-auto p-3">
                    <div class="text-white mb-2 small">
                        Giriş yapan: {{ Auth::user()->name }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-box-arrow-right me-2"></i> Çıkış Yap
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ana içerik -->
        <div class="main-content flex-grow-1">
            <div class="container-fluid px-lg-4">
                <div class="d-flex align-items-center mb-4">
                    <h4 class="mb-0">@yield('title', 'Dashboard')</h4>
                </div>

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
            document.querySelector('.overlay').classList.toggle('show');
        }

        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    toggleSidebar();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html> 