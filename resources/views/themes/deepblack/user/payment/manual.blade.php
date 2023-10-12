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
                        <h4>Pay with BEP20</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
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
    @endpush
@endsection
