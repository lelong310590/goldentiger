<!-- PAGE-BANNER -->
<style>
    .page-banner {
        background-image: url({{getFile(config('location.logo.path').'banner.png')}});
    }
</style>

@if(!request()->routeIs('home'))
    <!-- page banner -->
    <section class="page-banner">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2>@yield('title')</h2>
                </div>
            </div>
        </div>
    </section>
    <!-- /PAGE-BANNER -->
@endif
