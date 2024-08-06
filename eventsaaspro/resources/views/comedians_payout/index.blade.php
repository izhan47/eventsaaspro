@extends('eventsaaspro::o_dashboard.index')

<!-- @lang('eventsaaspro-pro::em.myevents') -->
@section('title')
    Comedians Payout
@endsection

@section('o_dashboard')
<div class="container-fluid my-2">
    <div class="row">
        <div class="col-md-12">
            <router-view
                :currency="{{ json_encode(setting('regional.currency_default'), JSON_HEX_APOS) }}"
                :date_format="{{ json_encode(
                    [
                        'vue_date_format' => format_js_date(),
                        'vue_time_format' => format_js_time(),
                    ],
                    JSON_HEX_APOS,
                ) }}">
            </router-view>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>var path = {!! json_encode($path, JSON_HEX_TAG) !!};</script>
<script type="text/javascript" src="{{ eventmie_asset('js/comedians_payout.js') }}"></script>
@stop
