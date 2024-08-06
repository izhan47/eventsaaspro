@component('mail::message')
# {{ $mail_data->mail_subject }}

<br>

**{{ __('eventsaaspro-pro::em.event') }}:** {{ $mail_data->mail_data[0]['event_title'] }}<br>

**{{ __('eventsaaspro-pro::em.date') }}:** {{ userTimezone($mail_data->mail_data[0]['event_start_date'].' '.$mail_data->mail_data[0]['event_start_time'], 'Y-m-d H:i:s', format_carbon_date(true))  }} - {{ ( userTimezone($mail_data->mail_data[0]['event_start_date'].' '.$mail_data->mail_data[0]['event_start_time'], 'Y-m-d H:i:s', 'Y-m-d') <= userTimezone($mail_data->mail_data[0]['event_end_date'].' '.$mail_data->mail_data[0]['event_end_time'], 'Y-m-d H:i:s', 'Y-m-d') ? userTimezone($mail_data->mail_data[0]['event_end_date'].' '.$mail_data->mail_data[0]['event_end_time'], 'Y-m-d H:i:s', format_carbon_date(true)) : userTimezone($mail_data->mail_data[0]['event_start_date'].' '.$mail_data->mail_data[0]['event_start_time'], 'Y-m-d H:i:s', format_carbon_date(true)) ) }} {{ showTimezone() }} <br>

**{{ __('eventsaaspro-pro::em.timings') }}:** {{ userTimezone($mail_data->mail_data[0]['event_start_date'].' '.$mail_data->mail_data[0]['event_start_time'], 'Y-m-d H:i:s', format_carbon_date(false))  }} - {{ userTimezone($mail_data->mail_data[0]['event_end_date'].' '.$mail_data->mail_data[0]['event_end_time'], 'Y-m-d H:i:s', format_carbon_date(false)) }} {{ showTimezone() }}  <br>

<br>

## {{ __('eventsaaspro-pro::em.tickets') }}


@component('mail::table')
| {{ __('eventsaaspro-pro::em.order') }} | {{ __('eventsaaspro-pro::em.ticket') }} | {{ __('eventsaaspro-pro::em.price') }} | {{ __('eventsaaspro-pro::em.quantity') }} |
|:-------------:|:-------------:|:-------------:|:-------------:|
@foreach($mail_data->mail_data as $val)
| {{$val['order_number']}} | {{$val['ticket_title']}} | {{$val['ticket_price']}} {{$val['currency']}} | {{$val['quantity']}} |
@endforeach
@endcomponent


@component('mail::button', ['url' => $mail_data->action_url])
{{ $mail_data->action_title }}
@endcomponent

@if($mail_data->is_online)
@component('mail::button', ['url' => $mail_data->action_url])
{{ __('eventsaaspro-pro::em.online_event') }}
@endcomponent
@endif

@if($mail_data->additional_data && $mail_data->additional_data !== null)
<strong>Additional Information :</strong> {!! $mail_data->additional_data !!}
@endif
{!! __('eventsaaspro-pro::em.order_terms') !!}<br><br>

{{ __('eventsaaspro-pro::em.thank_you') }}<br>
{{ (setting('site.site_name') ? setting('site.site_name') : config('app.name')) }} - [{{ trim(eventmie_url(), '/') }}]({{ eventmie_url() }})
@endcomponent
