<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Watu Coffee & Roastery</title>
    
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <link rel="icon" href="{{ asset('images/LOGO Produk.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/LOGO Produk.png') }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
</head>

<body class="main-layout">
    <header class="header" id="mainHeader">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('images/LOGO Produk.png') }}" alt="Logo">
                    <span>Watu Coffee & Roastery</span>
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarWatu" aria-controls="navbarWatu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarWatu">
                    <ul class="navbar-nav ml-auto text-center">
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link smooth-scroll" href="#about">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('order') }}">Order & Reservation</a></li>
                        
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" style="color: #d4a056 !important;" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    @yield('content')

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="{{ route('home') }}"><img src="{{ asset('images/LOGO Produk.png') }}" alt="" style="width: 100px; margin-bottom:20px;"></a>
                    <p>© 2025 All Rights Reserved. WATU COFFEE</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    
    <script>
        // Page Load Animation
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
        });
        
        // Header Scroll Effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Smooth Scroll for Anchor Links
        document.querySelectorAll('a.smooth-scroll').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Close mobile menu on link click
        $('.navbar-nav a').on('click', function(){
            if(window.innerWidth < 992) {
                $('.navbar-collapse').collapse('hide');
            }
        });
    </script>
</body>
</html>