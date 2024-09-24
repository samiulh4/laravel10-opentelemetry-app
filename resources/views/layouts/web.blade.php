<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <title>@yield('title', 'Laravel Opentelemetry App')</title>
    <link rel="shortcut icon" href="{{ asset('assets/web/favicon.png') }}" />

    <meta name="author" content="" />
    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap5" />
    
    @include('partials.web.styles')    
    @stack('css')
    @yield('styles')
</head>

<body>

    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close">
                <span class="icofont-close js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>

    @include('partials.web.nav')
    {{-- @include('partials.web.hero-section') --}}


    <div class="section search-result-wrap">
        <div class="container">
            <div class="row posts-entry">
                <div class="col-lg-8">
                    @yield('content')
                </div>
                <div class="col-lg-4 sidebar">
                    @include('partials.web.search-form')
                    @include('partials.web.popular-posts')
                    @include('partials.web.sidebar-categories')
                    @include('partials.web.sidebar-tags')
                </div><!--./sidebar-->
            </div>
        </div>
    </div>

    @include('partials.web.footer')

    <!-- Preloader -->
    <div id="overlayer"></div>
    <div class="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    @include('partials.web.scripts')
    @stack('js')
    @yield('scripts')
</body>

</html>