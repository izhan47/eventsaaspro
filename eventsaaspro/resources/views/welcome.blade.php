@extends('eventsaaspro::layouts.app')

@section('title') @lang('eventsaaspro-pro::em.home') @endsection

@section('content')
    @php
        $perPage = 3;
    @endphp
    <!--Hero Banner-->
    <section class="cb_hero-banner" style="background-image: url({{config('filesystems.disks.s3.url').'banners/home-banner.png'}});">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-12">
                    <h1>Laugh more, worry less</h1>
                    <p>Riot Comedy Club 2010 Waugh Dr Houston, TX 77006</p>
                </div>
            </div>
        </div>
    </section>
    <!--Hero Banner-->

    <!-- Events Near You -->
    <!-- @if ($events_nearby->isNotEmpty())
        <section class="cb_section cb_events">
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-5 d-md-flex d-block align-items-center justify-content-between">
                        <h2>All events</h2>
                        <a href="{{ route('eventsaaspro.events_index') }}" class="view-all-btn">View All Events <i class="fa fa-long-arrow-right ms-2"></i></a>
                    </div>
                </div>

                <event-listing :events="{{ json_encode($events_nearby, JSON_HEX_APOS) }}"
                    :currency="{{ json_encode($currency, JSON_HEX_APOS) }}" :item_count="{{ 3 }}"
                    :date_format="{{ json_encode(
                        [
                            'vue_date_format' => format_js_date(),
                            'vue_time_format' => format_js_time(),
                        ],
                        JSON_HEX_APOS,
                    ) }}">
                </event-listing>

            </div>
        </section>
    @endif -->

    <!-- Featured Events -->

    @if ($featured_events->isNotEmpty())
        <section class="cb_section cb_events">
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-5 d-md-flex d-block align-items-center justify-content-between">
                        <h2 >@lang('eventsaaspro-pro::em.featured_events')</h2>
                        <!-- href="{{ route('eventsaaspro.events_index') }}" -->
                        <a href="#" class="view-all-btn">View All Events <i class="fa fa-long-arrow-right ms-2"></i></a>
                    </div>
                </div>

                <event-listing :events="{{ json_encode($featured_events, JSON_HEX_APOS) }}"
                    :currency="{{ json_encode($currency, JSON_HEX_APOS) }}" :item_count="{{ 3 }}"
                    :date_format="{{ json_encode(
                        [
                            'vue_date_format' => format_js_date(),
                            'vue_time_format' => format_js_time(),
                        ],
                        JSON_HEX_APOS,
                    ) }}">
                </event-listing>

            </div>
        </section>
    @endif

    <!-- Events -->
    @if ($events->isNotEmpty())
        <section class="cb_section cb_events">
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-5 d-md-flex d-block align-items-center justify-content-between">
                        <h2>All Events</h2>
                        <a href="{{ route('eventsaaspro.events_index') }}" class="view-all-btn">View All Events <i class="fa fa-long-arrow-right ms-2"></i></a>
                    </div>
                </div>

                <event-listing :events="{{ json_encode($events, JSON_HEX_APOS) }}"
                    :currency="{{ json_encode($currency, JSON_HEX_APOS) }}" :item_count="{{ 3 }}"
                    :date_format="{{ json_encode(
                        [
                            'vue_date_format' => format_js_date(),
                            'vue_time_format' => format_js_time(),
                        ],
                        JSON_HEX_APOS,
                    ) }}">
                </event-listing>

            </div>
        </section>
    @endif

    <!-- Featured Comedian -->
    <section class="cb_section cb_comedians d-none">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-5 d-md-flex d-block align-items-center justify-content-between">
                    <h2>Featured Comedians</h2>
                    <a href="#" class="view-all-btn">View All Comedians <i class="fa fa-long-arrow-right ms-2"></i></a>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-3 col-md-6 col-12">
                    <div class="comedian-card">
                        <div class="image-box">
                            <img src="{{ asset('/storage/comedian.png') }}" class="img-fluid" alt="...">
                        </div>
                        <div class="text-center">
                            <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 24px; line-height: 150%; color: #000000; margin: 18px 0 0 0;">Steve Trevino</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12 comedian-card">
                    <div class="image-box">
                        <img src="{{ asset('/storage/comedian.png') }}" class="img-fluid" alt="...">
                    </div>
                    <div class="text-center">
                        <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 24px; line-height: 150%; color: #000000; margin: 18px 0 0 0;">Steve Trevino</h3>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12 comedian-card">
                    <div class="image-box">
                        <img src="{{ asset('/storage/comedian.png') }}" class="img-fluid" alt="...">
                    </div>
                    <div class="text-center">
                        <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 24px; line-height: 150%; color: #000000; margin: 18px 0 0 0;">Steve Trevino</h3>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12 comedian-card">
                    <div class="image-box">
                        <img src="{{ asset('/storage/comedian.png') }}" class="img-fluid" alt="...">
                    </div>
                    <div class="text-center">
                        <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 24px; line-height: 150%; color: #000000; margin: 18px 0 0 0;">Steve Trevino</h3>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Clubs -->
    <section class="cb_section cb_clubs d-none">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-5 d-md-flex d-block align-items-center justify-content-between">
                    <h2> Clubs </h2>
                    <a href="/clubs" class="view-all-btn">View All Clubs <i class="fa fa-long-arrow-right ms-2"></i></a>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-3 col-md-6 col-12" style="margin-bottom: 28px;">
                    <div class="card club-card">
                        <!-- Cover Image -->
                        <div class="cover-image">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <!-- Profile Image -->
                        <div class="club-image">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" alt="...">
                        </div>
                        <div class="club-content">

                            <!-- Club Name -->
                            <div class="club-name">
                                <h3> The ComicBook Club </h3>
                            </div>
                            <!-- Timing -->
                            <div class="club-time">
                                Mon- Fri, 9:00AM - 12:00AM
                            </div>
                            <!-- Description -->
                            <div class="club-description">
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                </p>
                            </div>
                            <!-- Action Button -->
                            <div class="club-actions">
                                <button class="follow-btn" style="background: #FFFFFF; color: #5145CD; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    Follow
                                </button>
                                <button class="view-events" style="background: #5145CD; color: #FFFFFF; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    View Events
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12" style="margin-bottom: 28px;">
                    <div class="card club-card">
                        <!-- Cover Image -->
                        <div class="cover-image">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <!-- Profile Image -->
                        <div class="club-image">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" alt="...">
                        </div>
                        <div class="club-content">

                            <!-- Club Name -->
                            <div class="club-name">
                                <h3> The ComicBook Club </h3>
                            </div>
                            <!-- Timing -->
                            <div class="club-time">
                                Mon- Fri, 9:00AM - 12:00AM
                            </div>
                            <!-- Description -->
                            <div class="club-description">
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                </p>
                            </div>
                            <!-- Action Button -->
                            <div class="club-actions">
                                <button class="follow-btn" style="background: #FFFFFF; color: #5145CD; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    Follow
                                </button>
                                <button class="view-events" style="background: #5145CD; color: #FFFFFF; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    View Events
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12" style="margin-bottom: 28px;">
                    <div class="card club-card">
                        <!-- Cover Image -->
                        <div class="cover-image">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <!-- Profile Image -->
                        <div class="club-image">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" alt="...">
                        </div>
                        <div class="club-content">

                            <!-- Club Name -->
                            <div class="club-name">
                                <h3> The ComicBook Club </h3>
                            </div>
                            <!-- Timing -->
                            <div class="club-time">
                                Mon- Fri, 9:00AM - 12:00AM
                            </div>
                            <!-- Description -->
                            <div class="club-description">
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                </p>
                            </div>
                            <!-- Action Button -->
                            <div class="club-actions">
                                <button class="follow-btn" style="background: #FFFFFF; color: #5145CD; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    Follow
                                </button>
                                <button class="view-events" style="background: #5145CD; color: #FFFFFF; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    View Events
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12" style="margin-bottom: 28px;">
                    <div class="card club-card">
                        <!-- Cover Image -->
                        <div class="cover-image">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <!-- Profile Image -->
                        <div class="club-image">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" alt="...">
                        </div>
                        <div class="club-content">

                            <!-- Club Name -->
                            <div class="club-name">
                                <h3> The ComicBook Club </h3>
                            </div>
                            <!-- Timing -->
                            <div class="club-time">
                                Mon- Fri, 9:00AM - 12:00AM
                            </div>
                            <!-- Description -->
                            <div class="club-description">
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                </p>
                            </div>
                            <!-- Action Button -->
                            <div class="club-actions">
                                <button class="follow-btn" style="background: #FFFFFF; color: #5145CD; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    Follow
                                </button>
                                <button class="view-events" style="background: #5145CD; color: #FFFFFF; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    View Events
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SignUp Card -->
    @if (!Auth::user())
    <section class="cb_section signup-section" style="padding-bottom: 100px;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="signup-card">
                        <div class="signup-content">
                            <h2 class="signup-title">Signup at ComicBook Club</h2>
                            <p class="signup-desc">
                            Sign up to be updated on future shows and early ticket release opportunities
                            </p>
                            <a href="/register" class="signup-btn">
                                <span>Get Started</span>
                            </a>
                            <div class="signup-short-desc">
                                <span>Instant singup. No credit card required.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

@endsection

@section('javascript')
    <script type="text/javascript">
        var google_map_key = {!! json_encode(setting('apps.google_map_key')) !!};
        var events_slider = true;
    </script>
    <script type="text/javascript" src="{{ eventmie_asset('js/welcome.js') }}"></script>
@stop
