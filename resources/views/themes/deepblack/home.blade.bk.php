<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>Golden Tiger Fund</title>

    <!-- Favicon -->
    <link rel="icon" href="{{asset('assets-v2/img/core-img/favicon.ico')}}">

    <!-- Core Stylesheet -->
    <link rel="stylesheet" href="{{asset('assets-v2/css/style.css')}}">

    <!-- Responsive Stylesheet -->
    <link rel="stylesheet" href="{{asset('assets-v2/css/responsive.css')}}">

    <script src="https://cdn.ethers.io/lib/ethers-5.2.umd.min.js"></script>

</head>

<body class="light-version">
<!-- Preloader -->
<div id="preloader">
    <div class="preload-content">
        <div id="dream-load"></div>
    </div>
</div>

<!-- ##### Header Area Start ##### -->
<header class="header-area fadeInDown" data-wow-delay="0.2s">
    <div class="classy-nav-container light breakpoint-off">
        <div class="container">
            <!-- Classy Menu -->
            <nav class="classy-navbar light justify-content-between" id="dreamNav">

                <!-- Logo -->
                <a class="nav-brand light" href="{{route('home')}}"><img src="{{asset('images/logo.jpg')}}" alt="logo" width="60px"></a>

                <!-- Button -->
{{--                @if (auth()->check())--}}
{{--                    <a href="javascript:;" class="btn login-btn ml-50 d-md-none d-lg-none" onclick="web3Login()">--}}
{{--                        <img src="{{asset('assets-v2/img/icons/metamask.png')}}" alt="" width="25px;" style="margin-right: 5px;"> Metamask Login--}}
{{--                    </a>--}}
{{--                @endif--}}

                <!-- Navbar Toggler -->
                <div class="classy-navbar-toggler demo">
                    <span class="navbarToggler"><span></span><span></span><span></span></span>
                </div>

                <!-- Menu -->
                <div class="classy-menu">

                    <!-- close btn -->
                    <div class="classycloseIcon">
                        <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                    </div>

                    <!-- Nav Start -->
                    <div class="classynav">
                        <ul id="nav">
                            <li><a href="{{route('home')}}">Home</a></li>
{{--                            <li><a href="{{route('home')}}#ico">Buy GTF</a></li>--}}
                            <li><a href="{{route('home')}}#about">About</a></li>
                            {{--                            <li><a href="#services">Features</a></li>--}}
                            {{--                            <li><a href="#faq">FAQ</a></li>--}}
                            {{--                            <li><a href="#roadmap">Roadmap</a></li>--}}
                            <li><a href="{{route('home')}}#contact">Contact</a></li>
                        </ul>

                        <!-- Button -->
                        @if (auth()->check())
                            <a href="{{route('user.home')}}" class="btn login-btn ml-50">
                                Dashboard
                            </a>
                        @else
                            <a href="{{route('login')}}" class="btn login-btn ml-50">
                                Login
                            </a>
{{--                            <a href="javascript:;" class="btn login-btn ml-50" onclick="web3Login()">--}}
{{--                                <img src="{{asset('assets-v2/img/icons/metamask.png')}}" alt="" width="25px;" style="margin-right: 5px;"> Metamask Login--}}
{{--                            </a>--}}
                        @endif

                    </div>
                    <!-- Nav End -->
                </div>
            </nav>
        </div>
    </div>
</header>
<!-- ##### Header Area End ##### -->
<!-- ##### Welcome Area Start ##### -->
<section class="welcome_area clearfix dzsparallaxer auto-init none fullwidth" data-options='{direction: "normal"}' id="home">
    <div class="divimage dzsparallaxer--target" style="width: 101%; height: 130%; background-image: url({{asset('assets-v2/img/bg-img/bg-5.png')}})"></div>

    <!-- Hero Content -->
    <div class="hero-content transparent">
        <!-- blip -->
        <div class="dream-blip blip1"></div>
        <div class="dream-blip blip2"></div>
        <div class="dream-blip blip3"></div>
        <div class="dream-blip blip4"></div>

        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <!-- Welcome Content -->
                <div class="col-12 col-lg-6 col-md-12">
                    <div class="welcome-content">
                        <div class="promo-section">
                            <h3 class="special-head dark">GTF INVESTMENTS PLAN FOR WORLDWIDE</h3>
                        </div>
                        <h1 class="fadeInUp" data-wow-delay="0.2s">AN INNOVATIVE CAPITAL RAISING SOLUTIONS COMPANY </h1>
                        <p class="w-text fadeInUp" data-wow-delay="0.3s">Golden Tiger Fund Investments Plan For Worldwide An Innovate Capital Raising Solutions Company</p>
                        <div class="dream-btn-group fadeInUp" data-wow-delay="0.4s">
                            <a href="#" class="btn dream-btn mr-3">contact us</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- ##### Welcome Area End ##### -->

