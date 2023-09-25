@extends($theme . 'layouts.user')
@section('title', trans('Staking'))
@section('content')
    <script>
        "use strict"

        function getCountDown(elementId, seconds) {
            var times = seconds;
            var x = setInterval(function() {
                var distance = times * 1000;
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById(elementId).innerHTML = days + "d: " + hours + "h " + minutes + "m " +
                    seconds + "s ";
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById(elementId).innerHTML = "COMPLETE";
                }
                times--;
            }, 1000);
        }
    </script>

    <!-- Staking -->
    <section class="transaction-history mt-5 pt-5">
        <div class="container-fluid">
            @if (session('flash_message'))
                <div class="alert alert-danger">
                    {{ session('flash_message') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col">
                    <h2>{{ trans('Swap token') }}</h2>
                </div>
            </div>
            <div class="sc-hgRTRy iSqzXS ClaimMdx"
                style="background-image: url('{{ asset($themeTrue . 'img/reward.png') }}')">
                <div class="sc-jKmXuR iWXIBe">
                    <div class="sc-iIHSe hZYvbF">
                        <div class="css-zkfaav" style="cursor: pointer;">
                            <div class="sc-gldTML cJuebY">GTF Price</div>
                            <img width="16px" height="16px"
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAWpJREFUOE+d0zFIV1EUx/HPJXQLGoWopTExaYgGN2lxaahMh7aWqCBws6UpQYiCiiIiRByiWlpaxFFoLXRw0KGWwCAwiArixXmcB9cHf6Tu8rjnnvO9v9+55xW91TTNSVzFORzP409YxfNSymZdUrpN0zTDuIdrONQH5/4PnmCulPI7Yi0gi99hMhM38LEHGcNoxtYwFZAO8BA3qoJL+IJjGfuMEbyuch6VUm6W9PyhJ/siZjGEX6n0Jd5UgLBzKgD3casnNwBH8SwcIvY/e4AoeRCA8BudjxVK4sbFVLCDXZxBKLidasYzfzMAezicxfF0L7CUgB/4mr0IQDzvlXzSgHyvAXfxDdcxgwmczZveYx0reIojqaYFdBbuZPJpLA+YgwvYyrPIby10TewA3XcAQ53XNjEaGM0Ljws4P6gy428xj8vtM+YkxiCF91fYPgBwAtN43A7SgFE+gGH/KFeQ//uZ6uv+9Xf+CyULgPnMo2oEAAAAAElFTkSuQmCC"
                                alt="claim">
                        </div>
                        <div class="sc-feryYK emsNPc">{{ $price_gtf }}$</div>
                    </div>
                    <div class="sc-iIHSe hZYvbF">
                        <div class="css-zkfaav" style="cursor: pointer;">
                            <div class="sc-gldTML cJuebY">GTF Interest Balance</div>
                            <img width="16px" height="16px"
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAAXNSR0IArs4c6QAAAF1QTFRFAAAA////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////USr75QAAAB50Uk5TAAYJGSssbG1ub4+TlJqbnaWnq6y8vtTb3N3w8vz9E7rVugAAAI1JREFUGNNlj8ESgjAMRAMUUlorWrGoLfv/n2nSMh5kL5m8ySYbIpFxKefkDDV1vqCq+K72K35alVywB57fCzM/4cVfEIbJvhYeiVEMOcCyjsd+lOooAcy1f2hNlBuIfcTtDuQDfKTHtik4LMIxW7XIUp5aCra6tJ5l1TRc9Sx5CWYrCbsGO0c/Pff3/hcLqREOD2zvsQAAAABJRU5ErkJggg=="
                                alt="extract">
                        </div>
                        <div class="sc-feryYK emsNPc">{{ $gtfInterestBalance }}</div>
                    </div>
                </div>
            </div>
            <div id="BodyWrapper" class="sc-hUfwpO fSMTrg">
                <small><i class="mb-2 text-secondary">*Note: Your usdt will go to the interest balance!</i></small>
                <div id="swap-page" class="sc-gHboQg bIMRGM">
                    <form action="{{ route('user.swap') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="sc-ifAKCX dcxnAx">
                            <div id="swap-currency-input" class="sc-iujRgT klLNRk">
                                <div class="sc-GMQeP cDdCcR">
                                    <div id="InputRow" class="sc-cLQEGU hAfotE">
                                        <div id="RightContentDiv" class="sc-daURTG isZsJa">
                                            <div class="sc-bXGyLb gffPBZ">
                                                <input class="sc-hMFtBS kfBjC token-amount-input" name="gtf_amount"
                                                    font-size="24px" inputmode="decimal" title="Token Amount"
                                                    autocomplete="off" autocorrect="off" type="number"
                                                    pattern="^[0-9]*[.,]?[0-9]*$" placeholder="0.0" minlength="1"
                                                    maxlength="79" spellcheck="false" value="">
                                            </div>
                                            <div class="sc-gojNiO jknDzv">
                                                <div class="sc-hORach kpDIPd">
                                                    <div class="sc-gqjmRU sc-jTzLTM sc-fjdhpX hwjYkd"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="gtf-to-usdt sc-gqPbQI hNPUBP open-currency-select-button">
                                            <span class="sc-bMVAic itTpBY">
                                                <img width="25" height="25" src="{{ asset('assets/uploads/logo/favicon.png') }}" alt="@lang('GTF amount')" />
                                                <span class="sc-bMVAic itTpBY">
                                                    <span class="sc-exAgwC dFGAgQ token-symbol-container">GTF</span>
                                                </span>
                                                <svg width="12" height="7" viewBox="0 0 12 7" fill="none"
                                                    color="#48587B" class="sc-bAeIUo iHLDof">
                                                    <path d="M0.97168 1L6.20532 6L11.439 1" stroke="#AEAEAE"></path>
                                                </svg>
                                            </span>
                                        </button>

                                        <div class="usdt-to-gtf" style="display: none">
                                            <div class=" d-flex align-items-center">
                                                <img class="fvzzkg" src="{{ asset($themeTrue . 'img/icon/usdt.png') }}" alt="@lang('Exchange icon')" />
                                                <select
                                                    class="form-select"
                                                    name="wallet_type" id="wallet_type"
                                                    aria-label="Default select example"
                                                >
                                                    <option value="" selected disabled class="text-white bg-dark">{{trans('Please select wallet')}}</option>
                                                    <option value="balance" class="text-white bg-dark">{{trans('Main balance: '.$basic->currency_symbol.getAmount(auth()->user()->balance))}}</option>
                                                    <option value="interest_balance" class="text-white bg-dark">{{trans('Interest Balance: '.$basic->currency_symbol.getAmount(auth()->user()->interest_balance))}}</option>
                                                    <option value="referral_balance" class="bg-dark text-white">@lang('Referral Balance: '.$basic->currency_symbol.getAmount(auth()->user()->referral_balance))</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="exchangeIcon" class="sc-gqjmRU sc-jTzLTM sc-cSHVUG loDFqx"
                                style="flex-wrap: revert; position: relative;">
                                <div size="48" class="sc-rBLzX bmMWlQ" onclick="exchangeAction()">
                                    <img src="{{ asset($themeTrue . 'img/icon/ex-change.svg') }}" alt="@lang('Exchange icon')" />
                                </div>
                                <div class="css-16cxqow"
                                    style="position: absolute; right: 0px; top: 13px; cursor: pointer; padding: 2px 8px; border-radius: 8px;">
                                    <div class="css-vurnku">Limit order</div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div id="swap-currency-output" class="sc-iujRgT klLNRk">
                                <div class="sc-GMQeP cDdCcR">
                                    <div id="InputRow" class="sc-cLQEGU hAfotE">
                                        <div id="RightContentDiv" class="sc-daURTG isZsJa">
                                            <div class="sc-bXGyLb gffPBZ">
                                                <input id="usdt" class="sc-hMFtBS kfBjC token-amount-input" readonly
                                                    font-size="24px" inputmode="decimal" title="Token Amount"
                                                    autocomplete="off" autocorrect="off" type="number"
                                                    pattern="^[0-9]*[.,]?[0-9]*$" placeholder="0.0" minlength="1"
                                                    maxlength="79" spellcheck="false" value="">
                                            </div>
                                        </div>
                                        <button type="button" class="gtf-to-usdt sc-gqPbQI hNPUBP open-currency-select-button">
                                            <span class="sc-bMVAic itTpBY">
                                                <img class="fvzzkg" src="{{ asset($themeTrue . 'img/icon/usdt.png') }}" alt="@lang('Exchange icon')" />
                                                <span class="sc-bMVAic itTpBY">
                                                    <span class="sc-exAgwC dFGAgQ token-symbol-container">USDT</span>
                                                </span>
                                                <svg width="12" height="7" viewBox="0 0 12 7" fill="none"
                                                    color="#48587B" class="sc-bAeIUo iHLDof">
                                                    <path d="M0.97168 1L6.20532 6L11.439 1" stroke="#AEAEAE"></path>
                                                </svg>
                                            </span>
                                        </button>
                                        <button type="button" class="usdt-to-gtf sc-gqPbQI hNPUBP open-currency-select-button" style="display: none">
                                            <span class="sc-bMVAic itTpBY">
                                                <img width="25" height="25" src="{{ asset('assets/uploads/logo/favicon.png') }}" alt="@lang('GTF amount')" />
                                                <span class="sc-bMVAic itTpBY">
                                                    <span class="sc-exAgwC dFGAgQ token-symbol-container">GTF</span>
                                                </span>
                                                <svg width="12" height="7" viewBox="0 0 12 7" fill="none"
                                                    color="#48587B" class="sc-bAeIUo iHLDof">
                                                    <path d="M0.97168 1L6.20532 6L11.439 1" stroke="#AEAEAE"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sc-emmjRN fHbVTw">
                            <button type="submit" class="sc-gqjmRU gacWOr sc-eHgmQL sc-jWBwVP captOf ButtonLight">Confirm swap</button>
                        </div>
                    </form>
                </div>
                <div id="AdvancedSwapDetailsDropdown" class="sc-gbzWSY jnmYwu">
                    <div class="sc-ifAKCX dcxnAx"></div>
                </div>
            </div>
            <div class="row mb-md-3 mb-sm-2">
                <div class="col-md-6 mb-4 mb-md-0 refferal-information mt-md-5 mt-sm-4">
                    <div class="box">
                        <div class="img-box">
                            <img src="{{ asset($themeTrue . 'img/icon/handshake.png') }}" alt="@lang('handshake img')" />
                        </div>
                        <div>
                            <h4 class="golden-text">@lang('Conditions for participating')</h4>
                            <p><i>You must join the Golden Tiger, Diamond Plan, or GTF with a balance greater than 10,000 GTF.</i></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 refferal-information mt-md-5 mt-sm-2">
                    <div class="box">
                        <div class="img-box">
                            <img src="{{ asset($themeTrue . 'img/icon/deposit.png') }}" alt="@lang('Referral Bonus img')" />
                        </div>
                        <div>
                            <h4 class="golden-text">@lang('Total GTF balance')</h4>
                            <p>{{ number_format($gtfBalance) . ' GTF' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('user.add.staking') }}" method="post" enctype="multipart/form-data">
                @csrf
                <button class="gold-btn btn investNow" type="submit">@lang('Staking now')</button>
            </form>
            <div class="row mt-5">
                <div class="col">
                    <div class="header-text-full">
                        <h2>{{ trans('Staking History') }}</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="table-parent table-responsive">
                        <table class="table table-striped mb-5">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('SL')</th>
                                    <th scope="col">@lang('Plan')</th>
                                    <th scope="col">@lang('Return Interest')</th>
                                    <th scope="col">@lang('Received Amount')</th>
                                    <th scope="col">@lang('Upcoming Payment')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stakings as $key => $staking)
                                    <tr>
                                        <td>{{ loopIndex($stakings) + $key }}</td>
                                        <td>
                                            {{ number_format($staking->amount) }} GTF
                                        </td>
                                        <td>
                                            {{ getAmount($staking->profit) }} GTF
                                            {{ $staking->period == '-1' ? trans('For Lifetime') : 'per ' . trans($staking->point_in_text) }}
                                            <br>
                                            {{ $staking->capital_status == '1' ? '+ ' . trans('Capital') : '' }}
                                        </td>
                                        <td>
                                            {{ $staking->recurring_time }} x {{ $staking->profit }} =
                                            {{ getAmount($staking->recurring_time * $staking->profit) }}
                                            GTF
                                        </td>
                                        <td>
                                            @if ($staking->status == 1)
                                                <p id="counter{{ $staking->id }}" class="mb-2"></p>
                                                <script>
                                                    getCountDown("counter{{ $staking->id }}", {{ \Carbon\Carbon::parse($staking->afterward)->diffInSeconds() }});
                                                </script>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped bg-danger"
                                                        role="progressbar" style="width: {{ $staking->nextPayment }}"
                                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                        {{ $staking->nextPayment }}</div>
                                                </div>
                                            @else
                                                <span class="badge badge-success">@lang('Completed')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button disabled class="cancelStaking gold-btn gold-btn-custom"
                                                data-staking="{{ $staking->id }}">Cancel</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="100%">{{ trans('No Data Found!') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- INVEST-NOW MODAL -->
    <div class="modal fade addFundModal" id="stakingNowModal" tabindex="-1" data-bs-backdrop="static" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title golden-text" id="exampleModalLabel">@lang('Are you sure to cancel?')</h3>
                </div>
                <div class="modal-body">
                    <div class="form-block">
                        <form class="login-form" id="invest-form" action="{{ route('user.cancel-staking') }}"
                            method="post">
                            @csrf
                            <div class="signin ">
                                <input type="hidden" name="invest_id" class="staking-id">
                                <div class="d-flex btn-area mb-30 modal-footer border-top-0 p-0">
                                    <button type="submit" class="gold-btn">@lang('Yes')</button>
                                    <button type="button" data-bs-dismiss="modal" class="gold-btn" aria-label="Close">
                                        @lang('No')
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- INVEST-NOW MODAL -->
@endsection

@push('script')
    <script>
        var swapTypeUsdtToGtf = true; // USDT => GTF
        "use strict";
        (function($) {
            var price_gtf = {{ $price_gtf }};
            var gtfInterestBalance = {{ $gtfInterestBalance }};
            $(document).on('click', '.cancelStaking', function() {
                // $("#stakingNowModal").toggleClass("modal-open");
                var planModal = new bootstrap.Modal(document.getElementById('stakingNowModal'))
                planModal.show()
                let stakingId = $(this).data('staking');
                $('.staking-id').val(stakingId);
            });

            $('.token-amount-input').on('keydown', (event) => {
                var amount_gtf = $(event.target).val();
                if(amount_gtf >= gtfInterestBalance) {
                    $(event.target).val(gtfInterestBalance);
                    $('#usdt').val( (gtfInterestBalance * price_gtf).toFixed(2));
                    event.preventDefault();
                    return;
                }
                var usdt = (amount_gtf * price_gtf).toFixed(2);
                $('#usdt').val(usdt);
            })

        })(jQuery);

        function exchangeAction() {
            swapTypeUsdtToGtf = !swapTypeUsdtToGtf;
            if(swapTypeUsdtToGtf) {
            }
            $('.usdt-to-gtf').toggle();
            $('.gtf-to-usdt').toggle();
        }
    </script>

    @if (count($errors) > 0)
        <script>
            @foreach ($errors->all() as $key => $error)
                Notiflix.Notify.Failure("@lang($error)");
            @endforeach
        </script>
    @endif
@endpush


