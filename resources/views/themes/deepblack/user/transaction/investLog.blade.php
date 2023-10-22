@extends($theme.'layouts.user')
@section('title',trans('Invest'))
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

    <section class="transaction-history mt-5 pt-5">
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col">
                    <div class="header-text-full">
                        <h2>{{trans('Invest Plan')}}</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    @include($theme.'sections.investment', ['plans' => $data['plans'], 'templates' => $data['templates']])
                </div>
            </div>
        </div>

        <!-- Invest history -->

        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="header-text-full d-flex justify-content-between">
                        <h2>{{trans('Invest History')}}</h2>
                        @if ($checkOldInvest)
                            <button class="gold-btn btn">Combine Plan</button>
                        @endif
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
                                @forelse($investments as $key => $invest)
                                <tr>
                                    <td>{{loopIndex($investments) + $key}}</td>
                                    <td>
                                        {{trans(optional($invest->plan)->name)}}
                                        <br> {{getAmount($invest->amount).' '.trans($basic->currency)}}
                                    </td>
                                    <td>
                                        {{getAmount($invest->profit)}} {{trans($basic->currency)}}
                                        {{($invest->period == '-1') ? trans('For Lifetime') : 'per '. trans($invest->point_in_text)}}
                                        <br>
                                        {{($invest->capital_status == '1') ? '+ '.trans('Capital') :''}}
                                    </td>
                                    <td>
                                        {{$invest->recurring_time}} x {{ $invest->profit }} =  {{getAmount($invest->recurring_time*$invest->profit) }} {{trans($basic->currency)}}
                                    </td>
                                    <td>
                                        @if($invest->status == 1)
                                            <p id="counter{{$invest->id}}" class="mb-2"></p>
                                            <script>getCountDown("counter{{$invest->id}}", {{\Carbon\Carbon::parse($invest->afterward)->diffInSeconds()}});</script>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped bg-danger" role="progressbar"  style="width: {{$invest->nextPayment}}"  aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{$invest->nextPayment}}</div>
                                            </div>
                                        @else
                                            <span class="badge badge-success">@lang('Completed')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="cancelInvest gold-btn gold-btn-custom" data-invest="{{ $invest->id }}">Cancel</button>
                                    </td>
                                </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="100%">{{trans('No Data Found!')}}</td>
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
<div class="modal fade addFundModal" id="investNowModal" tabindex="-1" data-bs-backdrop="static"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title golden-text" id="exampleModalLabel">@lang('Are you sure to cancel?')</h3>
        </div>
        <div class="modal-body">
            <div class="form-block">
                <form class="login-form" id="invest-form" action="{{route('user.cancel-plan')}}" method="post">
                    @csrf
                    <div class="signin ">
                        <input type="hidden" name="invest_id" class="invest-id">
                        <div class="d-flex btn-area mb-30 modal-footer border-top-0 p-0">
                            <button type="submit" class="gold-btn">@lang('Yes')</button>
                            <button
                                type="button"
                                data-bs-dismiss="modal"
                                class="gold-btn"
                                aria-label="Close"
                            >
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
    "use strict";
    (function ($) {
        $(document).on('click', '.cancelInvest', function () {
            // $("#investNowModal").toggleClass("modal-open");
            var planModal = new bootstrap.Modal(document.getElementById('investNowModal'))
            planModal.show()
            let investId = $(this).data('invest');
            $('.invest-id').val(investId);
        });

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
