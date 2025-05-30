<!DOCTYPE html>
<html lang="id" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Dumpling</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #4e73df;
            --secondary: #858796;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
            --dumpling: #FF6B6B;
            --sauce: #FFD166;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            background: white;
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--dumpling);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand img {
            height: 30px;
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(78, 115, 223, 0.1);
            color: var(--primary);
        }

        .main-content {
            flex: 1;
            padding: 2rem 0;
        }

        .card-custom {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(58, 59, 69, 0.2);
        }

        .card-header {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-bottom: none;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 0.5rem 1.5rem;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
            transform: translateY(-1px);
        }

        .form-control,
        .form-select {
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            border: 1px solid #d1d3e2;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1000;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar-header {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid #e3e6f0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: var(--primary);
            background-color: rgba(78, 115, 223, 0.1);
        }

        .sidebar-link i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1rem;
            border-top: 1px solid #e3e6f0;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .dumpling-bg {
            background-color: var(--dumpling);
        }

        .sauce-bg {
            background-color: var(--sauce);
        }

        .floating-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
            z-index: 100;
            transition: all 0.3s;
        }

        .floating-btn:hover {
            transform: scale(1.1);
        }

        .badge-dumpling {
            background-color: var(--dumpling);
            color: white;
        }

        .badge-sauce {
            background-color: var(--sauce);
            color: #333;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .dumpling-card {
            border-left: 4px solid var(--dumpling);
        }

        .sauce-card {
            border-left: 4px solid var(--sauce);
        }

        .order-card {
            transition: all 0.2s;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80%;
            }

            .floating-btn {
                bottom: 1.5rem;
                right: 1.5rem;
                width: 50px;
                height: 50px;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="d-flex flex-column h-100">
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5 class="mb-0 text-primary fw-bold">🥟 Dumpling Kasir</h5>
            <small class="text-muted">Point of Sale System</small>
        </div>
        <div class="py-3">
            <a href="{{ route('kasir.index') }}" class="sidebar-link {{ request()->is('kasir') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i> Transaksi
            </a>
            <a href="{{ route('costs.index') }}" class="sidebar-link {{ request()->is('costs*') ? 'active' : '' }}">
                <i class="bi bi-cart-dash"></i> Pengeluaran
            </a>
            <a href="{{ route('riwayat.index') }}" class="sidebar-link {{ request()->is('riwayat') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Riwayat
            </a>
            <a href="{{ route('laporan.index') }}" class="sidebar-link {{ request()->is('laporan') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> Laporan
            </a>
        </div>
        @auth
        <div class="sidebar-footer">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <small class="text-muted">Kasir</small>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-link me-2" id="sidebarToggle">
                <i class="bi bi-list" style="font-size: 1.5rem;"></i>
            </button>
            <a class="navbar-brand" href="{{ route('kasir.index') }}">
                <i class="bi bi-shop"></i> Dumpling Kasir
            </a>
            <div class="d-flex align-items-center">
                @auth
                <div class="d-none d-lg-block me-3">
                    <span class="fw-semibold">Halo, {{ auth()->user()->name }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm rounded-pill">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Floating Action Button -->
    @auth
    <a href="{{ route('kasir.index') }}" class="floating-btn btn btn-primary d-lg-none">
        <i class="bi bi-plus-lg" style="font-size: 1.5rem;"></i>
    </a>
    @endauth

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggle = document.getElementById('sidebarToggle');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    </script>

    @stack('scripts')
</body>

</html>