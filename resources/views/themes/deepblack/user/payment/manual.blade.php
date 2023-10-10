@extends($theme.'layouts.user')
@section('title')
    {{ 'Pay with '.optional($order->gateway)->name ?? '' }}
@endsection

@section('content')
    <section class="transaction-history mt-5 pt-5">
        <div class="container-fluid">
            <div class="row">
                    <div class="col">
                        <div class="header-text-full">
                                <h2>{{ 'Pay with '.optional($order->gateway)->name ?? '' }}</h2>
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
                                    <p class="text-center mt-2 ">{{trans('You have requested to deposit')}}  <b class="text--base">{{getAmount($order->amount)}}
                                            {{$basic->currency}}</b> , {{trans('Please pay')}}
                                        <b class="text--base">{{getAmount($order->final_amount)}} {{$order->gateway_currency}}</b>  {{trans('for successful payment')}}
                                    </p>

                                    <p class="mt-2 ">
                                        <p style="text-align: center; ">Please send <b>USDT-BEP20</b> to this address with exactly your total deposit amount:</p>
                                        <p style="text-align: center; margin-top: 10px" class="mt-2">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?color=fae15e&amp;bgcolor=222222&amp;data={{auth()->user()->wallet_address}}&amp;qzone=1&amp;margin=0&amp;size=200x200&amp;ecc=L" style="width: 200px;"><br></p>
                                            <p style="text-align: center; " class="mt-2">{{auth()->user()->wallet_address}}</p>
                                        <p style="text-align: center; margin-top: 10px;">After your transaction confirmed successfully please provide these following info:</p>
                                        <?php //echo optional($order->gateway)->note; ?>
                                    </p>

                                    <form action="" method="post" enctype="multipart/form-data" class="form-row preview-form">
                                        @csrf
                                        <div class="col-md-12 ">
                                            <div class="form-group">
                                                <button type="submit" class="gold-btn w-100 mt-3">
                                                    <span>@lang('Confirm Now')</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
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
@endsection
