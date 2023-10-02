@extends('admin.layouts.login')
@section('title','Admin Login')

@section('content')
    <div class="p-3">
        <div class="text-center">
            <img src=" {{getFile(config('location.logoIcon.path').'favicon.png')}}" alt="wrapkit">
        </div>
        <h2 class="mt-3 text-center">@lang('2FA Security')</h2>

        <form method="POST" action="{{ route('admin.post.two-fa') }}" aria-label="{{ __('2FA Security') }}">
            @csrf
            <div class="row mb-5">
                <div class="col-lg-12">
                    <input type="hidden" name="username" value="{{$tokenData->username}}">
                    <input type="hidden" name="password" value="{{$tokenData->password}}">
                    <div class="form-group">
                        <label class="text-dark" for="pwd">@lang('2FA Code')</label>
                        <input id="two_fa" type="text" class="form-control @error('two_fa') is-invalid @enderror" name="two_fa" required>
                        @error('two_fa')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-12 text-center">
                    <button type="submit" class="btn btn-block btn-dark">@lang('Verify')</button>
                </div>
            </div>
        </form>
    </div>
@endsection
