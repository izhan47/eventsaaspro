@extends('eventsaaspro::layouts.app')

@section('title', $event->title)
@section('meta_title', $event->meta_title)
@section('meta_keywords', $event->meta_keywords)
@section('meta_description', $event->meta_description)
@section('meta_image', $event['thumbnail'])
@section('meta_url', url()->current())


@section('content')
    <input type="hidden" value="{{$event->repetitive}}" id="hidden-input">
    @if (!$event->repetitive)
        <div class="popUp col d-block d-sm-none">
            <h4 class="m-0">Buy Tickets:</h4>
            <select-dates :event="{{ json_encode($event, JSON_HEX_APOS) }}"
                :max_ticket_qty="{{ json_encode($max_ticket_qty, JSON_HEX_APOS) }}"
                :login_user_id="{{ json_encode(\Auth::id(), JSON_HEX_APOS) }}"
                :user="{{ json_encode(Auth::user(), JSON_HEX_APOS) }}"
                :is_customer="{{ Auth::id() ? (Auth::user()->hasRole('customer') ? 1 : 0) : 1 }}"
                :is_organiser="{{ Auth::id() ? (Auth::user()->hasRole('organiser') ? 1 : 0) : 0 }}"
                :is_admin="{{ Auth::id() ? (Auth::user()->hasRole('admin') ? 1 : 0) : 0 }}"
                :is_paypal="{{ $is_paypal }}"
                :is_offline_payment_organizer="{{ setting('booking.offline_payment_organizer') ? 1 : 0 }}"
                :is_offline_payment_customer="{{ setting('booking.offline_payment_customer') ? 1 : 0 }}"
                :tickets="{{ json_encode($tickets, JSON_HEX_APOS) }}"
                :booked_tickets="{{ json_encode($booked_tickets, JSON_HEX_APOS) }}"
                :currency="{{ json_encode($currency, JSON_HEX_APOS) }}"
                :total_capacity="{{ $total_capacity }}"
                :is_usaepay="{{ json_encode($is_usaepay) }}"
                :stripe_key="{{ json_encode(setting('apps.stripe_public_key'), JSON_HEX_APOS) }}"
                :csrf_token="{{ json_encode(csrf_token()) }}"
                :date_format="{{ json_encode(
                    [
                        'vue_date_format' => format_js_date(),
                        'vue_time_format' => format_js_time(),
                    ],
                    JSON_HEX_APOS,
                ) }}"

            >

            </select-dates>
        </div>
    @endif
    <!-- <section class="event-poster" style="background-image: url({{ $event['poster'] }});"></section> -->
    <img src="{{ $event['poster'] }}" class="img-fluid w-100" alt="">

    <section class="cb_section cb_event-detail">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12 mb-lg-0 mb-4">

                    <div class="event-title-wrapper">
                        <h2 class="title"> {{ $event['title'] }} <span style="cursor: pointer;" onclick="copyToClipboard('{{env('APP_URL').'/events/'.$event->slug}}')"> <i class="fa fa-clone"></i> </span> </h2>
                        <div class="actions">
                            <button>
                                <img src="{{config('filesystems.disks.s3.url').'icons/share.png'}}" alt="...">
                                <span>Share</span>
                            </button>
                            <!-- <button>
                                <img src="{{config('filesystems.disks.s3.url').'icons/heart.png'}}" alt="...">
                                <span>Save</span>
                            </button> -->
                        </div>
                    </div>

                    <div class="event-organizer">
                        <div class="image">
                            @if (strpos($event->user->avatar, 'https:') !== false)
                                <img src="{{$event->user->avatar}}" alt="...">
                            @else
                                <img src="{{config('filesystems.disks.s3.url').$event->user->avatar}}" alt="...">
                            @endif
                        </div>
                         <div class="title"> {{ $event->user->name }} </div>
                        <!-- <div class="button-wrapper">
                            <button class="follow-btn"> Follow </button>
                        </div> -->
                    </div>

                    <div class="event-description">
                        <h3 class="heading-2"> About Event </h3>
                            {!! $event['description'] !!}
                    </div>

                </div>

                <div class="col-lg-4 col-md-8 col-12 mx-lg-0 mx-md-auto">
                    <div class="event-booking-card">
                        @if (!$event->repetitive)
                            <div class="event-info-wrapper">
                                <div class="label">Time / Date</div>
                                <div class="detail">
                                    {{ userTimezone($event->start_date . ' ' . $event->start_time, 'Y-m-d H:i:s', format_carbon_date(false)) }}
                                    {{ showTimezone() }}

                                    @if($event->start_date != $event->end_date)
                                        -
                                        {{ userTimezone($event->end_date . ' ' . $event->end_time, 'Y-m-d H:i:s', format_carbon_date(false)) }}
                                        {{ showTimezone() }}
                                    @endif
                                </div>
                            </div>

                        @else

                            <div class="event-info-wrapper">
                                <div class="label">Time / Date</div>
                                <div class="detail">
                                    {{ userTimezone($event->start_date . ' ' . $event->start_time, 'Y-m-d H:i:s', format_carbon_date(true)) }}

                                    -

                                    {{ userTimezone($event->start_date . ' ' . $event->start_time, 'Y-m-d H:i:s', 'Y-m-d') <= userTimezone($event->end_date . ' ' . $event->end_time, 'Y-m-d H:i:s', 'Y-m-d') ? userTimezone($event->end_date . ' ' . $event->end_time, 'Y-m-d H:i:s', format_carbon_date(true)) : userTimezone($event->start_date . ' ' . $event->start_time, 'Y-m-d H:i:s', format_carbon_date(true)) }}
                                </div>
                            </div>

                        @endif

                        <!-- <div class="more-options">More Options</div> -->

                        @if (!empty($event['venue']))
                            <div class="event-info-wrapper">
                                <div class="label">Location</div>
                                <div class="detail">
                                    {{ $event['venue'] }}
                                </div>
                            </div>
                        @endif

                        <div class="price-wrapper">
                            @php
                                $minPrice = collect($tickets)->min('price');
                                $maxPrice = collect($tickets)->max('price');
                            @endphp

                            @if($minPrice == $maxPrice)
                                <div class="price"> {{$currency}}{{ $minPrice }} </div>
                            @else
                                <div class="price"> {{$currency}}{{ $minPrice }} - {{$currency}}{{ $maxPrice }} </div>
                            @endif
                        </div>


                        <select-dates :event="{{ json_encode($event, JSON_HEX_APOS) }}"
                            :max_ticket_qty="{{ json_encode($max_ticket_qty, JSON_HEX_APOS) }}"
                            :login_user_id="{{ json_encode(\Auth::id(), JSON_HEX_APOS) }}"
                            :user="{{ json_encode(Auth::user(), JSON_HEX_APOS) }}"
                            :is_customer="{{ Auth::id() ? (Auth::user()->hasRole('customer') ? 1 : 0) : 1 }}"
                            :is_organiser="{{ Auth::id() ? (Auth::user()->hasRole('organiser') ? 1 : 0) : 0 }}"
                            :is_admin="{{ Auth::id() ? (Auth::user()->hasRole('admin') ? 1 : 0) : 0 }}"
                            :is_paypal="{{ $is_paypal }}"
                            :is_offline_payment_organizer="{{ setting('booking.offline_payment_organizer') ? 1 : 0 }}"
                            :is_offline_payment_customer="{{ setting('booking.offline_payment_customer') ? 1 : 0 }}"
                            :tickets="{{ json_encode($tickets, JSON_HEX_APOS) }}"
                            :booked_tickets="{{ json_encode($booked_tickets, JSON_HEX_APOS) }}"
                            :currency="{{ json_encode($currency, JSON_HEX_APOS) }}"
                            :total_capacity="{{ $total_capacity }}"
                            :is_usaepay="{{ json_encode($is_usaepay) }}"
                            :stripe_key="{{ json_encode(setting('apps.stripe_public_key'), JSON_HEX_APOS) }}"
                            :csrf_token="{{ json_encode(csrf_token()) }}"
                            :date_format="{{ json_encode(
                                [
                                    'vue_date_format' => format_js_date(),
                                    'vue_time_format' => format_js_time(),
                                ],
                                JSON_HEX_APOS,
                            ) }}"

                            >

                        </select-dates>

                        <!-- <button class="book-seat-btn"> Book a seat </button> -->

                        <div class="excerpt"> {{ $event['excerpt'] }} </div>
                        @if (isset($event->comedian) && count($event->comedian) > 0)
                        <div class="event-comic-lineups">
                            <div class="title"> Comic Lineups </div>
                            <ul class="comic-list">
                                @foreach($event->comedian as $comedian)
                                <li>
                                    <div class="image">
                                        <img src="{{config('filesystems.disks.s3.url').$comedian->user->avatar}}" alt="...">
                                    </div>
                                    <div class="title"> {{$comedian->user->name}} </div>
                                    <div class="role"> {{$comedian->user->comedian_stage_name}} </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery -->

    @if (!empty($event->video_link) || !empty($event->images))
    <section class="cb_section cb_gallery">
        <div class="container">

            <div class="row">
                <div class="col-12">
                    <h3 class="heading-2"> Gallery </h3>
                </div>
            </div>

            <ul class="row gallery-videos">
                @if (!empty($event->video_link))
                    <li class="col-lg-4 col-md-6 col-12">
                        <iframe width="100%" height="228" src="https://www.youtube.com/embed/{{ $event->video_link }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </li>
                @endif

                @if (!empty($event->images))

                    @foreach (json_decode($event->images) as $image)
                        <li class="col-lg-4 col-md-6 col-12">
                            <img src="{{ $image }}" class="img-fluid" alt=""> <br>
                        </li>
                    @endforeach
                @endif

            </ul>
        </div>
    </section>
    @endif

    @if ($event->latitude !== null && $event->longitude !== null)
    <section class="cb_section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="heading-2"> Location </h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="event_venue_map" class="listing-map" style="width:100%;height:500px"></div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- More Events -->
    @if (isset($related_events) && count($related_events) > 0)
    <section class="cb_section" style="padding-bottom: 100px;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="heading-2"> More Events from ComicBook Club </h3>
                </div>
            </div>

            <div class="row">
            <!-- related_events -->
                @foreach($related_events as $related)
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <event
                        :event="{{json_encode($related), JSON_HEX_APOS}}"
                        :currency="{{ json_encode($currency, JSON_HEX_APOS) }}"
                        :date_format="{{ json_encode(
                                [
                                    'vue_date_format' => format_js_date(),
                                    'vue_time_format' => format_js_time(),
                                ],
                                JSON_HEX_APOS,
                            ) }}"
                    ></event>
                </div>
                @endforeach

            </div>

        </div>
    </section>
    @endif