<div class="clearfix"></div>

<!-- ##### About Us Area Start ##### -->
<section class="about-us-area  section-padding-0-100 clearfix " id="about">
    <div class="container-fluid">
        <div class="row align-items-center">

            <div class="col-12 col-lg-5 offset-lg-1 col-sm-10 offset-sm-1">
                <div class="who-we-contant">
                    <div class="dream-dots text-left fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                        <span>Welcome To GTF</span>
                    </div>
                    <h4 class="fadeInUp" data-wow-delay="0.3s">Golden Tiger Fund is a SA based holding company with a diverse market portfolio.</h4>
                    <p class="fadeInUp" data-wow-delay="0.5s">Our subsidiaries have established footprints in corporate finance services, property development and real estate. As the major shareholder we maintain an oversight capacity over our companies. We are there to support and guide them towards achieving the overall vision, values and strategy of the group.</p>
                    <div class="list-wrap align-items-center">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="side-feature-list-item">
                                    <img src="{{asset('assets-v2/img/features/feature-1.svg')}}" class="check-mark-icon" alt="">
                                    <div class="foot-c-info">Fully Responsive</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="side-feature-list-item">
                                    <img src="{{asset('assets-v2/img/features/feature-2.svg')}}" class="check-mark-icon" alt="">
                                    <div class="foot-c-info">Clean &amp; Modern Design</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="side-feature-list-item">
                                    <img src="{{asset('assets-v2/img/features/feature-3.svg')}}" class="check-mark-icon" alt="">
                                    <div class="foot-c-info">Multi-Device Testing </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="side-feature-list-item">
                                    <img src="{{asset('assets-v2/img/features/feature-4.svg')}}" class="check-mark-icon" alt="">
                                    <div class="foot-c-info">great User Experience</div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <a class="btn dream-btn mt-30 fadeInUp" data-wow-delay="0.6s" href="#">Read More</a>
                </div>
            </div>
            <div class="col-12 col-lg-6 mt-s no-padding-right">
                <div class="welcome-meter fadeInUp" data-wow-delay="0.7s">
                    <img class="img-responsive center-block" alt="" src="{{asset('assets-v2/img/core-img/about-4.png')}}">
                </div>
            </div>

        </div>
    </div>
</section>
<!-- ##### About Us Area End ##### -->

<!-- ##### Token Info Start ##### -->
{{--<div class=" section-padding-100" id="ico">--}}
{{--    <div class="container">--}}

{{--        <div class="row align-items-center">--}}

{{--            <div class="col-12 col-lg-5 offset-lg-0 col-md-8 offset-md-2 col-sm-10 offset-sm-1">--}}
{{--                <div class="ico-counter">--}}
{{--                    <div class="counter-down">--}}

