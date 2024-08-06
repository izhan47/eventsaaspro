<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}" {!! is_rtl() ? 'dir="rtl"' : '' !!}>

<head>

    @include('eventsaaspro::layouts.meta')

    @include('eventsaaspro::layouts.favicon')

    @include('eventsaaspro::layouts.include_css')

    @yield('stylesheet')
</head>

<body class="home" {!! is_rtl() ? 'dir="rtl"' : '' !!}>

    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
        your browser</a> to improve your experience.</p>
    <![endif]-->

    {{-- Ziggy directive --}}
    @routes

    {{-- Main wrapper --}}
    <div id="eventmie_app">

        @include('eventsaaspro::layouts.header')

        @php
            $no_breadcrumb = ['eventsaaspro.welcome', 'eventsaaspro.events_index', 'eventsaaspro.events_show', 'eventsaaspro.login', 'eventsaaspro.register', 'eventsaaspro.register_show', 'eventsaaspro.password.request', 'eventsaaspro.password.reset', 'eventsaaspro.o_dashboard', 'eventsaaspro.myevents_index', 'eventsaaspro.myevents_index', 'eventsaaspro.myevents_form', 'eventsaaspro.obookings_index', 'eventsaaspro.event_earning_index', 'eventsaaspro.tags_form', 'eventsaaspro.myvenues.index', 'eventsaaspro.venues.show', 'eventsaaspro.ticket_scan', 'eventsaaspro.clubs_index', 'eventsaaspro.checkout_index', 'eventsaaspro.comedians_payout_index', 'eventsaaspro.page', 'eventsaaspro.contact'];
        @endphp
        @if (!in_array(Route::currentRouteName(), $no_breadcrumb))
            @include('eventsaaspro::layouts.breadcrumb')
        @endif

        <section class="db-wrapper">

            {{-- page content --}}
            @yield('content')

            {{-- set progress bar --}}
            <vue-progress-bar></vue-progress-bar>
        </section>

        @include('eventsaaspro::layouts.footer')

    </div>
    <!--Main wrapper end-->

    @include('eventsaaspro::layouts.include_js')

    {{-- Page specific javascript --}}
    @yield('javascript')

</body>

</html>
