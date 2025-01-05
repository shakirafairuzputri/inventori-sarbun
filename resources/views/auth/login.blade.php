<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Sistem Inventori Rumah Makan Sari Bundo</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" />
    <!-- Core theme CSS for Landing Page (includes Bootstrap)-->
    <link href="{{ asset('lp/css/styles.css') }}" rel="stylesheet" />
    <!-- Additional CSS for Login Modal -->
    <link href="{{ asset('sb/css/styles.css') }}" rel="stylesheet" />
</head>

<body>
    @if (session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Sukses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ session('success') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>
    @endif
    <!-- Navigation-->
    @include('layout.navbar')

    <!-- Masthead-->
    <header id="headerCarousel" class="carousel slide masthead" data-bs-ride="carousel">
        <!-- Carousel Slides -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="carousel-image" style="background-image: url('{{ asset('lp/assets/img/saribundo.jpg') }}');"></div>
            </div>
            <!-- Additional Slides -->
            <div class="carousel-item">
                <div class="carousel-image" style="background-image: url('{{ asset('lp/assets/img/saribundo2.jpg') }}');"></div>
            </div>
            <div class="carousel-item">
                <div class="carousel-image" style="background-image: url('{{ asset('lp/assets/img/saribundo3.jpg') }}');"></div>
            </div>
        </div>
        <h1 class="text-white text-center">Sistem Inventori Rumah Makan Sari Bundo</h1>
        
        
        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#headerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#headerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </header>
    

    <!-- Main Section -->
    <section class="showcase">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('{{ asset('lp/assets/img/bg-showcase-1.jpg') }}')"></div>
                <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                    <h2>Rumah Makan Sari Bundo</h2>
                    <p class="lead mb-0">Rumah Makan Sari Bundo merupakan suatu usaha dagang yang bergerak di bidang kuliner yang memperkenalkan kuliner khas Minangkabau Sumatera Barat. Rumah makan ini berdiri sejak tahun 1992 dan kini bertempat di Jalan Merak, Bandung serta memiliki satu cabang lainnya di Jalan Antapani, Bandung</p>
                </div>
            </div>
            <div class="row g-0">
                <div class="col-lg-6 text-white showcase-img" style="background-image: url('{{ asset('/lp/assets/img/bg-showcase-2.jpg') }}')"></div>
                <div class="col-lg-6 my-auto showcase-text">
                    <h2>Info</h2>
                    <p class="lead mb-0">Sistem Inventori Sari Bundo adalah sistem yang mengelola segala persediaan, baik persediaan bahan maupun barang di Rumah Makan Sari Bundo. Terdapat fungsi untuk mengelola data bahan, data barang, data retur bahan, data pembelian bahan, data produksi bahan, data barang masuk, dan data barang keluar</p>
                </div>
            </div>
            <div class="row g-0">
                <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('{{ asset('/lp/assets/img/bg-showcase-3.jpg') }}')"></div>
                <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                    <h2>Gunakan Sistem</h2>
                    <p class="lead mb-0">Lakukan login untuk menggunakan sistem (khusus pegawai)</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-3 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; RM Sari Bundo 2024</div>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center font-weight-light" id="loginModalLabel">Login</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('loginproses') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" class="form-control" id="email" required value="{{ old('email') }}">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-control" 
                                id="password" 
                                required
                            >
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-check mt-2">
                            <input 
                                type="checkbox" 
                                class="form-check-input" 
                                id="showPassword" 
                                onclick="togglePassword()"
                            >
                            <label class="form-check-label" for="showPassword">Show Password</label>
                        </div>
                          
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-block"><b>Login</b></button>
                        </div>
                    </form>
                    <!-- Error & Success Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lp/js/scripts.js') }}"></script>
    <script src="{{ asset('sb/js/scripts.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check if there are any error messages, and if so, show the login modal
            @if ($errors->any())
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            @endif
        });
    </script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
