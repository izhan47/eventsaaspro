@extends('eventsaaspro::layouts.app')

@section('title')
    @lang('eventsaaspro-pro::em.booking_details')
@endsection

@section('content')
    <main>

        <section class="bg-light">

            <div class="container py-lg-5">

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-12">
                        <!-- Card -->
                        <div class="card border-0 shadow-sm">

                            <!-- Card body -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    {{-- booking details --}}

                                    <h3 class="text-center bg-light p-3">@lang('eventsaaspro-pro::em.booking_info')</h3>
                                    <table class="table">
                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.order_id')</th>
                                            <td>{{ $booking['order_number'] }}</td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.event_category')</th>
                                            <td>{{ $booking['event_category'] }}</td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.event')</th>
                                            <td>{{ $booking['event_title'] }}</td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.repetitive')</th>
                                            <td>{{ $booking['event_repetitive'] == 0 ? 'No' : 'Yes' }}</td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.ticket')</th>
                                            <td>{{ $booking['ticket_title'] }}</td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.ticket_price')</th>
                                            <td>{{ $booking['ticket_price'] }}</td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.total_amount_paid')</th>
                                            <td>{{ $currency . ' ' . $booking['net_price'] }}</td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.start_date')</th>
                                            <td>{{ userTimezone($booking['event_start_date'] . ' ' . $booking['event_start_time'], 'Y-m-d H:i:s', format_carbon_date(true)) }}
                                                {{ showTimezone() }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.end_date')</th>
                                            @if (userTimezone($booking['event_start_date'] . ' ' . $booking['event_start_time'], 'Y-m-d H:i:s', 'Y-m-d') <=
                                                userTimezone($booking['event_end_date'] . ' ' . $booking['event_end_time'], 'Y-m-d H:i:s', 'Y-m-d'))
                                                <td>{{ userTimezone($booking['event_end_date'] . ' ' . $booking['event_end_time'], 'Y-m-d H:i:s', format_carbon_date(true)) }}
                                                    {{ showTimezone() }}
                                                </td>
                                            @else
                                                <td>{{ userTimezone($booking['event_start_date'] . ' ' . $booking['event_start_time'], 'Y-m-d H:i:s', format_carbon_date(true)) }}
                                                    {{ showTimezone() }}
                                                </td>
                                            @endif
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.start_time')</th>
                                            <td>
                                                {{ userTimezone($booking['event_start_date'] . ' ' . $booking['event_start_time'], 'Y-m-d H:i:s', format_carbon_date(false)) }}
                                                {{ showTimezone() }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.end_time')</th>
                                            <td>{{ userTimezone($booking['event_end_date'] . ' ' . $booking['event_end_time'], 'Y-m-d H:i:s', format_carbon_date(false)) }}
                                                {{ showTimezone() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.booking_date')</th>
                                            <td>{{ userTimezone($booking['created_at'], 'Y-m-d H:i:s', format_carbon_date(true)) }}
                                                {{ showTimezone() }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.booking_status')</th>
                                            <td><span
                                                    class="label label-success">{{ $booking['status'] == 0 ? 'Inactive' : 'Active' }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.booking_cancellation')</th>
                                            @if ($booking['booking_cancel'] == 0)
                                                <td><span class="label label-info"> @lang('eventsaaspro-pro::em.no_cancellation')</span></td>
                                            @elseif($booking['booking_cancel'] == 1)
                                                <td><span class="label label-info">@lang('eventsaaspro-pro::em.cancellation_pending')</span></td>
                                            @elseif($booking['booking_cancel'] == 2)
                                                <td><span class="label label-info">@lang('eventsaaspro-pro::em.cancellation_approved')</span></td>
                                            @elseif($booking['booking_cancel'] == 3)
                                                <td><span class="label label-info">@lang('eventsaaspro-pro::em.amount_refunded')</span></td>
                                            @endif
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.payment_type')</th>
                                            <td>
                                                @if ($booking['payment_type'] == 'offline')
                                                    <span class="label label-default">{{ $booking['payment_type'] }}</span>
                                                @else
                                                    <span class="label label-success">{{ $booking['payment_type'] }}</span>
                                                @endif
                                            </td>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.paid') </th>
                                            <td>

                                                @if ($booking['is_paid'])
                                                    <span class="label label-success"> @lang('eventsaaspro-pro::em.yes') </span>
                                                @else
                                                    <span class="label label-default"> @lang('eventsaaspro-pro::em.no')</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-12">
                        <!-- Card -->
                        <div class="card mb-4 border-0 shadow-sm">

                            <!-- Card body -->
                            <div class="card-body">
                                {{-- customer details --}}

                                <h3 class="text-center bg-light p-3">@lang('eventsaaspro-pro::em.customer_info')</h3>
                                <hr>
                                <table class="table table-hover">
                                    <tr>
                                        <th>@lang('eventsaaspro-pro::em.name')</th>
                                        <td>{{ $booking['customer_name'] }}</td>
                                    </tr>

                                    <tr>
                                        <th>@lang('eventsaaspro-pro::em.email')</th>
                                        <td>{{ $booking['customer_email'] }}</td>
                                    </tr>

                                </table>

                                {{-- payment information --}}
                                @if (!empty($payment))
                                    <h3 class="text-center bg-light p-3">@lang('eventsaaspro-pro::em.payment_info')</h3>
                                    <table class="table table-hover">
                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.transaction_id')</th>
                                            <td>{{ $payment['txn_id'] }}</td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.payment_type')</th>
                                            <td>{{ $payment['payment_gateway'] }}</td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.payment_status')</th>
                                            <td>{{ $payment['payment_status'] }}</td>
                                        </tr>

                                        <tr>
                                            <th>@lang('eventsaaspro-pro::em.total_amount_paid')</th>
                                            <td>
                                                {{ $payment['currency_code'] }}
                                                {{ $payment['amount_paid'] }}
                                            </td>
                                        </tr>

                                    </table>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>

                @yield('eventsaaspro-bookings-show')

            </div>
        </section>



    </main>
@endsection
