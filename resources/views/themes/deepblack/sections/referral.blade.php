@if(isset($templates['news-letter-referral'][0]) && 0 < count($referralLevel) && $newsLetterReferral = $templates['news-letter-referral'][0])
    <section class="commission-section-wrapper">
        <div class="container commission-section">
            <div class="row align-items-end">
                <div class="col-12 col-md-6">
                    <div class="header-text">
                        <h2>@lang(@$newsLetterReferral->description->sub_title)</h2>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <img src="{{asset($themeTrue.'img/fiat-money.png')}}" class="user-image" alt="@lang('profile')"/>
                </div>
            </div>
            <div class="referral-wrapper">
                <div class="row">
                    @foreach($referralLevel as $k => $data)
                        <div class="col-md-6 col-lg-4 mb-5">
                            <div
                                class="box box-commission box{{$k+1}} {{(session()->get('rtl') == 1) ? 'isRtl': 'noRtl'}}"
                                {{--                                data-aos="zoom-in"--}}
                                {{--                                data-aos-duration="800"--}}
                                {{--                                data-aos-anchor-placement="center-bottom"--}}
                            >
                                <h2>{{$data->percent}}%</h2>
                                <h4>@lang('level') {{$data->level}}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        @if(isset($templates['how-it-work'][0]) && $howItWork = $templates['how-it-work'][0])
            @php
                $totalContents = $contentDetails['how-it-work'];
            @endphp
            <!-- how it works -->
            <section class="how-it-works @if(session()->get('rtl') == 1) rtl @endif">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="header-text text-center">
                                <h2>@lang(@$howItWork->description->title)</h2>
                                <p>@lang(@$howItWork->description->short_details)</p>
                            </div>
                        </div>
                    </div>

                    <div class="how-wrapper">
                        <img src="{{asset($themeTrue.'img/hand-coin.png')}}" class="user-image" alt="@lang('profile')"/>
                        <div class="row">
                            @foreach($totalContents as $k =>  $item)
                                <div class="col-6 col-md-3">
                                    <div
                                        class="box box-{{$k+1}}"
                                        {{--                                    data-aos="fade-up"--}}
                                        {{--                                    data-aos-duration="800"--}}
                                        {{--                                    data-aos-anchor-placement="center-bottom"--}}
                                    >
                                        {{--                                    <div class="img">--}}
                                        {{--                                        <img--}}
                                        {{--                                            src="{{getFile(config('location.content.path').@$item->content->contentMedia->description->image)}}"--}}
                                        {{--                                            class="img-center" alt="@lang('how it work image')"/>--}}
                                        {{--                                    </div>--}}
                                        <div class="text">
                                            <h4 class="golden-text">@lang(@$item->description->title)</h4>
                                            <div class="how-text">@lang(@$item->description->short_description)</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

        @endif

    </section>
@endif
