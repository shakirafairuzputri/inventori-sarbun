<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>@yield('title', 'Default Title')</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="{{ asset('sb/css/styles.css') }}" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <style>
        .dot {
            height: 10px;
            width: 10px;
            background-color: red;
            border-radius: 50%;
            display: inline-block;
            margin-left: 5px;
        }
        /* Mengatur navbar untuk tampilan mobile */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem 1rem; /* Menyesuaikan padding navbar agar lebih rapat */
                display: flex;
                justify-content: space-between; /* Menyusun elemen navbar agar tersebar merata */
                align-items: center; /* Menjaga elemen tetap di tengah secara vertikal */
            }

            /* Mengatur tampilan tombol toggle */
            .navbar-toggler {
                border: none;
                font-size: 20px; /* Menyesuaikan ukuran tombol */
            }

            /* Agar nama pegawai tidak turun dan tetap di sebelah tombol toggle */
            .navbar-nav {
                display: flex;
                align-items: center; /* Menjaga agar nama pegawai tetap sejajar dengan tombol toggle */
            }

            .navbar-nav .nav-item {
                margin-left: 10px; /* Memberikan jarak antara item navbar */
            }

            .navbar-nav .nav-link {
                font-size: 14px; /* Menyesuaikan ukuran teks nama pegawai */
            }

        }
    </style>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand-lg custom-navbar">
        <!-- Navbar Brand with Logo on the Far Left -->
        <a class="navbar-brand ps-3 d-flex align-items-center ms--1" href="{{ route('login') }}">
            <img src="{{ asset('sb/assets/img/logo.png') }}" alt="Logo" style="height: 30px;">
        </a>

        <!-- Sidebar Toggle (center) -->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Navbar Content (Collapse) -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                    <!-- User Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-2"></i><span class="user-name">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('pegawai.dashboardp') }}">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>            
    </nav>        

    {{-- logout modal --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <!-- Confirm Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion custom-sidebar" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="sb-sidenav-menu-heading">
                            <span class="nav-link text-white" style="font-size: 14px;">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                        </div>
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link {{ request()->routeIs('pegawai.dashboardp') ? 'active' : '' }}" href="{{ route('pegawai.dashboardp') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Persediaan</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePersediaanBahan" aria-expanded="false" aria-controls="collapsePersediaanBahan">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Persediaan Bahan
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePersediaanBahan" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link {{ request()->routeIs('pegawai.persediaan-retur') ? 'active' : '' }}" href="{{ route('pegawai.persediaan-retur') }}">Retur Bahan</a>
                                    <a class="nav-link {{ request()->routeIs('pegawai.persediaan-beli') ? 'active' : '' }}" href="{{ route('pegawai.persediaan-beli') }}">Pembelian Bahan</a>
                                    <a class="nav-link {{ request()->routeIs('pegawai.persediaan-produksi') ? 'active' : '' }}" href="{{ route('pegawai.persediaan-produksi') }}">Produksi Bahan</a>
                                    <a class="nav-link" href="{{ route('pegawai.laporan-bhn') }}">Laporan Stok Bahan</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePersediaanBarang" aria-expanded="false" aria-controls="collapsePersediaanBarang">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Persediaan Barang
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePersediaanBarang" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link {{ request()->routeIs('pegawai.persediaan-brgm') ? 'active' : '' }}" href="{{ route('pegawai.persediaan-brgm') }}">Stok Barang Masuk</a>
                                    <a class="nav-link {{ request()->routeIs('pegawai.persediaan-brgk') ? 'active' : '' }}" href="{{ route('pegawai.persediaan-brgk') }}">Stok Barang Keluar</a>
                                    <a class="nav-link" href="{{ route('pegawai.laporan-brg') }}">Laporan Stok Barang</a>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Lapor</div>
                            <a class="nav-link {{ request()->routeIs('pegawai.laporan') ? 'active' : '' }}" href="{{ route('pegawai.laporan') }}" aria-expanded="false">
                                <div><i class="fas fa-exclamation" style="margin-right: 8px;"></i></div>
                                Laporan Kesalahan
                            </a> 
                            <div class="sb-sidenav-menu-heading">Request Input Data</div>
                            <a class="nav-link {{ request()->routeIs('pegawai.request-input') ? 'active' : '' }}" href="{{ route('pegawai.request-input') }}" aria-expanded="false">
                                <div><i class="fas fa-database" style="margin-right: 8px;"></i></div>
                                Request Input Data
                            </a> 
                            <div class="sb-sidenav-menu-heading">Action</div>
                            <a class="nav-link" href="#" aria-expanded="false" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <div><i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i></div>
                                Logout
                            </a>        
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    @yield('content')
                </main>
                <footer class="py-3 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; RM Sari Bundo 2024</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('sb/js/scripts.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('sb/assets/demo/chart-area-demo.js') }}"></script>
        <script src="{{ asset('sb/assets/demo/chart-bar-demo.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('sb/js/datatables-simple-demo.js') }}"></script>
        <!-- Select2 Initialization -->
        
        
    </body>
</html>
