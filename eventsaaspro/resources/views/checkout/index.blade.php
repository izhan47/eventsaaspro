@extends('eventsaaspro::layouts.app')

{{-- Page title --}}

@section('content')

<main>

    <section class="checkout-page border" style="margin-top: 60px;">
        <div class="d-flex" style="max-width: 1060px; margin: 0 auto">
            <div style="max-width: 716px; width: 100%;">
                <!-- Checkout Header -->
                <div class="checkout-header">
                    <!-- Title -->
                    <div class="title">The Arts First Friday Edition - Art - Music - Food - Dance - Poetry - Film</div>
                    <div class="date-label"> Starts on Friday, September 1 - 10pm EDT </div>
                </div>
                <div class="checkout-body">
                    <!-- Promo Code -->
                    <div class="promo-code">
                        <label for="promo">Promo Code</label>
                        <input type="text" placeholder="Enter Code">
                        <button class="apply">Apply</button>
                    </div>

                    <ul class="tickets">
                        <li class="ticket">
                            <div class="ticket-header">
                                <div class="title"> Advanced General Admission (non refundable) </div>
                                <div class="quantity_action">
                                    <button class="minus">-</button>
                                    <input type="number">
                                    <button class="plus">+</button>
                                </div>
                            </div>
                            <div class="ticket-body">
                                <div class="ticket-price">
                                    <div class="actual"> $10.00 </div>
                                    <div class="fee"> +$2.45 Fee </div>
                                    <div class="sale"> Sales end on Sept 1, 2023 </div>
                                </div>
                            </div>
                        </li>
                        <li class="ticket">
                            <div class="ticket-header">
                                <div class="title"> Advanced General Admission (non refundable) </div>
                                <div class="quantity_action">
                                    <button class="minus">-</button>
                                    <input type="number">
                                    <button class="plus">+</button>
                                </div>
                            </div>
                            <div class="ticket-body">
                                <div class="ticket-price">
                                    <div class="actual"> $10.00 </div>
                                    <div class="fee"> +$2.45 Fee </div>
                                    <div class="sale"> Sales end on Sept 1, 2023 </div>
                                </div>
                            </div>
                        </li>
                        <li class="ticket">
                            <div class="ticket-header">
                                <div class="title"> Advanced General Admission (non refundable) </div>
                                <div class="quantity_action">
                                    <button class="minus">-</button>
                                    <input type="number">
                                    <button class="plus">+</button>
                                </div>
                            </div>
                            <div class="ticket-body">
                                <div class="ticket-price">
                                    <div class="actual"> $10.00 </div>
                                    <div class="fee"> +$2.45 Fee </div>
                                    <div class="sale"> Sales end on Sept 1, 2023 </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="checkout-footer">
                    <button>Checkout</button>
                </div>
            </div>
            <div style="max-width: 344px; width: 100%;">
                <div style="max-width: 344px; width: 100%;">
                    <img src="/storage/checkout/checkout.png" style="width: 100%; height: 100%; object-fit: cover; object-position: center;" alt="...">
                </div>
            </div>
        </div>
    </section>

</main>
@endsection
