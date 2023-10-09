@if(isset($templates['hero'][0]) && $hero = $templates['hero'][0])
    @push('style')
        <style>
            .home-banner {
                background-image: url({{asset($themeTrue.'img/hero-bg.png')}});
                background-size: cover;
                background-position: bottom center;
            }
        </style>
    @endpush
    <!-- home banner -->
    <section class="home-banner">
        <div class="container h-100">
            <div class="row h-100 pt-5 align-items-center justify-content-around">
                <div class="col-lg-6">
                    <div class="text-box">
                        <h1>
                            @lang(@$hero['description']->title) <br />
                            <span>@lang(@$hero['description']->sub_title)</span>
                        </h1>
                        <p>@lang(@$hero['description']->short_description)</p>
                        <a href="{{@$hero->templateMedia()->button_link}}" class="gold-btn d-flex justify-content-center align-items-center">
                            @lang(@$hero['description']->button_name)
                        </a>
                    </div>
                </div>
                <div
                class="col-lg-6 text-right d-none d-lg-block animate__animated animate__bounce animate__delay-2s"
                >
                <img src="{{asset($themeTrue.'img/world.png')}}" alt="@lang('hero image')" class="img-fluid" />
                </div>
            </div>
        </div>
        @if(isset($contentDetails['feature']))
            @if(0 < count($contentDetails['feature']))
                <section class="feature-section">
                    <h2 class="text-center feature-title">
                        Golden Tiger Fund Investments Plan for Worldwide<br/>
                        An innovate capital raising solutions company
                    </h2>
                    <div class="container">
                        <div class="row">
                            @foreach($contentDetails['feature'] as $feature)
                                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                                    <div
                                        class="box"
{{--                                        data-aos="fade-up"--}}
{{--                                        data-aos-duration="800"--}}
{{--                                        data-aos-anchor-placement="center-bottom"--}}
                                    >
{{--                                        <div class="img-box">--}}
{{--                                            <img src="{{getFile(config('location.content.path').@$feature->content->contentMedia->description->image)}}" alt="@lang('feature image')" />--}}
{{--                                        </div>--}}
                                        <h4>@lang(@$feature->description->title)</h4>
                                        <h2>@lang(@$feature->description->information)</h2>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="technology-wrapper d-flex align-items-center justify-content-between">
                            <div class="technology-item d-flex align-items-center">
                                <img src="{{asset($themeTrue.'img/icon/medical-cross.png')}}" class="user-image" alt="@lang('profile')" width="25" height="25"/>
                                <span>Technology</span>
                            </div>
                            <div class="technology-item">
                                <img src="{{asset($themeTrue.'img/icon/medical-cross.png')}}" class="user-image" alt="@lang('profile')" width="25" height="25"/>
                                <span>Healthcare</span>
                            </div>
                            <div class="technology-item">
                                <img src="{{asset($themeTrue.'img/icon/power.png')}}" class="user-image" alt="@lang('profile')" width="25" height="25"/>
                                <span>Renewable Energy</span>
                            </div>
                            <div class="technology-item">
                                <img src="{{asset($themeTrue.'img/icon/medical-cross.png')}}" class="user-image" alt="@lang('profile')" width="25" height="25"/>
                                <span>Consumer Goods</span>
                            </div>
                            <div class="technology-item">
                                <img src="{{asset($themeTrue.'img/icon/medical-cross.png')}}" class="user-image" alt="@lang('profile')" width="25" height="25"/>
                                <span>Real Estate</span>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endif

    </section>
@endif


