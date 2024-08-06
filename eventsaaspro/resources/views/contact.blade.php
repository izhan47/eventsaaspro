@extends('eventsaaspro::layouts.app')

@section('title')
    @lang('eventsaaspro-pro::em.contact')
@endsection

@section('content')

    <main>
        <!--Hero Banner-->
        <section class="cb_event-listing-banner" style="background-image: url({{config('filesystems.disks.s3.url').'banners/banner2.png'}});">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <h1> Contact Us </h1>
                            <p style="max-width: 750px;">
                            With our email <a href="mailto:comedy@theriothtx.com">comedy@theriothtx.com</a> phone: <a href="tel:713-264-8664">713-264-8664</a>
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        <!--News-->
        <section>
            <div class="pb-lg-12 pb-7">
                <div class="container">

                    <div class="row justify-content-center mt-8">
                        <div class="col-lg-8 col-md-12 col-12">
                            <div>
                                @if (\Session::has('msg'))
                                    <div class="alert alert-success">
                                        {{ \Session::get('msg') }}
                                    </div>
                                @endif
                                @if (\Session::has('error'))
                                    <div class="alert alert-danger">
                                        {{ \Session::get('error') }}
                                    </div>
                                @endif
                                <!-- form -->
                                <form class="row needs-validation" novalidate="" method="POST"
                                    action="{{ route('eventsaaspro.store_contact') }}">
                                    @csrf
                                    @honeypot
                                    <!-- first name -->
                                    <div class="mb-3 col-md-6">
                                        <label for="fname" class="form-label">@lang('eventsaaspro-pro::em.name') <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="@lang('eventsaaspro-pro::em.name')" required="">
                                        <div class="invalid-feedback">
                                            @if ($errors->has('name'))
                                                <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- email -->
                                    <div class="mb-3 col-md-6">
                                        <label for="lname" class="form-label">@lang('eventsaaspro-pro::em.email') <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" id="lname"
                                            placeholder="@lang('eventsaaspro-pro::em.email')" required="">
                                        <div class="invalid-feedback">
                                            @if ($errors->has('email'))
                                                <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- title -->
                                    <div class="mb-3 col-md-12">
                                        <label for="title" class="form-label">@lang('eventsaaspro-pro::em.title') <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="@lang('eventsaaspro-pro::em.title')" required="">
                                        <div class="invalid-feedback">
                                            @if ($errors->has('title'))
                                                <div class="alert alert-danger">{{ $errors->first('title') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- type -->
                                    <div class="mb-3 col-md-12">
                                        <label for="title" class="form-label"> Type <span
                                                class="text-danger">*</span></label>
                                        <select name="type" class="form-control">
                                            <option value="">Select contact purpose</option>
                                            <option value="technical-support">Technical Support</option>
                                            <option value="event-support">Event Suppport</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @if ($errors->has('type'))
                                                <div class="alert alert-danger">{{ $errors->first('type') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- message -->
                                    <div class="mb-3 col-md-12">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control " rows="3" name="message" placeholder="@lang('eventsaaspro-pro::em.message')" id="message"
                                            required=""></textarea>
                                        @if ($errors->has('message'))
                                            <div class="alert alert-danger">{{ $errors->first('message') }}</div>
                                        @endif
                                    </div>
                                    <!-- button -->
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="submit" value="contact-form">
                                            <span><i class="fas fa-paper-plane"></i></span> @lang('eventsaaspro-pro::em.send_message')</button>

                                    </div>
                                </form>
                            </div>
                            <div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-12">
                <div class="innerpage-section g-map-wrapper">
                    <div class="lgxmapcanvas map-canvas-default">
                        <div id="contact_map" style="height: 500px;"></div>
                    </div>
                </div>
            </div>
        </section>

    </main>



@endsection

@section('javascript')
<script src="https://maps.googleapis.com/maps/api/js?key={{ setting('apps.google_map_key') }}&callback=initMap&v=weekly" defer></script>
<script>
function initMap() {
    const myLatLng = {
        lat: {{ setting('contact.google_map_lat') }},
        lng: {{ setting('contact.google_map_long') }}
    };
    const map = new google.maps.Map(document.getElementById("contact_map"), {
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
@stop
