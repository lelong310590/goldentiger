@if(isset($templates['investment'][0]) && $investment = $templates['investment'][0])
    <!-- plan start -->
    <section class="pricing-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="header-text text-center">
                        <h2>Join our investment plan</h2>
                        <p>Help agencies to define their new business objectives and then create professional software</p>
                    </div>
                </div>
            </div>

            <div class="row ">
                @foreach($plans as $k => $data)
                    @php
                        $getTime = \App\Models\ManageTime::where('time', $data->schedule)->first();
                    @endphp
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div
                            class="box"
{{--                            data-aos="fade-up"--}}
{{--                            data-aos-duration="800"--}}
{{--                            data-aos-anchor-placement="center-bottom"--}}
                            >
                            <div class="plan-image">
                                @php
                                    $link = $themeTrue.'img/icon/price0'. $k+1 .'.png';
                                @endphp
                                <img src="{{asset($link)}}" alt="price-icon" class="img-fluid">
                            </div>
                            <h2>@lang($data->name)</h2>
                            <h3>{{$data->price}}</h3>
                            <div class="bg">
                                @if ($data->profit_type == 1)
                                    <span class="golden-text">{{getAmount($data->profit)}}{{'%'}} <small class="small-font">@lang('Every') {{trans($getTime->name)}}</small></span>
                                @else
                                    <span class="golden-text"><small><sup>{{trans($basic->currency_symbol)}}</sup></small>{{getAmount($data->profit)}} <small class="small-font">@lang('Every') {{trans($getTime->name)}}</small></span>
                                @endif
                            </div>

                            <h4 class="golden-text">@lang('Profit For')  {{($data->is_lifetime ==1) ? trans('Lifetime') : trans('Every').' '.trans($getTime->name)}}</h4>
                            <h4>@lang('Capital will back') :
                                <small><span class="badge-small badge bg-{{($data->is_capital_back ==1) ? 'success':'danger'}}">{{($data->is_capital_back ==1) ? trans('Yes'): trans('No')}}</span></small></h4>
                            <h4>
                                @if($data->is_lifetime == 0)
                                    <span class="golden-text">@lang('Total') {{trans($data->profit*$data->repeatable)}} {{($data->profit_type == 1) ? '%': trans($basic->currency)}} + </span>
                                    @if($data->is_capital_back == 1)
                                        <span class="badge-small badge bg-success">@lang('Capital')</span>
                                    @endif
                                @else
                                    <span class="golden-text">@lang('Lifetime Earning')</span>
                                @endif
                            </h4>
                            <button class="gold-btn btn investNow" type="button"
                                data-price="{{$data->price}}"
                                data-resource="{{$data}}">@lang('Invest Now')
                            </button>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- plan end -->
@endif

@if (session('invest-success'))
<div class="investment-success">
    <div class="investment-success-wrapper">
        <h2 class="investment-success-title text-center">CONGRATULATIONS!</h2>
        <div class="investment-success-content text-center">
            <p>You are successfully registered</p>
            <p>{{session('invest-success')}}</p>
        </div>
        <div class="investment-success-button d-flex justify-content-center">
            <a href="javascript:;" class="gold-btn btn close-success d-flex justify-content-center align-items-center mt-3">Continue</a>
        </div>
    </div>
</div>
@endif


<!-- INVEST-NOW MODAL -->
<div class="modal fade addFundModal" id="investNowModal" tabindex="-1" data-bs-backdrop="static"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title golden-text" id="exampleModalLabel">@lang('Invest Now')</h3>
            <button
                type="button"
                data-bs-dismiss="modal"
                class="btn-close btn-close-investment"
                aria-label="Close"
            >
            </button>
        </div>
        <div class="modal-body">
            <div class="form-block">
                <form class="login-form" id="invest-form" action="{{route('user.purchase-plan')}}" method="post">
                    @csrf
                    <div class="signin ">
                        <h2 class="title golden-text text-center plan-name"></h2>

                        @if($basic->theme == 'lightorange')
                            <p class="text-center price-range font-20 planDetails"></p>
                            <p class="text-center profit-details font-18 planDetails"></p>
                            <p class="text-center profit-validity pb-3 font-18 planDetails"></p>
                        @elseif($basic->theme == 'deepblack')
                            <p class="text-center price-range lebelFont"></p>
                            <p class="text-center profit-details lebelFont"></p>
                            <p class="text-center profit-validity pb-3 lebelFont"></p>
                        @else
                            <p class="text-success text-center price-range font-20"></p>
                            <p class="text-success text-center profit-details font-18"></p>
                            <p class="text-success text-center profit-validity pb-3 font-18"></p>
                        @endif

                        <div class="form-group mb-3">
                            <h5 class="mb-2 golden-text d-block modal_text_level">@lang('Select wallet')</h5>
                            <select class="form-control" name="balance_type">
                                @auth
                                    <option
                                        value="balance" class="bg-dark text-white">@lang('Deposit Balance - '.$basic->currency_symbol.getAmount(auth()->user()->balance))</option>
                                    <option
                                        value="interest_balance" class="bg-dark text-white">@lang('Interest Balance -'.$basic->currency_symbol.getAmount(auth()->user()->interest_balance))</option>
                                @endauth
                                <option value="checkout" class="bg-dark text-white">@lang('Checkout')</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <div class="box">
                                <h5 class="golden-text">@lang('Amount')</h5>
                                <div class="input-group">
                                    <input
                                        type="text" class="invest-amount form-control" name="amount" id="amount" value="{{old('amount')}}"
                                        onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                        autocomplete="off"
                                        placeholder="@lang('Enter amount')">
                                    <button class="gold-btn show-currency"></button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="plan_id" class="plan-id">

                        <div class="btn-area mb-30 modal-footer border-top-0 p-0">
                            <button type="submit" class="gold-btn w-100">@lang('Invest Now')</button>
                        </div>

                    </div>
                </form>

            </div>

        </div>
      </div>
    </div>
  </div>
<!-- INVEST-NOW MODAL -->


@push('script')
    <script>
        "use strict";
        (function ($) {
            $(document).on('click', '.investNow', function () {
                // $("#investNowModal").toggleClass("modal-open");
                var planModal = new bootstrap.Modal(document.getElementById('investNowModal'))
                planModal.show()
                let data = $(this).data('resource');
                let price = $(this).data('price');
                let symbol = "{{trans($basic->currency_symbol)}}";
                let currency = "{{trans($basic->currency)}}";
                $('.price-range').text(`@lang('Invest'): ${price}`);

                if (data.fixed_amount == '0') {
                    $('.invest-amount').val('');
                    $('#amount').attr('readonly', false);
                } else {
                    $('.invest-amount').val(data.fixed_amount);
                    $('#amount').attr('readonly', true);
                }

                $('.profit-details').html(`@lang('Interest'): ${(data.profit_type == '1') ? `${data.profit} %` : `${data.profit} ${currency}`}`);
                $('.profit-validity').html(`@lang('Per') ${data.schedule} @lang('hours') ,  ${(data.is_lifetime == '0') ? `${data.repeatable} @lang('times')` : `@lang('Lifetime')`}`);
                $('.plan-name').text(data.name);
                $('.plan-id').val(data.id);
                $('.show-currency').text("{{config('basic.currency')}}");
            });

            $(document).on('click', '.close-success', function () {
                $('.investment-success').hide();
            })

        })(jQuery);

    </script>

    @if(count($errors) > 0 )
        <script>
            @foreach($errors->all() as $key => $error)
            Notiflix.Notify.Failure("@lang($error)");
            @endforeach
        </script>
    @endif
@endpush
