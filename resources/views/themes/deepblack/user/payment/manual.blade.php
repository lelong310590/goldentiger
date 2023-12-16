@extends($theme.'layouts.user')
@section('title')
    {{ 'Pay with BEP20' }}
@endsection

@section('content')
    <section class="transaction-history mt-5 pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="header-text-full">
                        <h4>Presale Buy GTF</h4>
                    </div>
                </div>
            </div>

            <!-- ##### Token Info Start ##### -->
            <div class=" section-padding-100" id="ico">
                <div class="container">

                    <div class="row align-items-center">
                        <div class="col-12 col-lg-5 offset-lg-0 col-md-5">
                            <div class="ico-counter">
                                <div class="counter-down">

                                    <div class="content">
                                        <div class="conuter-header">
                                            <h3 class="w-text text-center">TOKEN SALE ENDS IN</h3>
                                        </div>
                                        <div class="counterdown-content">
                                            <!-- Countdown  -->
                                            <div class="count-down titled circled text-center">
                                                <div class="simple_timer"></div>
                                            </div>
                                            <div class="ico-progress">
                                                <ul class="list-unstyled list-inline clearfix mb-10">
                                                    <li class="title">0.1m</li>
                                                    <li class="strength">1m</li>
                                                </ul>
                                                <!-- skill strength -->
                                                <div class="current-progress">
                                                    <div class="progress-bar has-gradient" style="width: 75%"></div>
                                                </div>
                                                <span class="pull-left">Softcap in 103 days</span>
                                                <span class="pull-right">Token Hardcap</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-7 col-md-7">
                            <div class="card secbg br-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="title text-center">{{trans('Please follow the instruction below')}}</h3>
                                            <p class="text-center mt-2 ">{{trans('You have requested to deposit')}}
                                                , {{trans('Please pay')}}
                                                <b class="text--base">more than 1 USDT as
                                                    fee</b> {{trans('for successful payment')}}
                                            </p>

                                            <p class="mt-2 ">
                                            <p style="text-align: center; ">Please send <b>USDT-BEP20</b> to this address with
                                                exactly your total deposit amount:</p>
                                            <div class="d-flex align-items-center flex-column">
                                                <img
                                                    src="https://api.qrserver.com/v1/create-qr-code/?color=fae15e&amp;bgcolor=222222&amp;data={{auth()->user()->wallet_address}}&amp;qzone=1&amp;margin=0&amp;size=200x200&amp;ecc=L"
                                                    style="width: 200px;" class="mt-4 mb-4 m-auto"><br></p>
                                                <div class="box refferal-link" style="width: 100%">
                                                    <div class="input-group">
                                                        <input
                                                            type="text"
                                                            value="{{auth()->user()->wallet_address}}"
                                                            class="form-control text-center"
                                                            id="referralURL"
                                                            readonly
                                                        />
                                                    </div>
                                                </div>
                                                <button class="gold-btn copytext mt-2" id="copyBoard"
                                                        onclick="copyFunction()"><i
                                                        class="fa fa-copy me-1"></i>@lang('copy address')</button>
                                            </div>
                                            {{--                                        <p style="text-align: center; margin-top: 10px;">After your transaction confirmed successfully please provide these following info:</p>--}}
                                            <?php //echo optional($order->gateway)->note; ?>

                                            {{--                                    <form action="" method="post" enctype="multipart/form-data" class="form-row preview-form">--}}
                                            {{--                                        @csrf--}}
                                            {{--                                        <div class="col-md-12 ">--}}
                                            {{--                                            <div class="form-group">--}}
                                            {{--                                                <button type="submit" class="gold-btn w-100 mt-3">--}}
                                            {{--                                                    <span>@lang('Confirm Now')</span>--}}
                                            {{--                                                </button>--}}
                                            {{--                                            </div>--}}
                                            {{--                                        </div>--}}
                                            {{--                                    </form>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    @push('css-lib')
        <link rel="stylesheet" href="{{asset($themeTrue.'scss/bootstrap-fileinput.css')}}">
    @endpush

    @push('extra-js')
        <script src="{{asset($themeTrue.'js/bootstrap-fileinput.js')}}"></script>
    @endpush

    @push('script')
        <script>
            function copyFunction() {
                var copyText = document.getElementById("referralURL");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                Notiflix.Notify.Success(`Copied: ${copyText.value}`);
            }
        </script>

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
    @endpush
@endsection
