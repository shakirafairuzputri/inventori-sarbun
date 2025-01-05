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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" type="text/css" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('lp/css/styles.css') }}" rel="stylesheet" />
        
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-light bg-light sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('beranda') }}">Sistem Inventori Rumah Makan Sari Bundo</a>
                <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
            </div>
        </nav>
        <!-- Masthead-->
        <header id="headerCarousel" class="carousel slide masthead" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="carousel-image" style="background-image: url('{{ asset('lp/assets/img/saribundo.jpg') }}');"></div>
                </div>
                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="carousel-image" style="background-image: url('{{ asset('lp/assets/img/saribundo2.jpg') }}');"></div>
                </div>
                <!-- Slide 3 -->
                <div class="carousel-item">
                    <div class="carousel-image" style="background-image: url('{{ asset('lp/assets/img/saribundo3.jpg') }}');"></div>
                </div>
            </div>
            <!-- Carousel controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#headerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#headerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>        
            <div class="position-relative text-center text-white">
                <h1 class="mb-5">Rumah Makan Sari Bundo</h1>
            </div>
        </header>        
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
        <!-- Footer-->
        <footer class="py-3 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; RM Sari Bundo 2024</div>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{ asset('lp/js/scripts.js') }}"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
        <!-- Bootstrap JS (harus setelah jQuery dan Popper.js) -->
        <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    </body>
</html>
