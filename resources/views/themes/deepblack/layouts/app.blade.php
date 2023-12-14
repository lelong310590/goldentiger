<!DOCTYPE html>
<!--[if lt IE 7 ]>
<html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="no-js" lang="en"  @if(session()->get('rtl') == 1) dir="rtl" @endif >

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    @include('partials.seo')

    <link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'assets/bootstrap/bootstrap.min.css')}}" />
    <!-- Favicon -->
    <link rel="icon" href="{{asset('assets-v2/img/core-img/favicon.ico')}}">

    <!-- Core Stylesheet -->
    <link rel="stylesheet" href="{{asset('assets-v2/css/style.css')}}">

    <!-- Responsive Stylesheet -->
    <link rel="stylesheet" href="{{asset('assets-v2/css/responsive.css')}}">
    @stack('css-lib')
    <link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'assets/plugins/owlcarousel/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'assets/plugins/owlcarousel/owl.carousel.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'assets/plugins/owlcarousel/owl.theme.default.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'assets/plugins/aos/aos.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'assets/plugins/radial-progress/radialprogress.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'scss/flag-icon.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'scss/style.min.css')}}">

    <script src="{{asset($themeTrue.'js/modernizr.custom.js')}}"></script>

    @stack('style')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script type="application/javascript" src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script type="application/javascript" src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
    <div class="classy-nav-container light breakpoint-off dark left">
        <div class="container">
            <!-- Classy Menu -->
            <nav class="classy-navbar light justify-content-between" id="dreamNav">

                <!-- Logo -->
                <a class="nav-brand light" href="{{route('home')}}"><img src="{{asset('assets-v2/img/core-img/logo.png')}}" alt="logo"></a>

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
                            <li><a href="#ico">Buy GTF</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#services">Features</a></li>
                            <li><a href="#faq">FAQ</a></li>
                            <li><a href="#roadmap">Roadmap</a></li>
                            <li><a href="#contact">Contact</a></li>
                        </ul>

                        <!-- Button -->
                        <a href="#" class="btn login-btn ml-50">
                            <img src="{{asset('assets-v2/img/icons/metamask.png')}}" alt="" width="25px;" style="margin-right: 5px;"> Metamask Login
                        </a>
                    </div>
                    <!-- Nav End -->
                </div>
            </nav>
        </div>
    </div>
</header>
<!-- ##### Header Area End ##### -->


@include($theme.'partials.banner')

@yield('content')

@include($theme.'partials.footer')

@stack('extra-content')


<script src="{{asset($themeTrue.'assets/bootstrap/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset($themeTrue.'assets/jquery/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset($themeTrue.'assets/plugins/owlcarousel/owl.carousel.min.js')}}"></script>
<script src="{{asset($themeTrue.'assets/plugins/counterup/jquery.waypoints.min.js')}}"></script>
<script src="{{asset($themeTrue.'assets/plugins/counterup/jquery.counterup.min.js')}}"></script>
<script src="{{asset($themeTrue.'assets/plugins/aos/aos.js')}}"></script>
<script src="{{asset($themeTrue.'assets/plugins/radial-progress/radialprogress.js')}}"></script>
<script src="{{asset($themeTrue.'assets/fontawesome/fontawesome.min.js')}}"></script>

@stack('extra-js')

<script src="{{asset('assets/global/js/notiflix-aio-2.7.0.min.js')}}"></script>
<script src="{{asset('assets/global/js/pusher.min.js')}}"></script>
<script src="{{asset('assets/global/js/vue.min.js')}}"></script>
<script src="{{asset('assets/global/js/axios.min.js')}}"></script>
<!-- custom script -->
<script src="{{asset($themeTrue.'js/script.js')}}"></script>

<!-- script js -->
<script src="{{asset('assets-v2/js/script.js')}}"></script>

<script type="text/javascript">
function googleTranslateElementInit() { new google.translate.TranslateElement({pageLanguage: 'id'}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
@stack('script')

@auth
    <script>
        'use strict';
        let pushNotificationArea = new Vue({
            el: "#pushNotificationArea",
            data: {
                items: [],
            },
            beforeMount() {
                this.getNotifications();
                this.pushNewItem();
            },
            methods: {
                getNotifications() {
                    let app = this;
                    axios.get("{{ route('user.push.notification.show') }}")
                        .then(function (res) {
                            app.items = res.data;
                        })
                },
                readAt(id, link) {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAt', 0) }}";
                    url = url.replace(/.$/, id);
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.getNotifications();
                                if (link !== '#') {
                                    window.location.href = link
                                }
                            }
                        })
                },
                readAll() {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAll') }}";
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.items = [];
                            }
                        })
                },
                pushNewItem() {
                    let app = this;
                    // Pusher.logToConsole = true;
                    let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                        encrypted: true,
                        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
                    });
                    let channel = pusher.subscribe('user-notification.' + "{{ Auth::id() }}");
                    channel.bind('App\\Events\\UserNotification', function (data) {
                        app.items.unshift(data.message);
                    });
                    channel.bind('App\\Events\\UpdateUserNotification', function (data) {
                        app.getNotifications();
                    });
                }
            }
        });
    </script>
@endauth

@if (session()->has('success'))
    <script>
        Notiflix.Notify.Success("@lang(session('success'))");
    </script>
@endif

@if (session()->has('error'))
    <script>
        Notiflix.Notify.Failure("@lang(session('error'))");
    </script>
@endif

@if (session()->has('warning'))
    <script>
        Notiflix.Notify.Warning("@lang(session('warning'))");
    </script>
@endif


@include('plugins')

</body>

</html>
