<div class="pt-10">
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-3">
                <h5 class="mb-3 text-white">@lang('eventsaaspro-pro::em.useful_links') 123</h5>
                <div>
                    <ul class="list-unstyled">
                        <li><a class="text-white lh-lg" href="{{ route('eventsaaspro.page', ['page' => 'about']) }}">@lang('eventsaaspro-pro::em.about')</a>
                        </li>
                        <li><a class="text-white lh-lg" href="{{ route('eventsaaspro.events_index') }}">@lang('eventsaaspro-pro::em.events')</a></li>
                        <li><a class="text-white lh-lg" href="{{ route('eventsaaspro.get_posts') }}">@lang('eventsaaspro-pro::em.blogs')</a></li>
                        <li><a class="text-white lh-lg" href="{{ route('eventsaaspro.page', ['page' => 'terms']) }}">@lang('eventsaaspro-pro::em.terms')</a>
                        </li>
                        <li><a class="text-white lh-lg" href="{{ route('eventsaaspro.page', ['page' => 'privacy']) }}">@lang('eventsaaspro-pro::em.privacy')</a>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <h5 class="mb-3 text-white">@lang('eventsaaspro-pro::em.social')</h5>
                <div>
                    <ul class="list-unstyled">
                        @if (setting('social.facebook'))
                        <li><a href="{{ 'https://www.facebook.com/' . setting('social.facebook') }}" target="_blank" class="text-white lh-lg">@lang('eventsaaspro-pro::em.facebook')</a>
                        @endif
                        @if (setting('social.twitter'))
                        <li><a href="{{ 'https://twitter.com/' . setting('social.twitter') }}" target="_blank" class="text-white lh-lg">@lang('eventsaaspro-pro::em.twitter')</a>
                        @endif
                        @if (setting('social.instagram'))
                        <li><a href="{{ setting('social.instagram') }}" target="_blank" class="text-white lh-lg">@lang('eventsaaspro-pro::em.instagram')</a>
                        @endif
                        @if (setting('social.linkedin'))
                        <li><a href="{{ setting('social.linkedin') }}" target="_blank" class="text-white lh-lg">@lang('eventsaaspro-pro::em.linkedin')</a>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <h5 class="mb-3 text-white">@lang('eventsaaspro-pro::em.contact')</h5>
                <div>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('eventsaaspro.contact') }}" class="text-white lh-lg">@lang('eventsaaspro-pro::em.contact_send_message')</a></li>
                        <li><a href="{{ route('eventsaaspro.contact') }}" class="text-white lh-lg">@lang('eventsaaspro-pro::em.contact_find_us')</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <img class="avatar-lg-custom mx-auto" src="/storage/{{ setting('site.logo') }}" alt="{{ setting('site.site_name') }}" />
            </div>
        </div>

        @php $footerMenuItems = footerMenu() @endphp
        @if (!empty($footerMenuItems))
        <div class="row mb-3">
            @php $key = 1; @endphp
            @foreach ($footerMenuItems as $parentItem)
                <div class="col-md-3">
                    <h5 class=" mb-3 text-white"><i class="{{ $parentItem->icon_class }}"></i> {{ $parentItem->title }}</h5>
                    <ul class="list-unstyled">
                        @foreach ($parentItem->submenu as $childItem)
                        <li>
                            <a class="text-white lh-lg" target="{{ $childItem->target }}" href="{{ $childItem->url }}">
                                <i class="{{ $childItem->icon_class }}"></i> {{ $childItem->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                @if (!($key % 4))
                </div>
                <div class="row mb-3">
                @endif

                @php $key++; @endphp
            @endforeach
        </div>
        @endif

        <div class="row mb-3">
            <div class="col-md-12 text-center h-scroll">
                <ul class="list-group list-group-horizontal list-group-flush justify-content-center">
                @foreach (lang_selector() as $val)
                <li class="list-group-item bg-secondary border-0 p-1">
                    <a class="text-center col-grey px-2 text-white {{ $val == config('app.locale') ? 'active' : '' }}" href="{{ route('eventsaaspro.change_lang', ['lang' => $val]) }}">
                        @lang('eventsaaspro-pro::em.lang_' . $val)
                    </a>
                </li>
                @endforeach
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-12 text-center">
                <p class="fs-6 text-gray-500 pb-3">
                    <span>©</span> {{ date('Y') }}
                    <a href="{{ eventmie_url() }}">{{ setting('site.site_name') ? setting('site.site_name') : config('app.name') }}</a><br>

                    @if (!empty(setting('site.site_footer')))
                        {!! setting('site.site_footer') !!}
                    @endif
                </p>
            </div>
        </div>
        <!-- tiny footer  -->
        <!-- footer section -->
    </div>
    <!-- footer section -->
</div>
