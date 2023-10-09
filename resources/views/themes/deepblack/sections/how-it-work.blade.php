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
