<div id="db-wrapper-two">
    <nav class="navbar-vertical-compact shadow-sm mt-9">
        <div data-simplebar style="" class="vh-100 mt-5">
            <ul class="navbar-nav flex-column" id="sideNavbar">

                <li class="nav-item tooltip-custom">
                    <a class="nav-link {{ Route::currentRouteName() == 'eventsaaspro.o_dashboard' ? 'active' : '' }}"
                        href="{{ route('eventsaaspro.o_dashboard') }}" data-bs-toggle="tooltip" data-bs-placement="right"
                        title="@lang('eventsaaspro-pro::em.dashboard')">
                        <span class="nav-icon"><i class="fas fa-gauge"></i></span>
                        <span class="tooltiptext">@lang('eventsaaspro-pro::em.dashboard')</span>
                    </a>
                </li>

                <li class="nav-item tooltip-custom">
                    <a class="nav-link {{ Route::currentRouteName() == 'eventsaaspro.myevents_index' || Route::currentRouteName() == 'eventsaaspro.myevents_form' ? 'active' : '' }}"
                        href="{{ route('eventsaaspro.myevents_index') }}" title="@lang('eventsaaspro-pro::em.myevents')">
                        <span class="nav-icon"><i class="far fa-calendar-alt"></i></span>
                        <span class="tooltiptext">@lang('eventsaaspro-pro::em.myevents')</span>
                    </a>
                </li>

                <li class="nav-item tooltip-custom">
                    <a class="nav-link {{ Route::currentRouteName() == 'eventsaaspro.ticket_scan' ? 'active' : '' }}"
                        href="{{ route('eventsaaspro.ticket_scan') }}" title="@lang('eventsaaspro-pro::em.scan_ticket')">
                        <span class="nav-icon"><i class="fas fa-qrcode"></i></span>
                        <span class="tooltiptext">@lang('eventsaaspro-pro::em.scan_ticket')</span>
                    </a>
                </li>


                <li class="nav-item tooltip-custom">
                    <a class="nav-link {{ Route::currentRouteName() == 'eventsaaspro.obookings_index' ? 'active' : '' }}"
                        href="{{ route('eventsaaspro.obookings_index') }}" title="@lang('eventsaaspro-pro::em.mybookings')">
                        <span class="nav-icon"><i class="fas fa-money-check-alt"></i></span>
                        <span class="tooltiptext">@lang('eventsaaspro-pro::em.mybookings')</span>
                    </a>
                </li>

                <li class="nav-item tooltip-custom">
                    <a class="nav-link {{ Route::currentRouteName() == 'eventsaaspro.event_earning_index' ? 'active' : '' }}"
                        href="{{ route('eventsaaspro.event_earning_index') }}" title="@lang('eventsaaspro-pro::em.myearning')">
                        <span class="nav-icon"><i class="fas fa-wallet"></i></span>
                        <span class="tooltiptext">@lang('eventsaaspro-pro::em.myearning')</span>
                    </a>
                </li>

                <li class="nav-item tooltip-custom">
                    <a class="nav-link {{ Route::currentRouteName() == 'eventsaaspro.tags_form' ? 'active' : '' }}"
                        href="{{ route('eventsaaspro.tags_form') }}" title="@lang('eventsaaspro-pro::em.mytags')">
                        <span class="nav-icon"><i class="fas fa-user-tag"></i></span>
                        <span class="tooltiptext">@lang('eventsaaspro-pro::em.mytags')</span>
                    </a>
                </li>

                <li class="nav-item tooltip-custom">
                    <a class="nav-link {{ Route::currentRouteName() == 'eventsaaspro.myvenues.index' ? 'active' : '' }}"
                        href="{{ route('eventsaaspro.myvenues.index') }}" title="@lang('eventsaaspro-pro::em.myvenues')">
                        <span class="nav-icon"><i class="fas fa-map-location"></i></span>
                        <span class="tooltiptext">@lang('eventsaaspro-pro::em.myvenues')</span>
                    </a>
                </li>

                <li class="nav-item tooltip-custom">
                    <a class="nav-link {{ Route::currentRouteName() == 'eventsaaspro.comedians_payout_index' ? 'active' : '' }}"
                        href="{{ route('eventsaaspro.comedians_payout_index') }}" title="Comedians Payout">
                        <span class="nav-icon"><i class="far fa-user"></i></span>
                        <span class="tooltiptext">Comedians Payout</span>
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</div>
