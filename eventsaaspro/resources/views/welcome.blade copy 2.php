@extends('eventsaaspro::layouts.app')

@section('title') @lang('eventsaaspro-pro::em.home') @endsection

@section('content')
    @php
        $perPage = 3;
    @endphp
    <!--Hero Banner-->
    <section style="background-image: url(/storage/banners/home-banner.png); padding: 200px 0; background-repeat: no-repeat; background-size: cover; background-position: center;">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-12">
                    <h1 style="max-width: 416px; width: 100%; color: #FFFFFF; font-family: 'Inter', sans-serif; font-weight: 600; font-size: 48px; line-height: 120%;">Laugh more, worry less</h1>
                    <p style="max-width: 441px; width: 100%; color: #FFFFFF; font-family: 'Inter', sans-serif; font-weight: 400; font-size: 18px; line-height: 150%;">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugiat soluta maxime facilis?</p>
                </div>
            </div>
        </div>
    </section>
    <!--Hero Banner-->

    <!-- Event Near You -->
    <section style="background-color: #FFFFFF; padding: 100px 0 0 0;">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-5 d-flex align-items-center justify-content-between">
                    <h2 style="color: #000000; font-family: 'Inter', sans-serif; font-weight: 600; font-size: 36px; line-height: 120%;">Events Near You</h2>
                    <a href="#" style="color: #6C2BD9; padding: 14px 24px; background: #ffffff; border: 1px solid #6C2BD9; border-radius: 5px;">View All Events <i class="fa fa-long-arrow-right ms-2"></i></a>
                </div>
            </div>

            <div class="row">

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Events -->
    <section style="background-color: #FFFFFF; padding: 100px 0 0 0;">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-5 d-flex align-items-center justify-content-between">
                    <h2 style="color: #000000; font-family: 'Inter', sans-serif; font-weight: 600; font-size: 36px; line-height: 120%;">Featured Events</h2>
                </div>
            </div>

            <div class="row">

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" alt="...">
                        </div>
                        <div style="padding: 11px 11px 27px 11px">

                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div class="img" style="width: 20px; height: 20px;">
                                        <img src="{{ asset('/storage/Avatar.png') }}" class="img-fluid" alt="...">
                                    </div>
                                    <div class="name" style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 11px; line-height: 150%; color: #111928;">The Riot Club</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/share.png') }}" alt="...">
                                    </button>
                                    <button style="padding: 0; border: 0; background: none;">
                                        <img src="{{ asset('/storage/icons/heart.png') }}" alt="...">
                                    </button>
                                </div>
                            </div>
                            <!-- Title -->
                            <div style="margin: 15px 0; display: flex; align-items: start; justify-content: space-between;">
                                <h3 style="max-width: 192px; width: 100%; font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    Friday Night Comedy Showcase
                                </h3>

                                <!-- Price -->
                                <div style="font-family: 'Inter', san-serif; font-weight: 600; font-size: 18px; line-height: 140%; color: #000000; margin: 0;">
                                    <span> $20 </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div style="font-family: 'Inter', san-serif; font-weight: 500; font-size: 15px; line-height: 150%; color: #6C2BD9; margin: 0 0 10px 0;">
                                Fri Sep 20 | 11:00 PM - 11:30 PM
                            </div>

                            <!-- Description -->
                            <div>
                                <p style="font-family: 'Inter', san-serif; font-weight: 400; font-size: 14px; line-height: 150%; color: #374151; margin: 0;">
                                    Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Comedian -->
    <section style="background-color: #FFFFFF; padding: 100px 0 0 0;">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-5 d-flex align-items-center justify-content-between">
                    <h2 style="color: #000000; font-family: 'Inter', sans-serif; font-weight: 600; font-size: 36px; line-height: 120%;">Featured Comedians</h2>
                    <a href="#" style="color: #6C2BD9; padding: 14px 24px; background: #ffffff; border: 1px solid #6C2BD9; border-radius: 5px;">View All Comedians <i class="fa fa-long-arrow-right ms-2"></i></a>
                </div>
            </div>

            <div class="row">

                <div class="col-md-3 col-12">
                    <div style="width: 296px; height: 296px; border-radius: 30px;">
                        <img src="{{ asset('/storage/comedian.png') }}" style="border-radius: 30px;" class="img-fluid" alt="...">
                    </div>
                    <div class="text-center">
                        <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 24px; line-height: 150%; color: #000000; margin: 18px 0 0 0;">Steve Trevino</h3>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div style="width: 296px; height: 296px; border-radius: 30px;">
                        <img src="{{ asset('/storage/comedian.png') }}" style="border-radius: 30px;" class="img-fluid" alt="...">
                    </div>
                    <div class="text-center">
                        <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 24px; line-height: 150%; color: #000000; margin: 18px 0 0 0;">Steve Trevino</h3>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div style="width: 296px; height: 296px; border-radius: 30px;">
                        <img src="{{ asset('/storage/comedian.png') }}" style="border-radius: 30px;" class="img-fluid" alt="...">
                    </div>
                    <div class="text-center">
                        <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 24px; line-height: 150%; color: #000000; margin: 18px 0 0 0;">Steve Trevino</h3>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div style="width: 296px; height: 296px; border-radius: 30px;">
                        <img src="{{ asset('/storage/comedian.png') }}" style="border-radius: 30px;" class="img-fluid" alt="...">
                    </div>
                    <div class="text-center">
                        <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 24px; line-height: 150%; color: #000000; margin: 18px 0 0 0;">Steve Trevino</h3>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Clubs -->
    <section style="background-color: #FFFFFF; padding: 100px 0 0 0;">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-5 d-flex align-items-center justify-content-between">
                    <h2 style="color: #000000; font-family: 'Inter', sans-serif; font-weight: 600; font-size: 36px; line-height: 120%;"> Clubs </h2>
                    <a href="#" style="color: #6C2BD9; padding: 14px 24px; background: #ffffff; border: 1px solid #6C2BD9; border-radius: 5px;">View All Clubs <i class="fa fa-long-arrow-right ms-2"></i></a>
                </div>
            </div>

            <div class="row">

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <!-- Cover Image -->
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" style="border-radius: 10px;" alt="...">
                        </div>
                        <!-- Profile Image -->
                        <div style="width: 98px; height: 98px; border-radius: 50%; margin: -45px auto 0 auto;">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" style="width: 98px; height: 98px; border-radius: 50%; object-fit: object; object-position: center;" alt="...">
                        </div>
                        <div style="padding: 11px 11px 28px 11px; text-align: center;">

                            <!-- Club Name -->
                            <div class="text-center" style="margin-top: 7px;">
                                <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 20px; line-height: 150%; margin: 0; color: #111928;"> The Riot Club </h3>
                            </div>
                            <!-- Timing -->
                            <div style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; margin: 0 0 13px 0; color: #374151;">
                                Mon- Fri, 9:00AM - 12:00AM
                            </div>
                            <!-- Description -->
                            <div style="margin: 0 0 28px 0;">
                                <p style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; margin: 0; color: #6B7280;">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                </p>
                            </div>
                            <!-- Action Button -->
                            <div class="d-flex align-items-center justify-content-center" style="gap: 24px;">
                                <button style="background: #FFFFFF; color: #5145CD; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    Follow
                                </button>
                                <button style="background: #5145CD; color: #FFFFFF; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    View Events
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <!-- Cover Image -->
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" style="border-radius: 10px;" alt="...">
                        </div>
                        <!-- Profile Image -->
                        <div style="width: 98px; height: 98px; border-radius: 50%; margin: -45px auto 0 auto;">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" style="width: 98px; height: 98px; border-radius: 50%; object-fit: object; object-position: center;" alt="...">
                        </div>
                        <div style="padding: 11px 11px 28px 11px; text-align: center;">

                            <!-- Club Name -->
                            <div class="text-center" style="margin-top: 7px;">
                                <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 20px; line-height: 150%; margin: 0; color: #111928;"> The Riot Club </h3>
                            </div>
                            <!-- Timing -->
                            <div style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; margin: 0 0 13px 0; color: #374151;">
                                Mon- Fri, 9:00AM - 12:00AM
                            </div>
                            <!-- Description -->
                            <div style="margin: 0 0 28px 0;">
                                <p style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; margin: 0; color: #6B7280;">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                </p>
                            </div>
                            <!-- Action Button -->
                            <div class="d-flex align-items-center justify-content-center" style="gap: 24px;">
                                <button style="background: #FFFFFF; color: #5145CD; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    Follow
                                </button>
                                <button style="background: #5145CD; color: #FFFFFF; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    View Events
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <!-- Cover Image -->
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" style="border-radius: 10px;" alt="...">
                        </div>
                        <!-- Profile Image -->
                        <div style="width: 98px; height: 98px; border-radius: 50%; margin: -45px auto 0 auto;">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" style="width: 98px; height: 98px; border-radius: 50%; object-fit: object; object-position: center;" alt="...">
                        </div>
                        <div style="padding: 11px 11px 28px 11px; text-align: center;">

                            <!-- Club Name -->
                            <div class="text-center" style="margin-top: 7px;">
                                <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 20px; line-height: 150%; margin: 0; color: #111928;"> The Riot Club </h3>
                            </div>
                            <!-- Timing -->
                            <div style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; margin: 0 0 13px 0; color: #374151;">
                                Mon- Fri, 9:00AM - 12:00AM
                            </div>
                            <!-- Description -->
                            <div style="margin: 0 0 28px 0;">
                                <p style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; margin: 0; color: #6B7280;">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                </p>
                            </div>
                            <!-- Action Button -->
                            <div class="d-flex align-items-center justify-content-center" style="gap: 24px;">
                                <button style="background: #FFFFFF; color: #5145CD; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    Follow
                                </button>
                                <button style="background: #5145CD; color: #FFFFFF; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    View Events
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12" style="margin-bottom: 28px;">
                    <div class="card">
                        <!-- Cover Image -->
                        <div>
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" class="img-fluid w-100" style="border-radius: 10px;" alt="...">
                        </div>
                        <!-- Profile Image -->
                        <div style="width: 98px; height: 98px; border-radius: 50%; margin: -45px auto 0 auto;">
                            <img src="{{ asset('/storage/posts/September2019/card.png') }}" style="width: 98px; height: 98px; border-radius: 50%; object-fit: object; object-position: center;" alt="...">
                        </div>
                        <div style="padding: 11px 11px 28px 11px; text-align: center;">

                            <!-- Club Name -->
                            <div class="text-center" style="margin-top: 7px;">
                                <h3 style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 20px; line-height: 150%; margin: 0; color: #111928;"> The Riot Club </h3>
                            </div>
                            <!-- Timing -->
                            <div style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; margin: 0 0 13px 0; color: #374151;">
                                Mon- Fri, 9:00AM - 12:00AM
                            </div>
                            <!-- Description -->
                            <div style="margin: 0 0 28px 0;">
                                <p style="font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; margin: 0; color: #6B7280;">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                </p>
                            </div>
                            <!-- Action Button -->
                            <div class="d-flex align-items-center justify-content-center" style="gap: 24px;">
                                <button style="background: #FFFFFF; color: #5145CD; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
                                    Follow
                                </button>
                                <button style="background: #5145CD; color: #FFFFFF; border-radius: 8px; padding: 10px 20px; font-family: 'Inter', sans-serif; font-weight: 500; font-size: 14px; line-height: 150%; border: 0;">
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
    <section style="background-color: #FFFFFF; padding: 100px 0;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div style="padding: 96px 0; background: #6C2BD9; border-radius: 10px;">
                        <div style="max-width: 672px; width: 100%; margin: 0 auto; text-align: center;">
                            <h2 style="margin: 0; font-family: 'Inter', sans-serif; font-weight: 600; font-size: 36px; line-height: 125%; color: #F9FAFB;">Signup at Riot Club</h2>
                            <p style="margin: 16px 0 32px 0; font-family: 'Inter', sans-serif; font-weight: 400; font-size: 20px; line-height: 150%; color: #F9FAFB;">
                            Lörem ipsum tyvis pred astrobel, sorat. Antibes rorade i tyll resybelt. Gigade vaskapet hubot. Bad ron medan astrocism. Tuktig vin.
                            </p>
                            <button style="border: 0; background: #FFFFFF; border-radius: 8px; padding: 12px 20px;">
                                <span style="font-family: 'Inter', sans-serif; font-weight: 400; font-size: 16px; line-height: 150%; color: #000000;">Get Started</span>
                            </button>
                            <div style="margin: 8px 0 0 0;">
                                <span style="font-family: 'Inter', sans-serif; font-weight: 400; font-size: 16px; line-height: 150%; color: #EBF5FF;">Instant singup. No credit cardrequired.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('javascript')
    <script type="text/javascript">
        var google_map_key = {!! json_encode(setting('apps.google_map_key')) !!};
        var events_slider = true;
    </script>
    <script type="text/javascript" src="{{ eventmie_asset('js/welcome.js') }}"></script>
@stop
