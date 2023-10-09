@extends($theme.'layouts.app')
@section('title',trans('Home'))

@section('content')
    <div class="inner-section">
        @include($theme.'partials.heroBanner')
        {{--    @include($theme.'sections.feature')--}}
        @include($theme.'sections.about-us')
        @include($theme.'sections.why-chose-us')
        @include($theme.'sections.investment')
        @include($theme.'sections.faq')
        @include($theme.'sections.referral')
        @include($theme.'sections.testimonial')
        {{--    @include($theme.'sections.how-it-work')--}}
        @include($theme.'sections.news-letter')
    </div>
@endsection
