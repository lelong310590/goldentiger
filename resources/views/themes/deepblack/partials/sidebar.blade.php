<div id="sidebar">
    <a class="navbar-brand golden-text" href="{{route('home')}}">{{config('basic.site_title')}}</a>
    <ul class="pb-4">
       <!-- list item -->
       <li class="{{menuActive('user.home')}}">
          <a href="{{route('user.home')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/layout.png')}}" alt="@lang('Dashboard')"/>@lang('Dashboard')
          </a>
       </li>
       <li class="{{menuActive(['user.invest-plan'])}}">
         <a href="{{route('user.invest-plan')}}" class="sidebar-link">
            <img src="{{asset($themeTrue.'img/icon/pay-history.png')}}" alt="@lang('plan')"/>@lang('Plan')
         </a>
       </li>
        <li class="{{menuActive(['user.invest-history'])}}">
          <a href="{{route('user.invest-history')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/growth-graph.png')}}" alt="@lang('invest history ')"/>@lang('invest history')
          </a>
        </li>
       <li class="{{menuActive(['user.addFund', 'user.addFund.confirm'])}}">
          <a href="{{route('user.addFund')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/money-bag.png')}}" alt="@lang('Add Fund')"/>@lang('Add Fund')
          </a>
       </li>
       <li class="{{menuActive(['user.staking', 'user.add.staking'])}}">
          <a href="{{route('user.staking')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/padlock.png')}}" alt="@lang('Staking')"/>@lang('Staking')
          </a>
       </li>
       <li class="{{menuActive(['user.fund-history', 'user.fund-history.search'])}}">
          <a href="{{route('user.fund-history')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/fund.png')}}" alt="@lang('Fund History')"/>@lang('Fund History')
          </a>
       </li>
       <li class="{{menuActive(['user.money-transfer'])}}">
          <a href="{{route('user.money-transfer')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/money-transfer.png')}}" alt="@lang('transfer')"/>@lang('transfer')
          </a>
       </li>
       <li class="{{menuActive(['user.transaction', 'user.transaction.search'])}}">
          <a href="{{route('user.transaction')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/transaction.png')}}" alt="@lang('transaction')"/>@lang('transaction')
          </a>
       </li>
       <li class="{{menuActive(['user.payout.money','user.payout.preview'])}}">
          <a href="{{route('user.payout.money')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/payout.png')}}" alt="@lang('payout')"/>@lang('payout')
          </a>
       </li>
       <li class="{{menuActive(['user.payout.history','user.payout.history.search'])}}">
          <a href="{{route('user.payout.history')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/pay-history.png')}}" alt="@lang('payout history')"/>@lang('payout history')
          </a>
       </li>
       <li class="{{menuActive(['user.referral'])}}">
          <a href="{{route('user.referral')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/refferal.png')}}" alt="@lang('my referral')"/>@lang('my referral')
          </a>
       </li>
       <li class="{{menuActive(['user.referral.bonus', 'user.referral.bonus.search'])}}">
          <a href="{{route('user.referral.bonus')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/bonus.png')}}" alt="@lang('referral bonus')"/>@lang('referral bonus')
          </a>
       </li>
       <li class="{{menuActive(['user.profile'])}}">
          <a href="{{route('user.profile')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/setting.png')}}" alt="@lang('profile settings')"/>@lang('profile settings')
          </a>
       </li>
       <li class="{{menuActive(['user.ticket.list', 'user.ticket.create', 'user.ticket.view'])}}">
          <a href="{{route('user.ticket.list')}}" class="sidebar-link">
             <img src="{{asset($themeTrue.'img/icon/support.png')}}" alt="@lang('support ticket')"/>@lang('support ticket')
          </a>
       </li>
       <li class="">
          <div id="google_translate_element" style="padding:1em;"></div>
       </li>
    </ul>
 </div>
