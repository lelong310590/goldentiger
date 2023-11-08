@extends($theme.'layouts.user')
@section('title', trans($title))

@section('content')

    <!-- investment plans -->
    <section class="payment-gateway mt-5 pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="header-text-full">
                        <h2>@lang($title)</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($gateways as $key => $gateway)
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-4">
                        <div class="gateway-box">
                            <img
                                class="img-fluid gateway"
                                src="{{ getFile(config('location.withdraw.path').$gateway->image)}}"
                                alt="{{$gateway->name}}"
                            >
                            <button type="button"
                                    data-id="{{$gateway->id}}"
                                    data-name="{{$gateway->name}}"
                                    data-min_amount="{{getAmount($gateway->minimum_amount, $basic->fraction_number)}}"
                                    data-max_amount="{{getAmount($gateway->maximum_amount,$basic->fraction_number)}}"
                                    data-percent_charge="{{getAmount($gateway->percent_charge,$basic->fraction_number)}}"
                                    data-fix_charge="{{getAmount($gateway->fixed_charge, $basic->fraction_number)}}"
                                    class="gold-btn addFund mt-4"
                                    data-bs-toggle="modal" data-bs-target="#addFundModal">@lang('PAYOUT NOW')
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    @push('loadModal')
        <div id="addFundModal" class="modal fade addFundModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content form-block">
                    <div class="modal-header">
                        <h4 class="modal-title method-name golden-text"></h4>
                        <button
                            type="button"
                            data-bs-dismiss="modal"
                            class="btn-close"
                            aria-label="Close"
                        >
                        </button>
                    </div>

                    <form action="{{route('user.payout.moneyRequest')}}" method="post">
                        @csrf

                        <div class="modal-body">
                            <div class="payment-form ">
                                <p class="depositLimit"></p>
                                <p class="depositCharge"></p>

                                <div class="form-group my-3">
                                    <h5 class="mb-2 golden-text d-block modal_text_level">@lang('Select wallet')</h5>
                                    <select class="form-control" name="wallet_type">
                                        <!--option value="balance" class="bg-dark text-white">@lang('Deposit Balance - '.$basic->currency_symbol.getAmount(auth()->user()->balance))</option-->
                                        <option value="interest_balance"
                                                class="bg-dark text-white">@lang('Interest Balance: '.$basic->currency_symbol.getAmount(auth()->user()->interest_balance))</option>
                                        <option value="referral_balance"
                                                class="bg-dark text-white">@lang('Referral Balance: '.$basic->currency_symbol.getAmount(auth()->user()->referral_balance))</option>
                                    </select>
                                </div>

                                <input type="hidden" class="gateway" name="gateway" value="">
                                <input type="hidden" name="key" value="{{Request::get('key')}}">

                                <div class="form-group mb-30 mt-3">
                                    <div class="box">
                                        <h5 class="golden-text">@lang('Password')</h5>
                                        <div class="input-group">
                                            <input
                                                type="password" class="password form-control" name="password"
                                            />
                                        </div>
                                    </div>
                                    @error('password')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>

                                <div class="form-group mb-30 mt-3">
                                    <div class="box">
                                        <h5 class="golden-text">@lang('2FA Code')</h5>
                                        <p>If you have not activated 2FA Security yet. <a
                                                href="{{route('user.twostep.security')}}">Click here to</a> active it!
                                        </p>
                                        <div class="input-group">
                                            <input
                                                type="text" class="password form-control" name="two_fa"
                                            />
                                        </div>
                                    </div>
                                    @error('two_fa')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>

                                <div class="form-group mb-30 mt-3">
                                    <div class="box">
                                        <h5 class="golden-text">@lang('Amount')</h5>
                                        <div class="input-group">
                                            <input
                                                type="text" class="amount form-control" name="amount"
                                            />
                                            <button class="gold-btn show-currency"></button>
                                        </div>
                                    </div>
                                    @error('amount')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>


                            </div>

                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="submit" class="btn gold-btn ">@lang('Next')</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endpush

@endsection



@push('script')

    @if(count($errors) > 0 )
        <script>
            @foreach($errors->all() as $key => $error)
            Notiflix.Notify.Failure("@lang($error)");
            @endforeach
        </script>
    @endif

    <script>
        "use strict";
        var id, minAmount, maxAmount, baseSymbol, fixCharge, percentCharge, currency, gateway;

        $('.addFund').on('click', function () {
            id = $(this).data('id');
            gateway = $(this).data('gateway');
            minAmount = $(this).data('min_amount');
            maxAmount = $(this).data('max_amount');
            baseSymbol = "{{config('basic.currency_symbol')}}";
            fixCharge = $(this).data('fix_charge');
            percentCharge = $(this).data('percent_charge');
            currency = $(this).data('currency');
            $('.depositLimit').text(`@lang('Transaction Limit:') ${minAmount} - ${maxAmount}  ${baseSymbol}`);

            var depositCharge = `@lang('Charge:') ${fixCharge} ${baseSymbol}  ${(0 < percentCharge) ? ' + ' + percentCharge + ' % ' : ''}`;
            $('.depositCharge').text(depositCharge);
            $('.method-name').text(`@lang('Payout By') ${$(this).data('name')}`);
            $('.show-currency').text("{{config('basic.currency')}}");
            $('.gateway').val(id);
        });
        $('.close').on('click', function (e) {
            $('#loading').hide();
            $('.amount').val(``);
            $("#addFundModal").modal("hide");
        });

    </script>
@endpush


