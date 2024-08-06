
<div id="navbar_vue"
    class="nav-header nav-header-classic {{ \Str::contains(url()->current(), 'dashboard') ? 'shadow menu-fixed nav-dashboard' : 'menu-space header-position header-white' }}">
    <div class="{{ \Str::contains(url()->current(), 'dashboard') ? 'dashboard-container' : 'container' }}">
        <div class="row">
            <div class="col-md-12">
                <!-- GDPR -->
                <cookie-law theme="gdpr" button-text="@lang('eventsaaspro-pro::em.accept')">
                    <div slot="message">
                        <gdpr-message></gdpr-message>
                    </div>
                </cookie-law>
                <!-- GDPR -->

                <!-- Vue Alert message -->
                @if ($errors->any())
                    <alert-message :errors="{{ json_encode($errors->all(), JSON_HEX_APOS) }}"></alert-message>
                @endif

                @if (session('status'))
                    <alert-message :message="'{{ session('status') }}'"></alert-message>
                @endif
                <!-- Vue Alert message -->

                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand pr-5" href="{{ url('') }}">
                            <img src="{{ config('filesystems.disks.s3.url').'logos/Logo.png' }}" id="brand-logo"
                            class="mx-auto d-blocks"
                            alt="logo" style="height:50px;" />
                    </a>
                    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>


                    <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">


                        <ul class="navbar-nav mx-lg-auto" >
                            @php $headerMenuItems = headerMenu() @endphp
                            @if (!empty($headerMenuItems))
                                @php $key = 1; @endphp
                                @foreach ($headerMenuItems as $parentItem)
                                    <li class="nav-item ">
                                        <a href="{{ $parentItem->url }}" class="nav-link">{{ $parentItem->title }}</a>
                                    </li>
                                    @php $key++; @endphp
                                @endforeach
                            @endif
                        </ul>

                        @if (Auth::user())
                            <ul class="navbar-nav ">
                                <li class="nav-item dropdown ">
                                    @php
                                        $data = notifications();
                                    @endphp

                                    <a class="nav-link dropdown-toggle" href="#" id="blogDropdown" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false" v-pre>
                                        <span class="position-relative btn btn-sm btn-primary badge">
                                            <i class="fas fa-bell text-white"> </i>
                                            @if ($data['total_notify'] > 0)
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle"></span>
                                            @endif
                                        </span>
                                        <i class="fas fa-caret-down"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="blogDropdown">
                                        @if (!empty($data['notifications']))
                                            @foreach ($data['notifications'] as $notification)
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-item" href="{{ route('eventsaaspro.notify_read', [$notification->n_type]) }}">
                                                        {{ $notification->total }}
                                                        @if ($notification->n_type == 'user')
                                                            @lang('eventsaaspro-pro::em.user')
                                                        @elseif($notification->n_type == 'cancel')
                                                            @lang('eventsaaspro-pro::em.booking_cancellation')
                                                        @elseif($notification->n_type == 'review')
                                                            @lang('eventsaaspro-pro::em.show_reviews')
                                                        @elseif($notification->n_type == 'contact')
                                                            @lang('eventsaaspro-pro::em.contact')
                                                        @elseif($notification->n_type == 'events')
                                                            @lang('eventsaaspro-pro::em.event')
                                                        @elseif($notification->n_type == 'Approve-Organizer')
                                                            @lang('eventsaaspro-pro::em.requested_to_become_organiser')
                                                        @elseif($notification->n_type == 'Approved-Organizer')
                                                            @lang('eventsaaspro-pro::em.became_organiser_successful')
                                                        @elseif($notification->n_type == 'bookings')
                                                            @lang('eventsaaspro-pro::em.booking')
                                                        @elseif($notification->n_type == 'forgot_password')
                                                            @lang('eventsaaspro-pro::em.reset_password')
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item"> @lang('eventsaaspro-pro::em.no_notifications')</a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="blogDropdown" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false" v-pre>
                                        @if (Auth::user()->hasRole('customer'))
                                            <i class="fas fa-user-circle"></i>
                                        @elseif(Auth::user()->hasRole('organiser'))
                                            <i class="fas fa-person-booth"></i>
                                        @else
                                            <i class="fas fa-user-shield"></i>
                                        @endif

                                        {{ Auth::user()->name }} <i class="fas fa-caret-down"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="blogDropdown">

                                        {{-- Customer --}}
                                        @if (Auth::user()->hasRole('customer'))
                                            <li class="nav-item dropdown ">
                                                <a class="dropdown-item" href="{{ route('eventsaaspro.profile') }}"><i class="fas fa-id-card"></i>
                                                    @lang('eventsaaspro-pro::em.profile')</a>
                                            </li>
                                            <li class="nav-item dropdown ">
                                                <a class="dropdown-item" href="{{ route('eventsaaspro.mybookings_index') }}"><i
                                                        class="fas fa-money-check-alt"></i> @lang('eventsaaspro-pro::em.mybookings')</a>
                                            </li>
                                        @endif

                                        {{-- Organiser --}}
                                        @if (Auth::user()->hasRole('organiser'))
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ route('eventsaaspro.profile') }}"><i class="fas fa-id-card"></i>
                                                    @lang('eventsaaspro-pro::em.profile')</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ route('eventsaaspro.o_dashboard') }}"><i
                                                        class="fas fa-tachometer-alt"></i> @lang('eventsaaspro-pro::em.dashboard')</a>
                                            </li>
                                        @endif

                                        {{-- Admin --}}
                                        @if (Auth::user()->hasRole('admin'))
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ eventmie_url() . '/' . config('eventsaaspro.route.admin_prefix') }}"><i
                                                        class="fas fa-tachometer-alt"></i> @lang('eventsaaspro-pro::em.admin_panel')</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ route('eventsaaspro.profile') }}"><i class="fas fa-id-card"></i>
                                                    @lang('eventsaaspro-pro::em.profile')</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ route('eventsaaspro.ticket_scan') }}"><i class="fas fa-qrcode"></i>
                                                    @lang('eventsaaspro-pro::em.scan_ticket')</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="dropdown-item" href="{{ route('eventsaaspro.myevents_form') }}"><i
                                                        class="fas fa-calendar-plus"></i> @lang('eventsaaspro-pro::em.create_event')</a>
                                            </li>
                                        @endif

                                        <li class="nav-item dropdown">
                                            <a class="dropdown-item" href="{{ route('eventsaaspro.logout') }}"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fas fa-sign-out-alt"></i> @lang('eventsaaspro-pro::em.logout')
                                            </a>
                                            <form id="logout-form" action="{{ route('eventsaaspro.logout') }}" method="POST" style="display: none;">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        @else
                            <ul class="navbar-nav ms-lg-auto" style="gap: 8px;">
                                <li class="nav-item "><a href="/login" class="nav-link cb_menu-login">Login</a></li>
                                <li class="nav-item "><a href="/register" class="cb_get-started-btn">Get Started</a></li>
                                <li class="nav-item "><a href="tel:tel:713-264-8664" class="nav-link cb_menu-login">
                                    <i class="fa fa-phone"></i>
                                    <span class="ms-1">Call us</span>
                                </a></li>
                            </ul>
                        @endif

                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