@endsection

@section('javascript')
    <script type="text/javascript">
        var google_map_key = {!! json_encode($google_map_key) !!};
    </script>
    <script src="https://cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js"></script>
    <script type="text/javascript" src="{{ eventmie_asset('js/events_show.js') }}"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ setting('apps.google_map_key') }}&callback=initMap&v=weekly" defer></script>
    <script>
        function initMap() {
            const myLatLng = {
                lat: {{ $event->latitude }},
                lng: {{ $event->longitude }}
            };
            const map = new google.maps.Map(document.getElementById("event_venue_map"), {
                zoom: 15,
                center: myLatLng,
            });

            new google.maps.Marker({
                position: myLatLng,
                map,
                title: {!! setting('site.site_name') ? json_encode(setting('site.site_name')) : json_encode(config('app.name')) !!},
            });
        }
        window.initMap = initMap;
    </script>

    <script>
        function showNotification(type, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 4000,
                customClass: {
                    container: 'custom-swal-container',
                    popup: 'custom-swal-popup custom-swal-popup-' + type,
                    header: 'custom-swal-header',
                    title: 'custom-swal-title',
                    closeButton: 'custom-swal-close-button',
                    image: 'custom-swal-image',
                    content: 'custom-swal-content',
                    input: 'custom-swal-input',
                    actions: 'custom-swal-actions',
                    confirmButton: 'custom-swal-confirm-button',
                    cancelButton: 'custom-swal-cancel-button',
                    footer: 'custom-swal-footer'
                }
            });
            Toast.fire({
                type: type,
                html: message
            })
        }
        function copyToClipboard(url) {
            // Create a temporary textarea element
            const textarea = document.createElement('textarea');

            // Set the value to the provided URL
            textarea.value = url;

            // Make the textarea out of the viewport
            textarea.style.position = 'fixed';
            textarea.style.top = '-9999px';

            // Append the textarea to the document
            document.body.appendChild(textarea);

            // Select the text in the textarea
            textarea.select();

            try {
                // Execute the copy command
                document.execCommand('copy');
                showNotification('success', 'Copied')
            } catch (err) {
                console.error('Unable to copy text to clipboard', err);
            } finally {
                // Remove the temporary textarea from the document
                document.body.removeChild(textarea);
            }
        }
    </script>

    <script>
        let repetitive = document.getElementById('hidden-input');
        if (repetitive.value == 0) {
            document.getElementById('eventmie_app').classList.add('event-detail-page');
        }
    </script>
@stop
