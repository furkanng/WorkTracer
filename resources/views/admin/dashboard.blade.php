<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
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
                z-index: 1046;
            }
            .main-content {
                width: 100% !important;
                margin-left: 0 !important;
                padding-top: 60px;
            }
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1044;
            }
            .overlay.show {
                display: block;
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
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand d-flex align-items-center">
                    <i class="bi bi-gear-fill me-2"></i>
                    Admin Paneli
                </a>
                <hr class="text-white mx-3 my-1">
                <ul class="nav flex-column flex-grow-1">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" 
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.customers.index') }}" 
                            class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                            <i class="bi bi-people me-2"></i> Müşteriler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.tasks.index') }}" 
                            class="nav-link {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
                            <i class="bi bi-list-task me-2"></i> Görevler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.messages.index') }}" 
                            class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                            <i class="bi bi-chat-dots me-2"></i> Mesajlar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" 
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people me-2"></i> Teknisyenler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.secretaries.*') ? 'active' : '' }}" 
                            href="{{ route('admin.secretaries.index') }}">
                            <i class="bi bi-people"></i> Sekreterler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.prices.*') ? 'active' : '' }}" 
                            href="{{ route('admin.prices.index') }}">
                            <i class="bi bi-currency-dollar"></i> Fiyatlandırma
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" 
                            href="{{ route('admin.brands.index') }}">
                            <i class="bi bi-tag"></i> Markalar
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

                @if(request()->routeIs('admin.dashboard'))
                <!-- İstatistik Kartları -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Toplam Müşteri</h5>
                                <h2 class="card-text">{{ $totalCustomers ?? 0 }}</h2>
                                <p class="card-text mb-0">
                                    <small>Son 30 günde: {{ $newCustomers ?? 0 }} yeni</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Aktif Görevler</h5>
                                <h2 class="card-text">{{ $activeTasks ?? 0 }}</h2>
                                <p class="card-text mb-0">
                                    <small>Bekleyen: {{ $pendingTasks ?? 0 }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Toplam Teknisyen</h5>
                                <h2 class="card-text">{{ $totalTechnicians ?? 0 }}</h2>
                                <p class="card-text mb-0">
                                    <small>Aktif: {{ $activeTechnicians ?? 0 }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafikler -->
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Aylık Görev İstatistikleri</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="taskChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Görev Durumları</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="taskStatusChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Son Aktiviteler -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Son Görevler</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Görev ID</th>
                                                <th>Müşteri</th>
                                                <th>Durum</th>
                                                <th>Tarih</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentTasks ?? [] as $task)
                                            <tr>
                                                <td>#{{ $task->id }}</td>
                                                <td>{{ $task->customer_name }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $task->status_color }}">
                                                        {{ $task->status }}
                                                    </span>
                                                </td>
                                                <td>{{ $task->created_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Henüz görev bulunmuyor</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Son Mesajlar</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Gönderen</th>
                                                <th>Konu</th>
                                                <th>Tarih</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentMessages ?? [] as $message)
                                            <tr>
                                                <td>{{ $message->sender_name }}</td>
                                                <td>{{ Str::limit($message->subject, 30) }}</td>
                                                <td>{{ $message->created_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Henüz mesaj bulunmuyor</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('show');
        document.querySelector('.overlay').classList.toggle('show');
    }

    // Mobil görünümde menü öğesine tıklandığında sidebar'ı kapat
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                toggleSidebar();
            }
        });
    });

    // Görev İstatistikleri Grafiği
    const taskCtx = document.getElementById('taskChart').getContext('2d');
    new Chart(taskCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyTaskLabels ?? []) !!},
            datasets: [{
                label: 'Tamamlanan Görevler',
                data: {!! json_encode($monthlyTaskData ?? []) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Görev Durumları Grafiği
    const taskStatusCtx = document.getElementById('taskStatusChart').getContext('2d');
    new Chart(taskStatusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($taskStatusLabels ?? []) !!},
            datasets: [{
                data: {!! json_encode($taskStatusData ?? []) !!},
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    </script>
    @stack('scripts')
</body>
</html> 