<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>@yield('title', 'Default Title')</title>

        <!-- CSS Libraries -->
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="{{ asset('sb/css/styles.css') }}" rel="stylesheet">
        <link href="{{ asset('lp/css/styles.css') }}" rel="stylesheet">
        
        <!-- Font Awesome -->
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
        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem 1rem; /* Padding navbar agar lebih rapat */
                display: flex;
                justify-content: space-between; /* Menyusun elemen navbar agar tersebar merata */
                align-items: center; /* Menjaga elemen tetap di tengah secara vertikal */
            }

            .navbar-toggler {
                border: none;
                font-size: 20px; /* Menyesuaikan ukuran tombol toggle */
            }

            /* Nama pengguna tetap sejajar dengan elemen navbar lainnya */
            .navbar-nav {
                display: flex;
                align-items: center; /* Menjaga agar nama pengguna tetap sejajar dengan tombol toggle */
                flex-wrap: nowrap; /* Menjaga agar nama pengguna tidak turun ke baris kedua */
            }

            .navbar-nav .nav-item {
                margin-left: 10px; /* Memberikan jarak antara item navbar */
            }

            .navbar-nav .nav-link {
                font-size: 14px; /* Menyesuaikan ukuran teks nama pengguna */
            }
            .login-text {
                display: none; /* Menyembunyikan teks login di layar kecil */
            }
            .login-btn {
                padding: 0.5rem; /* Menyisakan ruang hanya untuk ikon */
            }
        }
    </style>
    @php
        $dashboardRoute = null;

        if (auth()->check()) {
            // Define role-specific dashboard routes
            $dashboardRoutes = [
                'admin' => route('admin.dashboardo'),
                'supervisor' => route('supervisor.dashboard'),
                'pegawai' => route('pegawai.dashboardp'),
                // Add other roles as needed
            ];
            $dashboardRoute = $dashboardRoutes[auth()->user()->role] ?? null; // Default to null if role is not found
        }
    @endphp


    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand-lg custom-navbar" style="background-color: #16423C">
            <div class="container">
                <!-- Navbar Brand with Logo-->
                <a class="navbar-brand ps-3 d-flex align-items-center" href="{{ route('login') }}">
                    <img src="{{ asset('sb/assets/img/logo.png') }}" alt="Logo" style="height: 30px;">
                </a>
                @auth
                    <button id="navbarToggler" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>
                @endauth

                <!-- Navbar Content (Collapse) -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto d-flex align-items-center">
                        @auth
                            <!-- User Dropdown Menu -->
                            <li class="nav-item dropdown justify-content-end">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #16423C">
                                    <i class="fas fa-user me-2"></i><span class="user-name">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ $dashboardRoute }}">Dashboard</a></li>
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
                @guest
                    <li class="nav-item">
                        <button class="btn btn-primary btn-sm me-2 login-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fas fa-sign-in-alt"></i> <span class="login-text">Login</span>
                        </button>
                    </li>
                @endguest
            </div>
        </nav>
        
        

        <!-- Logout Modal -->
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
                        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const navbarToggler = document.getElementById('navbarToggler');
                const navbarNav = new bootstrap.Collapse(document.getElementById('navbarNav'), { toggle: false });
        
                navbarToggler.addEventListener('click', function () {
                    navbarNav.toggle();
                });
            });
        </script>
        
        
        <!-- JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('sb/js/scripts.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('sb/assets/demo/chart-area-demo.js') }}"></script>
        <script src="{{ asset('sb/assets/demo/chart-bar-demo.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('sb/js/datatables-simple-demo.js') }}"></script>
        <!-- JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    </body>
</html>
