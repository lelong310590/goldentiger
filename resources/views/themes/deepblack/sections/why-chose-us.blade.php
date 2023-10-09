@if(isset($templates['why-chose-us'][0]) && $whyChoseUs = $templates['why-chose-us'][0])
    <!-- why choose us start -->
    <section class="choose-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="header-text text-center">
                        <h2>Why chose <span>GTF</span></h2>
                        <h4>
                            Let get on the boat with us to invest in these potential industries
                        </h4>
                    </div>
                </div>
            </div>

            @if(isset($contentDetails['why-chose-us']))
                <div class="chose-wrapper">
                    <img src="{{asset($themeTrue.'img/coin.png')}}" alt="coin" class="img-fluid">
                    <div class="row">
                        @foreach($contentDetails['why-chose-us'] as $item)
                            <div class="col-6 col-md-4 mb-4">
                                <div
                                    class="box"
                                    {{--                                data-aos="fade-up"--}}
                                    {{--                                data-aos-duration="800"--}}
                                    {{--                                data-aos-anchor-placement="center-bottom"--}}
                                >
                                    {{--                                <div class="img">--}}
                                    {{--                                    <img class="img-center"--}}
                                    {{--                                         src="{{getFile(config('location.content.path').@$item->content->contentMedia->description->image)}}"--}}
                                    {{--                                         alt="@lang('why-choose-us image')"/>--}}
                                    {{--                                </div>--}}
                                    <div class="text">
                                        <h5>Our</h5>
                                        <h4 class="golden-text">@lang(@$item->description->title)</h4>
                                        {{--                                    <p>@lang(@$item->description->information)</p>--}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- why choose us end -->
@endif
