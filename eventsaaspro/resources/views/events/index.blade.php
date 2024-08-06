@extends('eventsaaspro::layouts.app')

{{-- Page title --}}

@section('content')

    <main>

    <!--Hero Banner-->
    <section class="cb_event-listing-banner" style="background-image: url({{config('filesystems.disks.s3.url').'banners/banner2.png'}});">
        <div class="container">
            <div class="row">
                <!-- <div class="col-1 d-lg-block d-none">
                    <img src="{{ asset('/storage/banners/riot-club.png') }}" alt="">
                </div> -->
                <div class="col-lg-5 col-12">
                    <h1>Events</h1>
                    <p>THE fun thing to do in Houston! A fun date, a night out with friends, or just to get out and meet new people. </p>
                    <!-- <ul class="banner-social-menu">
                        <li>
                            <a href="#"><img src="{{ asset('/storage/icons/Instagram - Original.png') }}" class="img-fluid" alt="..."></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{ asset('/storage/icons/Facebook - Original.png') }}" class="img-fluid" alt="..."></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{ asset('/storage/icons/Twitter - Original.png') }}" class="img-fluid" alt="..."></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{ asset('/storage/icons/YouTube - Original.png') }}" class="img-fluid" alt="..."></a>
                        </li>
                    </ul> -->
                </div>
            </div>
        </div>
    </section>
    <!--Hero Banner-->

    <!-- Upcomming Events -->
    <section class="cb_section cb_event-listing">
        <div class="container">

            <div class="row">
                <div class="col-12 d-flex align-items-center justify-content-between">
                    <h2>Events</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @component('eventsaaspro::skeleton.event')
                    @endcomponent
                </div>
            </div>

        </div>

        <router-view
            :date_format="{{ json_encode(
                [
                    'vue_date_format' => format_js_date(),
                    'vue_time_format' => format_js_time(),
                ],
                JSON_HEX_APOS,
            ) }}">
        </router-view>
    </section>

    </main>
@endsection

@section('javascript')
    <script>
        var path = {!! json_encode($path, JSON_HEX_TAG) !!};
        var events_slider = false;
    </script>
    <script type="text/javascript" src="{{ eventmie_asset('js/events_listing.js') }}"></script>


@stop
