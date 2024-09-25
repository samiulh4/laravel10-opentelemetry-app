<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>@yield('title', 'Laravel OpenTelemetry App')</title>
    <link rel="shortcut icon" href="{{ asset('assets/web/favicon.png') }}" />

    <meta name="author" content="" />
    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap5" />

    @include('partials.web.styles')
    @stack('css')
    @yield('styles')
</head>

<body>
    @include('partials.web.site-mobile-menu')
    @include('partials.web.nav')
    {{-- @include('partials.web.hero-section') --}}
    <div class="section search-result-wrap">
        <div class="container">
            <div class="row posts-entry">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div><!--./section-->
    @include('partials.web.footer')
    @include('partials.web.preloader')
    @include('partials.web.scripts')
    @stack('js')
    @yield('scripts')
</body>

</html>
