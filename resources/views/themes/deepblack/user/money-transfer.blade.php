@extends($theme.'layouts.user')
@section('title',__($page_title))

@section('content')

<!-- Fund history -->
<section class="transaction-history profile-setting mt-5 pt-5">
    <div class="container-fluid">
       <div class="row">
          <div class="col">
             <div class="header-text-full">
                <h2>{{trans($page_title)}}</h2>
             </div>
          </div>
       </div>

        <div class="edit-area">
            <form class="form-row" action="" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label for="email" class="golden-text">@lang('Receiver Email Address')</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email" value="{{old('email')}}" placeholder="@lang('Receiver Email Address')"
                        />
                        @error('email')
                            <div class="error text-danger">@lang($message) </div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label for="amount" class="golden-text">@lang('Amount')</label>
                        <input
                            type="text"
                            id="amount"
                            class="form-control"
                            name="amount" value="{{old('amount')}}" placeholder="@lang('Enter Amount')"  onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                        />
                        @error('amount')
                            <div class="error text-danger">@lang($message) </div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label for="" class="golden-text"
                            >@lang('Select Wallet')</label
                        >
                        <select
                            class="form-select"
                            name="wallet_type" id="wallet_type"
                            aria-label="Default select example"
                        >
                            <option value="" selected disabled class="text-white bg-dark">{{trans('Select Wallet')}}</option>
                            <option value="balance" class="text-white bg-dark">{{trans('Main balance: '.$basic->currency_symbol.getAmount(auth()->user()->balance))}}</option>
                            <option value="interest_balance" class="text-white bg-dark">{{trans('Interest Balance: '.$basic->currency_symbol.getAmount(auth()->user()->interest_balance))}}</option>
                            <option value="referral_balance" class="bg-dark text-white">@lang('Referral Balance: '.$basic->currency_symbol.getAmount(auth()->user()->referral_balance))</option>
                            <option value="gtf_interest_balance" class="bg-dark text-white">@lang('GTF Interest Balance: '.getAmount(auth()->user()->gtf_interest_balance))</option>
                        </select>
                        @error('wallet_type')
                            <div class="error text-danger">@lang($message) </div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label for="password" class="golden-text">@lang('Enter Password')</label>
                        <input
                            type="password"
                            id="password"
                            class="form-control"
                            name="password" value="{{old('password')}}" placeholder="@lang('Your Password')"
                            required
                        />
                        @error('password')
                            <div class="error text-danger">@lang($message) </div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label for="two_fa" class="golden-text">@lang('2FA Code')</label>
                        <p>If you have not activated 2FA Security yet. <a href="{{route('user.twostep.security')}}">Click here to</a> active it!</p>
                        <input
                            type="text"
                            id="two_fa"
                            class="form-control"
                            name="two_fa" value="{{old('two_fa')}}" placeholder="@lang('2FA Code')"
                            required
                        />
                        @error('two_fa')
                        <div class="error text-danger">@lang($message) </div>
                        @enderror
                    </div>

                </div>
                <button type="submit" class="gold-btn">@lang('Submit')</button>
            </form>
        </div>

    </div>
 </section>



@endsection

