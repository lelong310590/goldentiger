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
                        <div class="card-body bg-dark">
                            <div class="row ">
                                <div class="col-md-12">
                                    <h3 class="title text-center">{{trans('Please follow the instruction below')}}</h3>
                                    <p class="text-center mt-2 ">{{trans('You have requested to deposit')}}  <b class="text--base">{{getAmount($order->amount)}}
                                            {{$basic->currency}}</b> , {{trans('Please pay')}}
                                        <b class="text--base">{{getAmount($order->final_amount)}} {{$order->gateway_currency}}</b>  {{trans('for successful payment')}}
                                    </p>

                                    @dump(optional($order->gateway))

                                    <p class="mt-2 ">
                                        <p style="text-align: center; ">Please send <b>USDT-BEP20</b> to this address with exactly your total deposit amount:</p><p style="text-align: center; "><img src="https://api.qrserver.com/v1/create-qr-code/?color=fae15e&amp;bgcolor=222222&amp;data=0xf2eb2a8eeac9a9a12d81aa66f4de343c3c9d795e&amp;qzone=1&amp;margin=0&amp;size=200x200&amp;ecc=L" style="width: 200px;"><br></p><p style="text-align: center; ">0xf2eb2a8eeac9a9a12d81aa66f4de343c3c9d795e</p><p style="text-align: center; ">After your transaction confirmed successfully please provide these following info:</p>
                                        <?php //echo optional($order->gateway)->note; ?>
                                    </p>


                                    <form action="" method="post" enctype="multipart/form-data"
                                          class="form-row  preview-form">
                                        @csrf
                                        @if(optional($order->gateway)->parameters)
                                            @foreach($order->gateway->parameters as $k => $v)
                                                @if($v->type == "text")
                                                    <div class="col-md-12 mt-2">
                                                        <div class="form-group  ">
                                                            <label>{{trans($v->field_level)}} @if($v->validation == 'required') <span class="text--danger">*</span>  @endif </label>
                                                            <input type="text" name="{{$k}}" style="color: #b38728;"  class="form-control bg-transparent" @if($v->validation == "required") required @endif>
                                                            @if ($errors->has($k))
                                                                <span class="text--danger">{{ trans($errors->first($k)) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @elseif($v->type == "textarea")
                                                    <div class="col-md-12 mt-2">
                                                        <div class="form-group">
                                                            <label>{{trans($v->field_level)}} @if($v->validation == 'required') <span class="text--danger">*</span>  @endif </label>
                                                            <textarea name="{{$k}}" style="color: #b38728;"  class="form-control bg-transparent" rows="3" @if($v->validation == "required") required @endif></textarea>
                                                            @if ($errors->has($k))
                                                                <span class="text--danger">{{ trans($errors->first($k)) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @elseif($v->type == "file")
                                                    <div class="col-md-12 mt-2">
                                                        <label>{{trans($v->field_level)}} @if($v->validation == 'required') <span class="text--danger">*</span>  @endif </label>

                                                        <div class="form-group">
                                                            <div class="fileinput fileinput-new " data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail withdraw-thumbnail"
                                                                     data-trigger="fileinput">
                                                                    <img class="w-150px"
                                                                         src="{{ getFile(config('location.default')) }}"
                                                                         alt="...">
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail wh-200-150 "></div>

                                                                <div class="img-input-div">
                                                                <span class="btn btn-success btn-file">
                                                                    <span
                                                                        class="fileinput-new "> @lang('Select') {{$v->field_level}}</span>
                                                                    <span
                                                                        class="fileinput-exists"> @lang('Change')</span>
                                                                    <input type="file" name="{{$k}}" accept="image/*"
                                                                           @if($v->validation == "required") required @endif>
                                                                </span>
                                                                    <a href="#" class="btn btn-danger fileinput-exists"
                                                                       data-dismiss="fileinput"> @lang('Remove')</a>
                                                                </div>

                                                            </div>
                                                            @if ($errors->has($k))
                                                                <br>
                                                                <span
                                                                    class="text--danger">{{ __($errors->first($k)) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif


                                        <div class="col-md-12 ">
                                            <div class=" form-group">
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
