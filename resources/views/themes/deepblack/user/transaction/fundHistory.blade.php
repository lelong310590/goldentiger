@extends($theme.'layouts.user')
@section('title',trans('Fund History'))
@section('content')

<section class="transaction-history mt-5 pt-5">
    <div class="container-fluid">
       <div class="row">
          <div class="col">
             <div class="header-text-full mb-5">
                <h2>{{trans('Deposit')}}</h2>
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

       <form action="{{ route('user.fund-history.search') }}" method="get" class="mt-5">
          <div class="row select-transaction">
             <div class="col-md-6 col-lg-4">
                <div class="input-group mb-4">
                   <div class="img">
                      <img src="{{asset($themeTrue.'img/icon/edit.png')}}" alt="@lang('edit img')" />
                   </div>
                   <input
                      type="text"
                      name="name"
                      value="{{@request()->name}}"
                      class="form-control"
                      placeholder="@lang('Type Here')"
                   />
                </div>
             </div>
             <div class="col-md-6 col-lg-3">
                <div class="input-group mb-4">
                   <div class="img">
                      <img src="{{asset($themeTrue.'img/icon/chevron.png')}}" alt="@lang('chevron img')" />
                   </div>
                   <select
                        name="status"
                        class="form-select"
                        id="salutation"
                        aria-label="Default select example"
                   >
                        <option value="">@lang('All Payment')</option>
                        <option value="1"
                                @if(@request()->status == '1') selected @endif>@lang('Complete Payment')</option>
                        <option value="2"
                                @if(@request()->status == '2') selected @endif>@lang('Pending Payment')</option>
                        <option value="3"
                                @if(@request()->status == '3') selected @endif>@lang('Cancel Payment')</option>
                   </select>
                </div>
             </div>
             <div class="col-md-6 col-lg-3">
                <div class="input-group mb-4">
                    <div class="img">
                        <img src="{{asset($themeTrue.'img/icon/chevron.png')}}" alt="@lang('chevron img')" />
                    </div>
                    <input type="text" class="form-control" name="date_time"
                   id="datepicker" placeholder="@lang('Select a date')" autocomplete="off" readonly/>
                </div>
             </div>
             <div class="col-md-6 col-lg-2">
                <button type="submit" class="gold-btn search-btn mb-4">
                    @lang('Search')
                </button>
             </div>
          </div>
       </form>

       <div class="row">
          <div class="col">
             <div class="table-parent table-responsive">
                <table class="table table-striped mb-5">
                    <thead>
                        <tr>
                            <th scope="col">@lang('Transaction ID')</th>
                            <th scope="col">@lang('Gateway')</th>
                            <th scope="col">@lang('Amount')</th>
                            <th scope="col">@lang('Charge')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Time')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($funds as $data)
                            <tr>
                                <td>{{$data->transaction}}</td>
                                <td>@lang(optional($data->gateway)->name)</td>
                                <td>{{getAmount($data->amount)}} @lang($basic->currency)</td>
                                <td>{{getAmount($data->charge)}} @lang($basic->currency)</td>
                                <td>
                                    @if($data->status == 1)
                                        <span class="badge bg-success">@lang('Complete')</span>
                                    @elseif($data->status == 2)
                                        <span class="badge bg-warning">@lang('Pending')</span>
                                    @elseif($data->status == 3)
                                        <span class="badge bg-danger">@lang('Cancel')</span>
                                    @endif
                                </td>
                                <td>{{ dateTime($data->created_at, 'd M Y h:i A') }}</td>
                            </tr>

                        @empty
                            <tr class="text-center">
                                <td colspan="100%">{{__('No Data Found!')}}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $funds->appends($_GET)->links($theme.'partials.pagination') }}

             </div>
          </div>
       </div>
    </div>
</section>

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

