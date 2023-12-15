@extends($theme.'layouts.user')
@section('title',trans('Dashboard'))
@section('content')

    <script>
        "use strict"

        function getCountDown(elementId, seconds) {
            var times = seconds;
            var x = setInterval(function () {
                var distance = times * 1000;
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById(elementId).innerHTML = days + "d: " + hours + "h " + minutes + "m " + seconds + "s ";
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById(elementId).innerHTML = "COMPLETE";
                }
                times--;
            }, 1000);
        }
    </script>


    <!---- other balances ----->
    <section class="statistic-section mt-5 pt-5 pb-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="header-text-full d-flex justify-content-between align-items-center">
                        <h3 class="mb-4">@lang('dashboard')</h3>
                        <div class="d-flex align-item-center wrap-gtf-price cc-coin">
                            <div class="mx-2">
                                <img class="coin-img" alt="litecoin"
                                     src="{{ asset('assets/uploads/logo/favicon.png') }}" style="max-height: 24px;">
                            </div>
                            (GTF)
                            <span style="text-align:center;margin-left:1ch;"
                                  class="">$ <span>{{ $priceGtf ?? 0 }}</span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div
                        class="box"
                    >
                        <div class="img-box">
                            <img src="{{asset('assets/uploads/logo/favicon.png')}}" alt="@lang('GTF amount')"/>
                        </div>
                        <h4>GTF Token</h4>
                        <h2>{{getAmount($gtfBalance, config('basic.fraction_number'))}}</h2>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-4">
                    <div
                        class="box"
                    >
                        <div class="img-box">
                            <img src="{{asset('assets/uploads/logo/favicon.png')}}" alt="@lang('GTF amount')"/>
                        </div>
                        <h4>GTF Point (Old GTF)</h4>
                        <h2>{{getAmount($gtfOld, config('basic.fraction_number'))}}</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--    <!-- Imvest history -->--}}
    {{--    <section class="transaction-history mt-5 pt-5">--}}
    {{--      <div class="container-fluid">--}}
    {{--          <div class="row">--}}
    {{--                <div class="col">--}}
    {{--                    <div class="header-text-full">--}}
    {{--                        <h2>{{trans('Invest History')}}</h2>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--          <div class="row">--}}
    {{--                <div class="col">--}}
    {{--                    <div class="table-parent table-responsive">--}}
    {{--                        <table class="table table-striped mb-5">--}}
    {{--                            <thead>--}}
    {{--                                <tr>--}}
    {{--                                    <th scope="col">@lang('SL')</th>--}}
    {{--                                    <th scope="col">@lang('Plan')</th>--}}
    {{--                                    <th scope="col">@lang('Return Interest')</th>--}}
    {{--                                    <th scope="col">@lang('Received Amount')</th>--}}
    {{--                                    <th scope="col">@lang('Upcoming Payment')</th>--}}
    {{--                                </tr>--}}
    {{--                            </thead>--}}
    {{--                            <tbody>--}}
    {{--                                @forelse($investments as $key => $invest)--}}
    {{--                                <tr>--}}
    {{--                                    <td>{{loopIndex($investments) + $key}}</td>--}}
    {{--                                    <td>--}}
    {{--                                        {{trans(optional($invest->plan)->name)}}--}}
    {{--                                        <br> {{getAmount($invest->amount).' '.trans($basic->currency)}}--}}
    {{--                                    </td>--}}
    {{--                                    <td>--}}
    {{--                                        {{getAmount($invest->profit)}} {{trans($basic->currency)}}--}}
    {{--                                        {{($invest->period == '-1') ? trans('For Lifetime') : 'per '. trans($invest->point_in_text)}}--}}
    {{--                                        <br>--}}
    {{--                                        {{($invest->capital_status == '1') ? '+ '.trans('Capital') :''}}--}}
    {{--                                    </td>--}}
    {{--                                    <td>--}}
    {{--                                        {{$invest->recurring_time}} x {{ $invest->profit }} =  {{getAmount($invest->recurring_time*$invest->profit) }} {{trans($basic->currency)}}--}}
    {{--                                    </td>--}}
    {{--                                    <td>--}}
    {{--                                        @if($invest->status == 1)--}}
    {{--                                            <p id="counter{{$invest->id}}" class="mb-2"></p>--}}
    {{--                                            <script>getCountDown("counter{{$invest->id}}", {{\Carbon\Carbon::parse($invest->afterward)->diffInSeconds()}});</script>--}}
    {{--                                            <div class="progress">--}}
    {{--                                                <div class="progress-bar progress-bar-striped bg-danger" role="progressbar"  style="width: {{$invest->nextPayment}}"  aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{$invest->nextPayment}}</div>--}}
    {{--                                            </div>--}}
    {{--                                        @else--}}
    {{--                                            <span class="badge badge-success">@lang('Completed')</span>--}}
    {{--                                        @endif--}}
    {{--                                    </td>--}}
    {{--                                </tr>--}}
    {{--                                @empty--}}
    {{--                                    <tr class="text-center">--}}
    {{--                                        <td colspan="100%">{{trans('No Data Found!')}}</td>--}}
    {{--                                    </tr>--}}
    {{--                                @endforelse--}}

    {{--                            </tbody>--}}
    {{--                            <thead>--}}
    {{--                                <tr>--}}
    {{--                                    <th scope="col"><a style="color: #fff;" href="{{route('user.invest-history')}}">@lang('View All')</a></th>--}}
    {{--                                </tr>--}}
    {{--                            </thead>--}}
    {{--                        </table>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--      </div>--}}
    {{--    </section>--}}

    <!---- charts ----->
    {{--    <section class="chart-information mt-5">--}}
    {{--        <div class="container-fluid">--}}
    {{--        <div class="row justify-content-center">--}}
    {{--            <div class="col-lg-6 mb-4 mb-lg-0">--}}
    {{--                <div class="progress-wrapper">--}}
    {{--                    <div--}}
    {{--                        id="container"--}}
    {{--                        class="apexcharts-canvas"--}}
    {{--                    ></div>--}}
    {{--                </div>--}}
    {{--            </div>--}}

    {{--            <div class="col-lg-6">--}}
    {{--                <div class="progress-wrapper progress-wrapper-circle">--}}
    {{--                    <div class="progress-container d-flex flex-column flex-sm-row justify-content-around">--}}
    {{--                        <div class="circular-progress cp_1">--}}
    {{--                            <svg--}}
    {{--                                class="radial-progress"--}}
    {{--                                data-percentage="{{getPercent($roi['totalInvest'], $roi['completed'])}}"--}}
    {{--                                viewBox="0 0 80 80"--}}
    {{--                            >--}}
    {{--                                <circle--}}
    {{--                                    class="incomplete"--}}
    {{--                                    cx="40"--}}
    {{--                                    cy="40"--}}
    {{--                                    r="35"--}}
    {{--                                ></circle>--}}
    {{--                                <circle--}}
    {{--                                    class="complete"--}}
    {{--                                    cx="40"--}}
    {{--                                    cy="40"--}}
    {{--                                    r="35"--}}
    {{--                                    style="--}}
    {{--                                    stroke-dashoffset: 39.58406743523136;--}}
    {{--                                    "--}}
    {{--                                ></circle>--}}
    {{--                                <text--}}
    {{--                                    class="percentage"--}}
    {{--                                    x="50%"--}}
    {{--                                    y="53%"--}}
    {{--                                    transform="matrix(0, 1, -1, 0, 80, 0)"--}}
    {{--                                >--}}
    {{--                                {{getPercent($roi['totalInvest'], $roi['completed'])}} %--}}
    {{--                                </text>--}}
    {{--                            </svg>--}}
    {{--                            <h4 class="golden-text mt-4 text-center">--}}
    {{--                                @lang('Invest Completed')--}}
    {{--                            </h4>--}}
    {{--                        </div>--}}

    {{--                        <div class="circular-progress cp_3">--}}
    {{--                            <svg--}}
    {{--                                class="radial-progress"--}}
    {{--                                data-percentage="{{100 - getPercent($roi['expectedProfit'], $roi['returnProfit'])}}"--}}
    {{--                                viewBox="0 0 80 80"--}}
    {{--                            >--}}
    {{--                                <circle--}}
    {{--                                    class="incomplete"--}}
    {{--                                    cx="40"--}}
    {{--                                    cy="40"--}}
    {{--                                    r="35"--}}
    {{--                                ></circle>--}}
    {{--                                <circle--}}
    {{--                                    class="complete"--}}
    {{--                                    cx="40"--}}
    {{--                                    cy="40"--}}
    {{--                                    r="35"--}}
    {{--                                    style="--}}
    {{--                                    stroke-dashoffset: 39.58406743523136;--}}
    {{--                                    "--}}
    {{--                                ></circle>--}}
    {{--                                <text--}}
    {{--                                    class="percentage"--}}
    {{--                                    x="50%"--}}
    {{--                                    y="53%"--}}
    {{--                                    transform="matrix(0, 1, -1, 0, 80, 0)"--}}
    {{--                                >--}}
    {{--                                {{100 - getPercent($roi['expectedProfit'], $roi['returnProfit'])}} %--}}
    {{--                                </text>--}}
    {{--                            </svg>--}}

    {{--                            <h4 class="golden-text mt-4 text-center">--}}
    {{--                                @lang('ROI Speed')--}}
    {{--                            </h4>--}}
    {{--                        </div>--}}

    {{--                        <div class="circular-progress cp_2">--}}
    {{--                            <svg--}}
    {{--                                class="radial-progress"--}}
    {{--                                data-percentage="{{getPercent($roi['expectedProfit'], $roi['returnProfit'])}}"--}}
    {{--                                viewBox="0 0 80 80"--}}
    {{--                            >--}}
    {{--                                <circle--}}
    {{--                                    class="incomplete"--}}
    {{--                                    cx="40"--}}
    {{--                                    cy="40"--}}
    {{--                                    r="35"--}}
    {{--                                ></circle>--}}
    {{--                                <circle--}}
    {{--                                    class="complete"--}}
    {{--                                    cx="40"--}}
    {{--                                    cy="40"--}}
    {{--                                    r="35"--}}
    {{--                                    style="--}}
    {{--                                    stroke-dashoffset: 147.3406954533613;--}}
    {{--                                    "--}}
    {{--                                ></circle>--}}
    {{--                                <text--}}
    {{--                                    class="percentage"--}}
    {{--                                    x="50%"--}}
    {{--                                    y="53%"--}}
    {{--                                    transform="matrix(0, 1, -1, 0, 80, 0)"--}}
    {{--                                >--}}
    {{--                                {{getPercent($roi['expectedProfit'], $roi['returnProfit'])}} %--}}
    {{--                                </text>--}}
    {{--                            </svg>--}}

    {{--                            <h4 class="golden-text mt-4 text-center">--}}
    {{--                                @lang('ROI Redeemed')--}}
    {{--                            </h4>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        </div>--}}
    {{--    </section>--}}


    <!----- account balances ----->
    <!--section class="statistic-section mt-5 pt-5 pb-0">
        <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="header-text-full">
                    <h2 class="text-center">@lang('Account Statistics')</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
                <div
                    class="box"
                    data-aos="fade-up"
                    data-aos-duration="800"
                    data-aos-anchor-placement="center-bottom"
                >
                    <div class="img-box">
                        <img src="{{asset($themeTrue.'img/icon/money-bag.png')}}" alt="@lang('Total Invest')"/>
                    </div>
                    <h4>@lang('Total Invest')</h4>
                    <h2><small><sup>{{trans(config('basic.currency_symbol'))}}</sup></small>{{getAmount($roi['totalInvestAmount'])}}</h2>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
                <div
                    class="box"
                    data-aos="fade-up"
                    data-aos-duration="1200"
                    data-aos-anchor-placement="center-bottom"
                >
                    <div class="img-box">
                    <img src="{{asset($themeTrue.'img/icon/payout.png')}}" alt="@lang('Total Payout')"/>
                    </div>
                    <h4>@lang('Total Payout')</h4>
                    <h2><small><sup>{{trans(config('basic.currency_symbol'))}}</sup></small>{{getAmount($totalPayout)}}</h2>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4 mb-md-0">
                <div
                    class="box"
                    data-aos="fade-up"
                    data-aos-duration="800"
                    data-aos-anchor-placement="center-bottom"
                >
                    <div class="img-box">
                    <img src="{{asset($themeTrue.'img/icon/support.png')}}" alt="@lang('Total Ticket')"/>
                    </div>
                    <h4>@lang('Total Ticket')</h4>
                    <h2>{{$ticket}}</h2>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div
                    class="box"
                    data-aos="fade-up"
                    data-aos-duration="1200"
                    data-aos-anchor-placement="center-bottom"
                >
                    <div class="img-box">
                    <img src="{{asset($themeTrue.'img/icon/bonus.png')}}" alt="@lang('Total Referral Bonus')"/>
                    </div>
                    <h4>@lang('Total Referral Bonus')</h4>
                    <h2><small><sup>{{trans(config('basic.currency_symbol'))}}</sup></small>{{getAmount($depositBonus + $investBonus)}}</h2>
                </div>
            </div>
        </div>
        </div>
    </section-->


    <!----- refferal-information ----->
    <section class="refferal-link mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-{{($latestRegisteredUser) ? '12':'6'}}">
                    <div class="box">
                        <h4 class="golden-text">@lang('Referral Link')</h4>
                        <div class="input-group">
                            <input
                                type="text"
                                value="{{route('home')}}?ref={{Auth::user()->real_wallet}}"
                                class="form-control"
                                id="sponsorURL"
                                readonly
                            />
                            <button class="gold-btn copytext" id="copyBoard" onclick="copyFunction()"><i
                                    class="fa fa-copy mx-1"></i>@lang('copy link')</button>
                        </div>
                    </div>
                </div>

                @if($latestRegisteredUser)
                    <div class="col-md-6 mb-4 mb-md-0 refferal-information mt-5">
                        <div class="box">
                            <div class="img-box">
                                <img src="{{asset($themeTrue.'img/icon/handshake.png')}}" alt="@lang('handshake img')"/>
                            </div>
                            <div>
                                <h4 class="golden-text">@lang('Latest Registered Partner')</h4>
                                <p>{{$latestRegisteredUser->username}} <span class="pe-2">@lang('Email')
                            : {{$latestRegisteredUser->email}}</span></p>
                            </div>
                        </div>
                    </div>
                @endif

                {{--            <div class="col-md-6 refferal-information {{($latestRegisteredUser) ? 'mt-5':''}}">--}}
                {{--                <div class="box">--}}
                {{--                    <div class="img-box">--}}
                {{--                    <img src="{{asset($themeTrue.'img/icon/deposit.png')}}" alt="@lang('Referral Bonus img')" />--}}
                {{--                    </div>--}}
                {{--                    <div>--}}
                {{--                    <h4 class="golden-text">@lang('The last Referral Bonus')</h4>--}}
                {{--                    <p>{{trans($basic->currency_symbol)}} {{$lastBonus}}</p>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--            </div>--}}
            </div>
        </div>
    </section>


    {{--<!-- INVEST-NOW MODAL -->--}}
    {{--<div class="modal fade addFundModal" id="investNowModal" tabindex="-1" data-bs-backdrop="static"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >--}}
    {{--    <div class="modal-dialog modal-dialog-centered" role="document">--}}
    {{--      <div class="modal-content">--}}
    {{--        <div class="modal-header">--}}
    {{--            <h3 class="modal-title golden-text" id="exampleModalLabel">@lang('Notification')</h3>--}}
    {{--            <button--}}
    {{--                type="button"--}}
    {{--                data-bs-dismiss="modal"--}}
    {{--                class="btn-close btn-close-investment"--}}
    {{--                aria-label="Close"--}}
    {{--            >--}}
    {{--                <img src="{{asset($themeTrue.'img/icon/cross.png')}}" alt="@lang('cross img')" />--}}
    {{--            </button>--}}
    {{--        </div>--}}
    {{--        <div class="modal-body">--}}
    {{--            <div class="form-block">--}}
    {{--                <div class="signin ">--}}
    {{--                    <h2 class="title golden-text text-center plan-name"></h2>--}}

    {{--                    <div class="form-group mb-3">--}}
    {{--                        <div class="box">--}}
    {{--                            Notice From Goldentigerfund. <br>--}}

    {{--                            Sincere thanks to the Community and investors for accompanying GTF.<br>--}}

    {{--                            We ANNOUNCE to the community the launch issuance program and GTF Giveaway for investors who have participated and trusted us in the past.<br>--}}

    {{--                            On September 15, 2023, the GTF staking feature will be activated and unlocked.<br>--}}

    {{--                            We have been developing according to the roadmap of the project announced to the community.<br>--}}

    {{--                            The timing of holding and increasing GTF is great.<br>--}}

    {{--                            For all inquiries from customers and the community, please contact GTF's support department.<br>--}}

    {{--                            Best regards !<br>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}

    {{--                    <div class="btn-area mb-30 modal-footer border-top-0 p-0">--}}
    {{--                        <button --}}
    {{--                        type="button"--}}
    {{--                        data-bs-dismiss="modal"--}}
    {{--                        class="btn-close-investment gold-btn w-100"--}}
    {{--                        aria-label="Close">@lang('Close')</button>--}}
    {{--                    </div>--}}

    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--      </div>--}}
    {{--    </div>--}}
    {{--</div>--}}
    {{--<!-- INVEST-NOW MODAL -->--}}

@endsection


@push('script')

    <script src="{{asset($themeTrue.'js/apexcharts.js')}}"></script>


    <script>
        "use strict";

        (function ($) {

            var planModal = new bootstrap.Modal(document.getElementById('investNowModal'))
            planModal.show()

        })(jQuery);

        var options = {
            theme: {
                mode: 'dark',
            },

            series: [
                {
                    name: "{{trans('Investment')}}",
                    color: 'rgba(247, 147, 26, 1)',
                    data: {!! $monthly['investment']->flatten() !!}
                },
                {
                    name: "{{trans('Payout')}}",
                    color: 'rgba(240, 16, 16, 1)',
                    data: {!! $monthly['payout']->flatten() !!}
                },
                {
                    name: "{{trans('Deposit')}}",
                    color: 'rgba(255, 72, 0, 1)',
                    data: {!! $monthly['funding']->flatten() !!}
                },
                {
                    name: "{{trans('Deposit Bonus')}}",
                    color: 'rgba(39, 144, 195, 1)',
                    data: {!! $monthly['referralFundBonus']->flatten() !!}
                },
                {
                    name: "{{trans('Investment Bonus')}}",
                    color: 'rgba(136, 203, 245, 1)',
                    data: {!! $monthly['referralInvestBonus']->flatten() !!}
                }
            ],
            chart: {
                type: 'bar',
                // height: ini,
                background: '#000',
                toolbar: {
                    show: false
                }

            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: {!! $monthly['investment']->keys() !!},

            },
            yaxis: {
                title: {
                    text: ""
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                colors: ['#000'],
                y: {
                    formatter: function (val) {
                        return "{{trans($basic->currency_symbol)}}" + val + ""
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#container"), options);
        chart.render();

        function copyFunction() {
            var copyText = document.getElementById("sponsorURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.Success(`Copied: ${copyText.value}`);
        }
    </script>
@endpush
