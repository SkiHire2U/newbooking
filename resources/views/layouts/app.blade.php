<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Fonts -->
    {!! Html::style('/fonts/catamaran/stylesheet.css') !!}

    {!! Html::style('font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('/css/parsley.css') !!}
    {!! Html::style('/css/style.css') !!}

    @yield('styles')

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <header>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <div class="brand-wrapper clearfix">
                        <a class="navbar-brand" href="{{ url('http://skihire2u.com/') }}">
                            <img class="logo" src="/images/logo.png">
                        </a>
                    </div>
                </div>

                

                <div class="collapse navbar-collapse main-menu" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="http://skihire2u.com/#home">
                                <span class="item_outer">
                                    <span class="item_inner">
                                        Home
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="http://skihire2u.com/about-us/">
                                <span class="item_outer">
                                    <span class="item_inner">
                                        About Us
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="http://skihire2u.com/price/">
                                <span class="item_outer">
                                    <span class="item_inner">
                                        Pricing
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="http://skihire2u.com/equipment/">
                                <span class="item_outer">
                                    <span class="item_inner">
                                        Equipment
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="has-dropdown dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="item_outer">
                                    <span class="item_inner">
                                        Guestbook<i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </span>
                                </span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="http://skihire2u.com/guestbook/">
                                        <span class="item_outer">
                                            <span class="item_inner">
                                                Guestbook
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="http://skihire2u.com/blog/">
                                        <span class="item_outer">
                                            <span class="item_inner">
                                                Blog
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="http://newbooking.skihire2u.com/">
                                <span class="item_outer">
                                    <span class="item_inner">
                                        Book Now
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="http://skihire2u.com/contact-us/">
                                <span class="item_outer">
                                    <span class="item_inner">
                                        Contact Us
                                    </span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    @yield('title-bar')

    @include('partials._messages')

    @yield('content')

    <footer>
        <div class="container">
            <div class="copyright">
                <p class="white-text">Web Application Developed by <a href="https://www.web-design-malta.com" target="_blank" title="Web Design Malta" style="text-decoration:none; ">Web Design in Malta / <img src="https://www.web-design-malta.com/images/webee-white.png" border="0" alt="Web Design Malta" title="Web Design Malta" style="border-width: 0px; border-style: none; background:none;"></a></p>
            </div>
        </div>
        <!--
        <div class="upper-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="footer-column">
                            <a href="http://skihire2u.com/">
                                <img src="http://skihire2u.com/wp-content/uploads/2016/09/SkiHire2U-logo-white-grey-with-strap-line-400w.png" alt="logo">
                            </a>
                            <div class="social-icons">
                                <ul>
                                    <li>
                                        <a href="#" target="_blank">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" target="_blank">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" target="_blank">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" target="_blank">
                                            <i class="fa fa-linkedin"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" target="_blank">
                                            <i class="fa fa-dribble"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="footer-column">
                            <h4 class="qodef-footer-widget-title">Our Location</h4>
                            <p>Chalet Marmerlarde<br>2451 Route de la Dranse<br>74390 Châtel<br>France</p>
                        </div>
                        <a class="" href="javascript:void(0)">
                            <i class="ion-link"></i>Helpful Links
                        </a>
                    </div>
                </div>
            </div>
        </div>
        -->
    </footer>

    <!-- Scripts -->
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/parsley.min.js"></script>
    <script type="text/javascript" src="/js/scripts.js"></script>

    @yield('scripts')

</body>
</html>
