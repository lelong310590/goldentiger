{{-- @extends($extend_blade) --}}
@extends($theme.'layouts.app')
@section('title',trans('Plan'))

@section('content')
    <div class="inner-section">
        @include($theme.'sections.investment')
        @include($theme.'sections.deposit-withdraw')
        @include($theme.'sections.faq')
        @include($theme.'sections.we-accept')
    </div>
@endsection