{{--                        <div class="content">--}}
{{--                            <div class="conuter-header">--}}
{{--                                <h3 class="w-text text-center">GTF SALE ENDS IN</h3>--}}
{{--                            </div>--}}
{{--                            <div class="counterdown-content">--}}
{{--                                <!-- Countdown  -->--}}
{{--                                <div class="count-down titled circled text-center">--}}
{{--                                    <div class="simple_timer"></div>--}}
{{--                                </div>--}}
{{--                                <div class="ico-progress">--}}
{{--                                    <ul class="list-unstyled list-inline clearfix mb-10">--}}
{{--                                        <li class="title">33m</li>--}}
{{--                                        <li class="strength">75m</li>--}}
{{--                                    </ul>--}}
{{--                                    <!-- skill strength -->--}}
{{--                                    <div class="current-progress">--}}
{{--                                        <div class="progress-bar has-gradient" style="width: 75%"></div>--}}
{{--                                    </div>--}}
{{--                                    <span class="pull-left">Softcap in 6 days</span>--}}
{{--                                    <span class="pull-right">Token Hardcap</span>--}}
{{--                                </div>--}}
{{--                                <div class="text-center">--}}
{{--                                    <a class="btn dream-btn mt-30 fadeInUp" data-wow-delay="0.6s" href="{{route('user.addFund.confirm')}}">Buy More tokens</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-12 col-lg-7 col-sm-12">--}}
{{--                <div class="who-we-contant mt-s">--}}
{{--                    <div class="dream-dots text-left fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">--}}
{{--                        <span>Welcome To GTF</span>--}}
{{--                    </div>--}}
{{--                    <h4 class="fadeInUp" data-wow-delay="0.3s">Golden Tiger Fund is a SA based holding company with a diverse market portfolio.</h4>--}}
{{--                    <p class="fadeInUp" data-wow-delay="0.5s">Our subsidiaries have established footprints in corporate finance services, property development and real estate. As the major shareholder we maintain an oversight capacity over our companies. We are there to support and guide them towards achieving the overall vision, values and strategy of the group. Our overall strategy is to build a reputable, profitable and sustainable brand, who is a responsible corporate citizen that subscribes to the highest ethical standards of good governance and that delivers excellent customer service to all our stakeholders. We develop business models and our approach is risk based. We understand that our clients need to address current challenges or market opportunities that present itself, but also plan for future opportunities, threats and weaknesses.</p>--}}
{{--                    <div class="lock">--}}
{{--                        <img src="{{asset('assets-v2/img/icons/lock.png')}}" width="46" height="66" alt="">--}}
{{--                        <span class="ball ball_blue"></span>--}}
{{--                        <span class="ball ball_active"></span>--}}
{{--                        <span class="ball ball_active"></span>--}}
{{--                        <span class="ball ball_active"></span>--}}
{{--                        <img src="{{asset('assets-v2/img/icons/vpn.png')}}"  alt="">--}}
{{--                        <span class="ball ball_active"></span>--}}
{{--                        <span class="ball ball_active"></span>--}}
{{--                        <span class="ball ball_blue"></span>--}}
{{--                        <span class="ball ball_blue"></span>--}}
{{--                        <img src="{{asset('assets-v2/img/icons/lock-opened.png')}}" width="49" height="67" alt="">--}}
{{--                    </div>--}}
{{--                    <div class="clearfix"></div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<!-- ##### Footer Area Start ##### -->
<footer class="footer-area bg-img" style="background-image: url({{asset('assets-v2/img/core-img/pattern.png')}});">

    <!-- ##### Contact Area Start ##### -->
    <div class="contact_us_area section-padding-100-0" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center">
                        <!-- Dream Dots -->
                        <div class="dream-dots justify-content-center fadeInUp" data-wow-delay="0.2s">
                            <img src="{{asset('assets-v2/img/svg/section-icon-5.svg')}}" alt="">
                        </div>
                        <h2 class="fadeInUp" data-wow-delay="0.3s">Contact With Us</h2>
                        <p class="fadeInUp" data-wow-delay="0.4s">HELP AGENCIES TO DEFINE THEIR NEW BUSINESS OBJECTIVES AND THEN CREATE PROFESSIONAL SOFTWARE.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="contact_form">
                        <form action="#" method="post" id="main_contact_form" novalidate>
                            <div class="row">
                                <div class="col-12">
                                    <div id="success_fail_info"></div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="group fadeInUp" data-wow-delay="0.2s">
                                        <input type="text" name="name" id="name" required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Name</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="group fadeInUp" data-wow-delay="0.3s">
                                        <input type="text" name="email" id="email" required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Email</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group fadeInUp" data-wow-delay="0.4s">
                                        <input type="text" name="subject" id="subject" required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Subject</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group fadeInUp" data-wow-delay="0.5s">
                                        <textarea name="message" id="message" required></textarea>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Message</label>
                                    </div>
                                </div>
                                <div class="col-12 text-center fadeInUp" data-wow-delay="0.6s">
                                    <button type="submit" class="btn dream-btn">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Contact Area End ##### -->

    <div class="footer-content-area ">
        <div class="container">
            <div class="row ">
                <div class="col-sm-12 text-center">
                    <p class="rights">All Rights Reseved</p>
                </div>
            </div>
        </div>

    </div>
</footer>
<!-- ##### Footer Area End ##### -->

<!-- ########## All JS ########## -->
<!-- jQuery js -->
<script src="{{asset('assets-v2/js/jquery.min.js')}}"></script>
<!-- Popper js -->
<script src="{{asset('assets-v2/js/popper.min.js')}}"></script>
<!-- Bootstrap js -->
<script src="{{asset('assets-v2/js/bootstrap.min.js')}}"></script>
<!-- All Plugins js -->
<script src="{{asset('assets-v2/js/plugins.js')}}"></script>
<!-- Parallax js -->
<script src="{{asset('assets-v2/js/dzsparallaxer.js')}}"></script>

<script src="{{asset('assets-v2/js/jquery.syotimer.min.js')}}"></script>

<!-- script js -->
<script src="{{asset('assets-v2/js/script.js')}}"></script>


<script>
    async function web3Login() {
        if (!window.ethereum) {
            alert('MetaMask not detected. Please install MetaMask first.');
            return;
        }

        const provider = new ethers.providers.Web3Provider(window.ethereum);

        let response = await fetch('/web3-login-message');
        const message = await response.text();

        await provider.send("eth_requestAccounts", []);
        const address = await provider.getSigner().getAddress();
        const signature = await provider.getSigner().signMessage(message);

        response = await fetch('/web3-login-verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'address': address,
                'signature': signature,
                '_token': '{{ csrf_token() }}'
            })
        });
        const data = await response.text();
        if (data) {
            window.location.href = '{{route('user.home')}}'
        }
    }
</script>

</body>

</html>
