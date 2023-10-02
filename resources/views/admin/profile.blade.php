@extends('admin.layouts.app')

@section('title')
    @lang('profile')
@endsection


@section('content')

    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3"><i class="icon-user"></i> @lang('Profile Setting')</h4>
                        <form action="" method="post" class="form-body file-upload" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="form-row justify-content-between">
                                <div class="col-sm-6 col-md-3">
                                    <div class="image-input ">
                                        <label for="image-upload" id="image-label"><i class="fas fa-upload"></i></label>
                                        <input type="file" name="image" placeholder="" id="image">
                                        <img id="image_preview_container" class="preview-image"
                                             src="{{ getFile(config('location.admin.path').$admin->image) }}"
                                             alt="preview image">
                                    </div>
                                    @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-sm-6 col-md-8">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Name') <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control form-control-lg"
                                                       value="{{$admin->name}}" placeholder="@lang('Enter Name')">

                                                @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Username') <span class="text-danger">*</span></label>
                                                <input type="text" name="username" class="form-control"
                                                       value="{{$admin->username}}"
                                                       placeholder="@lang('Enter Username')">

                                                @error('username')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Email Address') <span class="text-danger">*</span></label>
                                                <input type="text" name="email" class="form-control"
                                                       value="{{$admin->email}}"
                                                       placeholder="@lang('Enter Email Address')">


                                                @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Phone Number') <span class="text-danger">*</span></label>
                                                <input type="text" name="phone" class="form-control"
                                                       value="{{$admin->phone}}"
                                                       placeholder="@lang('Enter Phone Number')">

                                                @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>@lang('Address') <span
                                                        class="text-muted text-sm">{{trans('(optional)')}}</span></label>
                                                <textarea name="address" class="form-control" rows="3"
                                                          placeholder="@lang('Your Address')">{{$admin->address}}</textarea>

                                                @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="text-right">
                                                <button type="submit"
                                                        class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">{{trans('Submit')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @if($admin->two_fa)
                <div class="col-lg-6 col-md-6 mb-3">
                    <div class="card text-center py-2">
                        <div class="card-header">
                            <h3 class="card-title">@lang('Two Factor Authenticator')</h3>
                        </div>
                        <div class="card-body">
                            <div class="box refferal-link">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        value="{{$secret}}"
                                        class="form-control"
                                        id="referralURL"
                                        readonly
                                    />
                                    <button class="gold-btn copytext" id="copyBoard" onclick="copyFunction()"><i
                                            class="fa fa-copy me-1"></i>@lang('copy code')</button>
                                </div>
                            </div>

                            <div class="form-group mx-auto text-center py-4">
                                <img class="mx-auto" src="{{$qrCodeUrl}}">
                            </div>

                            <div class="form-group mx-auto text-center">
                                <a href="javascript:void(0)" class="btn btn-bg btn-lg btn-primary"
                                   data-toggle="modal"
                                   data-target="#disableModal">@lang('Disable Two Factor Authenticator')</a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-6 col-md-6 mb-3">
                    <div class="card text-center py-2">
                        <div class="card-header">
                            <h3 class="card-title">@lang('Two Factor Authenticator')</h3>
                        </div>
                        <div class="card-body">
                            <div class="box refferal-link">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        value="{{$secret}}"
                                        class="form-control"
                                        id="referralURL"
                                        readonly
                                    />
                                    <button class="gold-btn copytext" id="copyBoard" onclick="copyFunction()"><i
                                            class="fa fa-copy me-1"></i>@lang('copy code')</button>
                                </div>
                            </div>

                            <div class="form-group mx-auto text-center py-4">
                                <img class="mx-auto" src="{{$qrCodeUrl}}">
                            </div>

                            <div class="form-group mx-auto text-center">
                                <a href="javascript:void(0)" class="btn btn-bg btn-lg btn-primary waves-effect waves-light btn-rounded"
                                   data-toggle="modal"
                                   data-target="#enableModal">@lang('Enable Two Factor Authenticator')</a>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            <div class="col-lg-6 col-md-6 mb-3">
                <div class="card text-center py-2">
                    <div class="card-header">
                        <h3 class="card-title pt-2">@lang('Google Authenticator')</h3>
                    </div>
                    <div class="card-body">

                        <h6 class="text-uppercase my-1">@lang('Use Google Authenticator to Scan the QR code  or use the code')</h6>

                        <p class="p-5">@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.')</p>
                        <a class="btn btn-bg btn-md btn-primary waves-effect waves-light btn-rounded"
                           href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                           target="_blank">@lang('DOWNLOAD APP')</a>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--Enable Modal -->
    <div id="enableModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content form-block">
                <div class="modal-header">
                    <h4 class="modal-title golden-text">@lang('Verify Your OTP')</h4>
                    <button
                        type="button"
                        data-dismiss="modal"
                        class="btn-close"
                        aria-label="Close"
                    >
                        <img src="{{asset($themeTrue.'img/icon/cross.png')}}" alt="@lang('cross img')"/>
                    </button>

                </div>
                <form action="{{route('admin.twoStepEnable')}}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <input type="hidden" name="key" value="{{$secret}}">
                            <input type="text" class="form-control" name="code"
                                   placeholder="@lang('Enter Google Authenticator Code')">
                        </div>

                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block">@lang('Verify')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Disable Modal -->
    <div id="disableModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content form-block">
                <div class="modal-header">
                    <h4 class="modal-title golden-text">@lang('Verify Your OTP to Disable')</h4>
                    <button
                        type="button"
                        data-dismiss="modal"
                        class="btn-close"
                        aria-label="Close"
                    >
                        <img src="{{asset($themeTrue.'img/icon/cross.png')}}" alt="@lang('cross img')"/>
                    </button>
                </div>
                <form action="{{route('admin.twoStepDisable')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" name="code"
                                   placeholder="@lang('Enter Google Authenticator Code')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn waves-effect waves-light btn-rounded btn-danger btn-block" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block">@lang('Verify')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function (e) {
            "use strict";
            $('#image').change(function () {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#image_preview_container').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });

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
