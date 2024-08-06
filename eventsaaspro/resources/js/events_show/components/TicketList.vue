<template>
    <div class="custom_model">
        <div class="modal show" :style="openLoginModal ? 'overflow: hidden': ''" v-if="openModal">
            <div class="modal-dialog" style="max-width: 1060px; width: 100%; height:100%; margin: auto;" :style="openLoginModal ? 'overflow: hidden': ''">
                <div class="modal-content checkout-page">
                    <div class="d-md-flex justify-content-md-between" v-if="showCheckout">
                        <div class="tickets-wrapper">
                            <!-- Checkout Header -->
                            <div class="checkout-header">
                                <span @click="close" class="pointer">
                                    <i class="fa fa-close"></i>
                                </span>
                                <div v-if="!isCheckout">
                                    <!-- Title -->
                                    <div class="title">{{ event.title }}</div>
                                    <div class="date-label"> Starts on {{ eventStartDateTime }} </div>
                                </div>

                                <div v-else>
                                    <!-- Title -->
                                    <div class="title"> Checkout </div>
                                    <div class="checkout-timer" :class="isMinutesLessThanThree ? 'checkout-timer-danger' : 'checkout-timer-normal'"> Time Left: {{ timerCountDown }} </div>
                                </div>
                            </div>

                            <form ref="form" @submit.prevent="validateForm" method="POST">
                                <input type="hidden" class="form-control" name="event_id" :value="tickets[0].event_id" >
                                <input type="hidden" class="form-control" name="booking_date" :value="serverTimezone(booking_date+' '+start_time, 'dddd LL HH:mm a').format('YYYY-MM-DD')" >

                                <input type="hidden" class="form-control" name="booking_end_date"
                                    :value="(booking_end_date != null && typeof(booking_end_date) != 'undefined' && booking_end_date != false) ?
                                    serverTimezone(moment(booking_date).format('dddd LL')+' '+end_time, 'dddd LL HH:mm a').locale('en').format('YYYY-MM-DD') : null"
                                    v-if="!event.merge_schedule"
                                >
                                <input type="hidden" class="form-control" name="booking_end_date"
                                    :value="(booking_end_date != null && typeof(booking_end_date) != 'undefined' && booking_end_date != false) ?
                                    serverTimezone(moment(booking_end_date).format('dddd LL')+' '+end_time, 'dddd LL HH:mm a').locale('en').format('YYYY-MM-DD') : null"
                                    v-else
                                >

                                <input type="hidden" class="form-control" name="start_time" :value="serverTimezone(booking_date+' '+start_time, 'dddd LL HH:mm a').format('HH:mm:ss')" >
                                <input type="hidden" class="form-control" name="end_time" :value="serverTimezone((booking_end_date == null ? booking_date : booking_end_date) +' '+end_time, 'dddd LL HH:mm a').format('HH:mm:ss')" >
                                <input type="hidden" class="form-control" name="merge_schedule" :value="event.merge_schedule" >
                                <input type="hidden" name="customer_id" :value="user ? user.id : 0" v-validate="'required'" >

                                <input type="hidden" name="payment_method" value="2">
                                <input type="hidden" name="source_token" v-model="sourceToken">

                                <div class="checkout-body" v-show="!isCheckout">
                                    <!-- Promo Code -->
                                    <div class="promo-code">
                                        <label for="promo">Promo Code</label>
                                        <input type="text" v-model="coupon" placeholder="Enter Code">
                                        <button class="apply" @click="applyCoupon(true)">Apply</button>
                                    </div>

                                    <ul class="tickets">
                                        <li class="ticket" v-for="(item, index) in tickets" :key="index">
                                            <input type="hidden" class="formbh g-control" name="ticket_id[]" :value="item.id" >
                                            <input type="hidden" class="form-control" name="ticket_title[]" :value="item.title" >

                                            <div class="ticket-header">
                                                <div class="title"> {{ item.title }} </div>
                                                <div class="quantity_action">
                                                    <button class="minus" @click="changeTicketQuantity(index, 'decrement', item.quantity > (item.customer_limit != null ? item.customer_limit :  max_ticket_qty) ? parseInt((item.customer_limit != null ? item.customer_limit :  max_ticket_qty)) : parseInt(item.quantity))">
                                                        <svg width="12" height="2" viewBox="0 0 12 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1 1H11" stroke="#7A7A7A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </button>
                                                    <input type="number" readonly name="quantity[]" v-model="quantity[index]" value="0" min="0" step="1" :max="item.quantity > (item.customer_limit != null ? item.customer_limit :  max_ticket_qty) ? parseInt((item.customer_limit != null ? item.customer_limit :  max_ticket_qty)) : parseInt(item.quantity)">
                                                    <button class="plus" @click="changeTicketQuantity(index, 'increment', item.quantity > (item.customer_limit != null ? item.customer_limit :  max_ticket_qty) ? parseInt((item.customer_limit != null ? item.customer_limit :  max_ticket_qty)) : parseInt(item.quantity))">
                                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M13 8H8V13C8 13.2652 7.89464 13.5196 7.70711 13.7071C7.51957 13.8946 7.26522 14 7 14C6.73478 14 6.48043 13.8946 6.29289 13.7071C6.10536 13.5196 6 13.2652 6 13V8H1C0.734784 8 0.48043 7.89464 0.292893 7.70711C0.105357 7.51957 0 7.26522 0 7C0 6.73478 0.105357 6.48043 0.292893 6.29289C0.48043 6.10536 0.734784 6 1 6H6V1C6 0.734784 6.10536 0.480429 6.29289 0.292893C6.48043 0.105357 6.73478 0 7 0C7.26522 0 7.51957 0.105357 7.70711 0.292893C7.89464 0.480429 8 0.734784 8 1V6H13C13.2652 6 13.5196 6.10536 13.7071 6.29289C13.8946 6.48043 14 6.73478 14 7C14 7.26522 13.8946 7.51957 13.7071 7.70711C13.5196 7.89464 13.2652 8 13 8Z" fill="#F8F8F8"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="ticket-body">
                                                <div class="ticket-price">
                                                    <div class="actual"> {{currency}} {{ item.price > 0 ? item.price : '0.00' }} </div>
                                                    <!-- <div class="fee" v-for="(tax, index1) in item.taxes" :key ="index1">
                                                        {{ countTax(item.price, tax.rate, tax.rate_type, tax.net_price, quantity[index])}} {{ tax.title }}
                                                    </div> -->
                                                    <!-- <div class="sale"> Sales end on Sept 1, 2023 </div> -->
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Billing Address -->
                                <div class="checkout-body" v-if="isCheckout">
                                    <div class="billing-details">
                                        <h3 class="title m-0"> Billing Information </h3>
                                        <p class="loggedin-info"> Logged in as {{billingAddress.email}}. Not you?  </p>

                                        <div class="form">
                                            <div class="row">

                                                <div class="col-12">
                                                    <div class="billing-input-group">
                                                        <label>First Name <span class="required">*</span> </label>
                                                        <input type="text" placeholder="Enter name" name="billing_address_name" v-model="billingAddress.name">
                                                    </div>
                                                </div>

                                                <!-- <div class="col-md-6 col-12">
                                                    <div class="billing-input-group">
                                                        <label>Last Name <span class="required">*</span> </label>
                                                        <input type="text" placeholder="Enter last name" v-model="billingAddress.lastName">
                                                    </div>
                                                </div> -->

                                                <div class="col-12">
                                                    <div class="billing-input-group">
                                                        <label>Email Address <span class="required">*</span> </label>
                                                        <input type="email" name="billing_address_email" placeholder="Enter your email address" v-model="billingAddress.email">
                                                    </div>
                                                </div>

                                                <div class="col-12">

                                                    <div class="billing-checkbox">
                                                        <input type="checkbox" name="billing_address_keep_updated" id="keep-me-updated" v-model="billingAddress.keepMeUpdated">
                                                        <label for="keep-me-updated"> Keep me updated on more events and news from this event organizer. </label>
                                                    </div>

                                                    <div class="billing-checkbox">
                                                        <input type="checkbox" name="billing_address_send_email" id="send-me-email" v-model="billingAddress.sendMeEmail">
                                                        <label for="send-me-email"> Send me emails about the best events happening nearby or online. </label>
                                                    </div>

                                                </div>

                                                <!-- Tickets Detail -->
                                                <!-- <div class="col-12">
                                                    <ul class="tickets-detail">
                                                        <li v-for="(item, index) in tickets" :key="index">
                                                            <h3 class="title">Ticket {{ index + 1 }} - {{ item.title }} </h3>
                                                            <div class="row">

                                                                <div class="col-md-6 col-12">
                                                                    <div class="billing-input-group">
                                                                        <label>First Name <span class="required">*</span> </label>
                                                                        <input type="text" placeholder="Enter first name" v-model="billingAddress.firstName">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6 col-12">
                                                                    <div class="billing-input-group">
                                                                        <label>Last Name <span class="required">*</span> </label>
                                                                        <input type="text" placeholder="Enter last name" v-model="billingAddress.lastName">
                                                                    </div>
                                                                </div>

                                                                <div class="col-12">
                                                                    <div class="billing-input-group">
                                                                        <label>Email Address <span class="required">*</span> </label>
                                                                        <input type="email" placeholder="Enter your email address" v-model="billingAddress.lastName">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div> -->

                                                <!-- Payment -->
                                                <div class="col-12">
                                                    <h3 class="title"> Pay with </h3>

                                                    <div class="mt-2">
                                                        <ul class="payment-methods">
                                                            <li :style="isNotAdminOrOrganizer ? 'cursor: not-allowed' : ''">
                                                                <div class="input-radio-group">
                                                                    <input type="radio" name="payment_type" id="stripe-payment" :disabled="isNotAdminOrOrganizer" v-model="paymentMethod" value="stripe">
                                                                    <label for="stripe-payment" :style="isNotAdminOrOrganizer ? 'cursor: not-allowed' : ''">
                                                                        <div class="content">
                                                                            <span>Credit or debit card</span>
                                                                            <span>
                                                                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                    <path d="M5 18H9M5 21H11M2 7V25H30V7H2Z" stroke="#777777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                    <path d="M2 11V13H30V11H2Z" fill="#777777" stroke="#777777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                </svg>
                                                                            </span>
                                                                        </div>
                                                                    </label>
                                                                </div>

                                                                <div class="row card-wrapper" v-if="paymentMethod === 'stripe'">

                                                                    <StripeAddCard ref="stripeCardComponent" @stripeToken="getStripeToken" :stripe_key="stripe_key" />

                                                                    <!-- <div class="col-12">
                                                                        <div class="card-input" style="margin-bottom: 40px;">
                                                                            <label for="card-number"> Card Number <span class="required">*</span> </label>
                                                                            <input type="text" id="card-number">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-12">
                                                                        <div class="card-input">
                                                                            <label for="card-exp-date"> Expiration Date <span class="required">*</span> </label>
                                                                            <input type="text" id="card-exp-date">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-12">
                                                                        <div class="card-input">
                                                                            <label for="card-cvv"> Security Code <span class="required">*</span> </label>
                                                                            <input type="text" id="card-cvv">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-12">
                                                                        <div class="card-input">
                                                                            <label for="card-zip"> Zip Code <span class="required">*</span> </label>
                                                                            <input type="text" id="card-zip">
                                                                        </div>
                                                                    </div> -->

                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="terms-text">
                                                        By selecting Place Order, I agree to the <a href="#">EventBrite Terms of Service</a>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <button class="back-button" @click="backToCheckout()"> Back </button>
                                                    <button type="button" @click="bookTickets()" class="place-order-button">Place Order</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout-footer d-md-block d-none" v-if="!isCheckout">
                                    <button class="checkout" @click="proceedToCheckout()">Checkout</button>
                                </div>
                            </form>

                            <!-- Guest Login or Register -->
                            <div class="custom_model">
                                <div class="modal show" v-if="openLoginModal">
                                    <div class="modal-dialog" style="max-width: 400px; width: 100%; margin: auto">
                                        <div class="modal-content checkout-page">
                                            <div>
                                                <div class="checkout-header d-flex align-items-center justify-content-between">
                                                    Enter Details
                                                    <span @click="closeRegisterModal" class="pointer">
                                                        <i class="fa fa-close"></i>
                                                    </span>
                                                </div>

                                                <div class="guest-login" style="padding: 20px;">
                                                    <div class="row" v-if="userExist === null">
                                                        <form @submit.prevent="checkEmail()">
                                                            <div class="col-12">
                                                                <input type="email" v-model="newUser.email" placeholder="Please enter your email" />
                                                            </div>
                                                            <button type="submit"> Next </button>
                                                        </form>
                                                    </div>
                                                    <div class="row" v-if="userExist === true">
                                                        <h5>Please login first</h5>
                                                        <form @submit.prevent="loginUserFirst()">
                                                            <input type="text" class="email" v-model="newUser.email" placeholder="Please enter your email" />
                                                            <input type="password" v-model="newUser.password" placeholder="Enter password" />
                                                            <button type="submit"> Login </button>
                                                        </form>
                                                    </div>
                                                    <div class="row" v-if="userExist === false">
                                                        <h5>Please register first</h5>
                                                        <form @submit.prevent="registerUserFirst()">
                                                            <input type="name" v-model="newUser.name" placeholder="Please enter your name" />
                                                            <input type="text" class="email" v-model="newUser.email" placeholder="Please enter your email" />
                                                            <input type="password" v-model="newUser.password" placeholder="Enter password" />
                                                            <button type="submit"> Register </button>
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Guest Login or Register -->

                        </div>
                        <div class="summary-wrapper">
                            <div style="max-width: 100%; width: 100%; margin-bottom: 30px;">
                                <img :src="`${imageBaseUrl}checkout/checkout.png`" style="width: 100%; height: 100%; object-fit: cover; object-position: center;" alt="...">
                            </div>

                            <div class="order-summary">
                               Order Summary
                            </div>
                            <div class=summary-table>
                                <table class="table">
                                    <tr v-for="(item, index) in tickets" :key="index">
                                        <template v-if="quantity[index] > 0">
                                            <td> {{ quantity[index] }} x {{ item.title }} </td>
                                            <td> {{ item.price }} </td>
                                        </template>
                                    </tr>
                                    <tr class="tr-border">
                                        <td>Sub Total</td>
                                        <td> {{currency}}{{ parseFloat(subTotal).toFixed(2) }} </td>
                                    </tr>
                                    <tr>
                                        <td>Tax</td>
                                        <td> {{ currency }}{{ parseFloat(taxFee).toFixed(2) }} </td>
                                    </tr>
                                    <tr>
                                        <td>Service Fee</td>
                                        <td> {{ currency }}{{ parseFloat(serviceFee).toFixed(2) }} </td>
                                    </tr>
                                    <tr v-if="couponDiscount > 0">
                                        <td>Coupon Discount</td>
                                        <td> {{ currency }}{{ parseFloat(couponDiscount).toFixed(2) }} </td>
                                    </tr>
                                    <tr class="tr-border total">
                                        <td>Total</td>
                                        <td> {{currency}}{{ total }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="checkout-footer d-md-none d-block" v-if="!isCheckout">
                            <button class="checkout" @click="proceedToCheckout()">Checkout</button>
                        </div>
                    </div>
                    <div v-if="isCheckoutTimeOut" class="checkout-message">
                        <div class="text-center">
                            <h1> Time Limit Reached </h1>
                            <p> Your reservation has been released. Please re-start your purchase. </p>

                            <button @click="close">Back to Tickets</button>
                        </div>
                    </div>
                    <div v-if="isCheckoutSuccess" class="checkout-message">
                        <div class="text-center">
                            <div class="text-center mb-5">
                                <svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0 21.5C0 15.7978 2.26517 10.3292 6.2972 6.2972C10.3292 2.26517 15.7978 0 21.5 0C27.2022 0 32.6708 2.26517 36.7028 6.2972C40.7348 10.3292 43 15.7978 43 21.5C43 27.2022 40.7348 32.6708 36.7028 36.7028C32.6708 40.7348 27.2022 43 21.5 43C15.7978 43 10.3292 40.7348 6.2972 36.7028C2.26517 32.6708 0 27.2022 0 21.5ZM20.2731 30.702L32.6513 15.2277L30.4153 13.4389L19.8603 26.6285L12.384 20.3992L10.5493 22.6008L20.2731 30.7049V30.702Z" fill="#4ECB71"/>
                                </svg>
                            </div>
                            <h1 class="text-center"> Thank you! </h1>
                            <p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Non, voluptatibus? </p>

                            <div class="redirecting-text"> We are redirecting you to booking listing page </div>

                            <!-- <button @click="close">Back to Tickets</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

import { mapState, mapMutations} from 'vuex';
import mixinsFilters from '../../mixins.js';
import _ from 'lodash';
import StripeAddCard from './StripeAddCard.vue';
import USAePay from './USAePay.vue';

export default {

    mixins:[
        mixinsFilters
    ],

    components: {
        USAePay, StripeAddCard
    },
    props : [
        'tickets',
        'max_ticket_qty',
        'event',
        'currency',
        'login_user_id',
        'is_admin',
        'is_organiser',
        'is_customer',
        'is_paypal',
        'is_offline_payment_organizer',
        'is_offline_payment_customer',
        'booked_tickets',
        'is_usaepay',
        'user',
        'stripe_key',
        'eventStartDateTime',
        'csrf_token'
    ],

    data() {
        return {
            openModal           : false,
            ticket_info         : false,
            moment              : moment,
            quantity            : [1],
            price               : null,
            total_price         : [],
            customer_id         : 0,
            total               : 0,
            disable             : false,
            payment_method      : 'offline',

            // customers options
            options             : [],
            //selected customer
            customer            : null,

            cardName    : "",
            cardNumber  : "",
            cardMonth   : "",
            cardYear    : "",
            cardCvv     : "",

            isCheckout  : false,
            billingAddress: {
                name: '',
                email: '',
                keepMeUpdated: false,
                sendMeEmail: false,
                ticketsDetail: []
            },

            currentTime: 15 * 60,

            paymentMethod: 'stripe',
            sourceToken: null,

            isCheckoutTimeOut: false,
            isCheckoutSuccess: false,

            serviceFee: 0,
            imageBaseUrl: window.config.s3Url,

            coupon: '',
            taxFee: 0,
            couponDiscount: 0,

            openLoginModal: false,

            newUser: {
                email: '',
                name: '',
                password: '',
            },
            userExist: null,
        }
    },

    computed: {
        // get global variables
        ...mapState( ['booking_date', 'start_time', 'end_time', 'booking_end_date', 'booked_date_server']),

        timerCountDown() {
            const minutes = Math.floor(this.currentTime / 60);
            const seconds = this.currentTime % 60;
            return `${this.padTime(minutes)}:${this.padTime(seconds)}`;
        },

        isMinutesLessThanThree () {
            const minutes = Math.floor(this.currentTime / 60);
            return this.padTime(minutes) <= 3;
        },

        subTotal() {
            let subTotal = 0;
            this.quantity.forEach((item, index) => {
                if (item > 0) {
                    subTotal += this.tickets[index].price * item;
                }
            })

            return subTotal;
        },

        // taxFee() {
        //     let fee = 0;
        //     fee = this.total - this.subTotal - this.serviceFee
        //     return fee;
        // },

        showCheckout() {
            return !this.isCheckoutTimeOut && !this.isCheckoutSuccess
        },

        isNotAdminOrOrganizer() {
            return this.user && this.user.role_id ? [1,3].includes(this.user.role_id) : false;
        }
    },

    methods: {
        // update global variables
        ...mapMutations(['add', 'update']),

        // reset form and close modal
        close: function () {
            this.price          = null;
            this.quantity       = [];
            this.total_price    = [];

            this.add({
                booking_date        : null,
                booked_date_server  : null,
                booking_end_date    : null,
                start_time          : null,
                end_time            : null,
            })


            this.openModal      = false;
            if (localStorage.getItem('newLogin')) {
                localStorage.removeItem('newLogin');
                window.location.reload();
            }
        },

        closeRegisterModal() {
            this.newUser = {
                email: '',
                name: '',
                password: '',
            },
            this.userExist = null,
            this.openLoginModal = false;
        },

        getStripeToken (token) {
            this.sourceToken = token.id;
        },

        wait(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },

        async bookTickets(){
            if (this.billingAddress.email === "" || this.billingAddress.name === "") {
                this.showNotification('error', "Please fill billing information")
                return;
            }
            // show loader
            this.showLoaderNotification(trans('em.processing'));

            // prepare form data for post request
            this.disable = true;

            if (this.isNotAdminOrOrganizer) {
                this.bookingApiCall();
            } else {

                await this.$refs.stripeCardComponent.submit();

                await this.wait(3000);

                if (this.sourceToken && this.sourceToken !== null) {
                    this.bookingApiCall();

                } else {
                    this.showNotification('error', 'Something went wrong');
                }

            }
        },

        bookingApiCall () {
            let post_url = route('eventsaaspro.bookings_book_tickets');
            let post_data = new FormData(this.$refs.form);

            // axios post request
            axios.post(post_url, post_data)
            .then(res => {
                if(res.data.status && res.data.message != ''  && typeof(res.data.message) != "undefined") {

                    // hide loader
                    Swal.hideLoading();

                    // close popup
                    // this.close();
                    this.isCheckoutSuccess = true;
                    this.showNotification('success', res.data.message);
                    setTimeout(() => {
                        window.location.href = res.data.url;
                    }, 2000);

                }
                else if(!res.data.status && res.data.message != '' && res.data.url != ''  && typeof(res.data.url) != "undefined"){

                    // hide loader
                    Swal.hideLoading();

                    // close popup
                    this.close();
                    this.showNotification('error', res.data.message);

                    // setTimeout(() => {
                    //     window.location.href = res.data.url;
                    // }, 1000);
                }

                if(res.data.url != '' && res.data.status  && typeof(res.data.url) != "undefined") {

                    // hide loader
                    Swal.hideLoading();

                    this.isCheckoutSuccess = true;
                    setTimeout(() => {
                        window.location.href = res.data.url;
                    }, 2000);
                }

                if(!res.data.status && res.data.message != ''  && typeof(res.data.message) != "undefined") {

                    // hide loader
                    Swal.hideLoading();

                    // close popup
                    this.close();
                    this.showNotification('error', res.data.message);

                }

            })
            .catch(error => {
                this.disable = false
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {

                    this.serverValidate(serrors);

                }
            });
},

        // validate data on form submit
        validateForm(e) {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.disable = true;
                    // this.formSubmit(e);
                }
                else{
                    this.disable = false;
                }
            });
        },

        // show server validation errors
        serverValidate(serrors) {
            this.disable = false;
            this.$validator.validateAll().then((result) => {
                this.$validator.errors.add(serrors);
            });
        },


        // count total tax
        countTax(price, tax, rate_type, net_price, quantity) {

            price           = parseFloat(price).toFixed(2);
            tax             = parseFloat(tax).toFixed(2);
            var total_tax   = parseFloat(quantity * tax).toFixed(2);


                // in case of percentage
                if(rate_type == 'percent')
                {
                    if(isNaN((price * total_tax)/100))
                        return 0;

                    total_tax = (parseFloat((price*total_tax)/100)).toFixed(2);

                    if(net_price == 'excluding')
                        return total_tax+' '+this.currency+' ('+tax+'%'+' '+trans('em.exclusive')+')';
                    else
                        return total_tax+' '+this.currency+' ('+tax+'%'+' '+trans('em.inclusive')+')';
                }

                // for fixed tax
                if(rate_type == 'fixed')
                {
                    if(net_price == 'excluding')
                        return total_tax+' '+this.currency+' ('+tax+' '+this.currency+' '+trans('em.exclusive')+')';
                    else
                        return total_tax+' '+this.currency+' ('+tax+' '+this.currency+' '+trans('em.inclusive')+')';
                }

            return 0;
        },

        countTax2(price, tax, rate_type, quantity) {

            price           = parseFloat(price).toFixed(2);
            tax             = parseFloat(tax).toFixed(2);
            var total_tax   = parseFloat(quantity * tax).toFixed(2);

            if(rate_type == 'percent') {
                if(isNaN((price * total_tax)/100))
                    return 0;

                return (parseFloat((price*total_tax)/100)).toFixed(2);
            }

            if(rate_type == 'fixed')
            {
                return +total_tax;
            }

            return 0;
        },

        // count total price
        totalPrice(){
            if(this.quantity != null || this.quantity.length > 0)
            {
                let amount;
                let tax;
                let total_tax ;
                this.quantity.forEach(function(value, key) {
                    total_tax               = 0;
                    this.total_price[key]   = [];

                    amount                  = (parseFloat(value * this.tickets[key].price)).toFixed(2);

                    // when have no taxes set set total_price with actual ammount without taxes
                    if(Object.keys(this.total_price).length > 0)
                    {
                        this.total_price.forEach(function(v, k){

                            if(Object.keys(v).length <= 0);
                                this.total_price[key] = amount;

                        }.bind(this))
                    }
                    if(this.tickets[key].taxes.length > 0 && amount > 0) {

                        this.tickets[key].taxes.forEach(function(tax_v, tax_k) {
                                    // in case of percentage
                            if(tax_v.rate_type == 'percent')
                            {
                                // in case of excluding
                                if(tax_v.net_price == 'excluding')
                                {
                                    tax = isNaN((amount * tax_v.rate)/100) ? 0 : (parseFloat((amount*tax_v.rate)/100)).toFixed(2);

                                    total_tax   =  parseFloat(total_tax) + parseFloat(tax);
                                }
                            }

                            // // in case of percentage
                            if(tax_v.rate_type == 'fixed')
                            {
                                tax   = parseFloat(value *tax_v.rate);

                                // // in case of excluding
                                if(tax_v.net_price == 'excluding')
                                    total_tax   = parseFloat(total_tax) + parseFloat(tax);

                            }

                        }.bind(this))
                    }

                    this.total_price[key] = (parseFloat(amount) + parseFloat(total_tax)).toFixed(2);

                }.bind(this));
            }
        },

        updateItem() {
            this.$emit('changeItem');
        },

        setDefaultQuantity() {
            // only set default value once
            var _this   = this;
            var promise = new Promise(function(resolve, reject) {
                // only set default value once
                if(_this.quantity.length == 1) {
                    _this.tickets.forEach(function(value, key) {
                        if(key == 0)
                            _this.quantity[key] = 0;
                        else
                            _this.quantity[key] = 0;

                    }.bind());
                }
                resolve(true);
            });

            promise.then(function(successMessage) {
                _this.totalPrice();
                _this.orderTotal();
            }, function(errorMessage) {

            });
        },

        // count prise all booked tickets
        orderTotal() {
            this.total = 0
            if(Object.keys(this.total_price).length > 0)
            {
                this.total_price.forEach(function(value, key){

                    this.total = (parseFloat(this.total) + parseFloat(value)).toFixed(2);

                }.bind(this))

                return this.total;
            }
            return 0;
        },

        // total booked tickets
        bookedTicketsTotal() {
            let  total = 0
            if(this.quantity.length > 0)
            {
                this.quantity.forEach(function(value, key){
                    total = parseInt(total) + parseInt(value);

                }.bind(this))

                return total;
            }
            return 0;
        },

        defaultPaymentMethod() {
            // if not admin
            // total > 0
            if(this.is_admin <= 0 && this.bookedTicketsTotal() > 0)
                this.payment_method = 1;
        },

        loginFirst() {
            window.location.href = route('eventsaaspro.login_first');
        },
        signupFirst() {
            window.location.href = route('eventsaaspro.signup_first');
        },

        // get customers

        getCustomers(loading, search = null){
            var postUrl     = route('eventsaaspro.get_customers');
            var _this       = this;
            axios.post(postUrl,{
                'search' :search,
            }).then(res => {

                var promise = new Promise(function(resolve, reject) {

                    _this.options = res.data.customers;

                    resolve(true);
                })

                promise
                    .then(function(successMessage) {
                        loading(false);
                    }, function(errorMessage) {
                    //error handler function is invoked
                        console.log(errorMessage);
                    })
            })
            .catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            });
        },

        // v-select methods
        onSearch(search, loading) {
            loading(true);
            this.search(loading, search, this);
        },

        // v-select methods
        search: _.debounce((loading, search, vm) => {

            if(vm.validateEmail(search))
                vm.getCustomers(loading, search);
            else
                loading(false);

        }, 350),

        validateEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        scrollToBottom() {
            this.$refs["bottom-down"].scrollIntoView({ block: "end", inline: "nearest" })
        },

        changeTicketQuantity(index, action, limit) {
            if (action === 'increment') {
                if(this.quantity[index] < limit && this.quantity[index] >= 0){
                    this.$set(this.quantity, index, this.quantity[index] + 1);
                }
            } else if (action === 'decrement' && this.quantity[index] > 0) {
                if(this.quantity[index] <= limit && this.quantity[index] > 0){
                    this.$set(this.quantity, index, this.quantity[index] - 1);
                }
            }
        },

        padTime(time) {
            return time < 10 ? `0${time}` : time;
        },

        startTimer () {
            this.timer = setInterval(() => {
                if (this.currentTime > 0) {
                    this.currentTime -= 1;
                } else {
                    if (!this.isCheckoutSuccess) {
                        this.isCheckoutTimeOut = true
                    }
                }
            }, 1000);
        },

        proceedToCheckout () {
            let count = 0;
            this.quantity.forEach(item => {
                if (item === 0) {
                    count ++;
                }
            })

            if (count === this.quantity.length) {
                this.showNotification('error', 'Please Select any ticket');
                return;
            }

            if (this.user !== null) {
                this.startTimer();
                this.isCheckout = true;
            } else {
                this.openLoginModal = true;
                this.overflowHidden = true;
            }


        },

        backToCheckout () {
            this.isCheckout = false;
            clearInterval(this.timer);
            this.currentTime = 15 * 60;
        },

        calculateServiceFee() {
            let fee = 0
            this.quantity.forEach((item, index) => {
                if (item > 0) {
                    this.tickets[index].taxes.forEach((tax) => {
                        if (tax.comic_book_fee === 1) {
                            fee += +this.countTax2(this.tickets[index].price, tax.rate, tax.rate_type, this.quantity[index]);
                        }
                    })
                }
            })

            this.serviceFee = fee;
        },

        calculateTaxFee() {
            let taxFee = 0
            this.quantity.forEach((item, index) => {
                if (item > 0) {
                    this.tickets[index].taxes.forEach((tax) => {
                        if (tax.comic_book_fee !== 1) {
                            taxFee += +this.countTax2(this.tickets[index].price, tax.rate, tax.rate_type, this.quantity[index]);
                        }
                    })
                }
            })

            this.taxFee = taxFee;
        },

        applyCoupon (showSuccessToast = false) {
            const foundCoupon = this.event.coupon.some((item) => { return this.coupon === item.title });

            if (foundCoupon) {
                let coupon = this.event.coupon.find((item) => { return this.coupon === item.title });

                let currentDate = new Date();
                var startDate = new Date(coupon.start_date);
                var expireDate = new Date(coupon.expire_date);

                var isDateInRange = currentDate >= startDate && currentDate <= expireDate;

                if (isDateInRange) {
                    let couponDiscount = 0;
                    let count = 0;

                    coupon.ticket.forEach(item => {
                        this.tickets.map((ticket, index) => {
                            if (ticket.id === item) {
                                if (this.quantity[index] > 0) {
                                    couponDiscount += +(ticket.price*this.quantity[index]).toFixed(2)

                                    if (coupon.type === 'fixed') {
                                        this.couponDiscount = couponDiscount.toFixed(2);
                                        this.total = this.total - this.couponDiscount;
                                    }

                                    if (coupon.type === 'percent') {
                                        let amount = coupon.amount.toFixed(2);
                                        let discount = (parseFloat((couponDiscount*amount)/100)).toFixed(2)
                                        this.couponDiscount = +discount;
                                        this.total = parseFloat(this.total - discount).toFixed(2)
                                    }
                                }
                                if (this.quantity[index] === 0) {
                                    count++;
                                }

                                if (count === coupon.ticket.length) {
                                    this.couponDiscount = 0;
                                }
                            }
                        });
                    });

                    if (showSuccessToast) {
                        this.showNotification('success', 'Coupon has been applied')
                    }
                } else {
                    if (showSuccessToast) {
                        this.showNotification('error', 'Coupon has been expired or invalid')
                    }
                }

            } else {
                if (showSuccessToast) {
                    this.showNotification('error', 'Opp\'s... Coupon Not Found')
                }
            }
        },

        checkEmail(e) {
            var postUrl     = route('eventsaaspro.check_email');
            var _this       = this;
            axios.post(postUrl,{
                'email' : _this.newUser.email,
            }).then(res => {
                _this.userExist = res.data.status;
            }).catch(err => console.error(err));
        },

        loginUserFirst() {
            // this.showLoaderNotification(trans('em.processing'));
            var postUrl     = route('eventsaaspro.guest_login_post');
            var _this       = this;
            axios.post(postUrl,{
                '_token': _this.csrf_token,
                'email' : _this.newUser.email,
                'password' : _this.newUser.password,
            }).then(res => {
                _this.openLoginModal = false;
                _this.user = res.data.user;

                this.billingAddress.name = this.user.name;
                this.billingAddress.email = this.user.email;

                _this.proceedToCheckout();
                localStorage.setItem('newLogin', "true");
                // Swal.hideLoading();
            }).catch(err => {
                console.error(err);
                // Swal.hideLoading();
            });
        },

        registerUserFirst() {
            // this.showLoaderNotification(trans('em.processing'));
            var postUrl     = route('eventsaaspro.register');
            var _this       = this;
            axios.post(postUrl,{
                'name' : _this.newUser.name,
                'email' : _this.newUser.email,
                'password' : _this.newUser.password,
                'accept' : '1',
            }, {
                headers: {
                    'accept': 'application/json'
                }
            }).then(res => {
                _this.openLoginModal = false;
                _this.user = res.data.user;

                this.billingAddress.name = this.user.name;
                this.billingAddress.email = this.user.email;

                _this.proceedToCheckout();
                localStorage.setItem('newLogin', "true");
                // Swal.hideLoading();
            }).catch(err => {
                console.error(err);
                // Swal.hideLoading();
            });
        },
    },

    watch: {
        quantity: function () {
            this.totalPrice();
            this.orderTotal();
            this.defaultPaymentMethod();
            this.calculateServiceFee();
            this.calculateTaxFee();
            this.applyCoupon();
        },
        tickets: function() {
            this.setDefaultQuantity();
            this.totalPrice();
            this.orderTotal();
            this.calculateServiceFee();
            this.calculateTaxFee();
            this.applyCoupon();
        },

        // active when customer search
        customer: function () {
            this.customer_id = this.customer != null ?  this.customer.id : null;
        },

    },

    mounted() {
        this.openModal = true;
        this.setDefaultQuantity();
        this.defaultPaymentMethod();

        if (this.login_user_id === null) {
            this.billingAddress.name = "";
            this.billingAddress.email = "";
        } else {
            if (this.isNotAdminOrOrganizer) {
                this.billingAddress.name = "";
                this.billingAddress.email = "";
            } else {
                this.billingAddress.name = this.user.name;
                this.billingAddress.email = this.user.email;
            }
        }
    },
}
</script>

<style>
.guest-login input{
    width: 100%;
    background-color: #F8F8F8;
    border: 1px solid #BCBCBC;
    border-radius: 6px;
    padding: 10px 18px;
    margin-bottom: 17px
}
.guest-login button {
    padding: 10px;
    max-width: 150px;
    width: 100%;
    border-radius: 8px;
    font-family: "Poppins", sans-serif;
    font-weight: 600;
    font-size: 16px;
    line-height: 1.3;
    background-color: #6C2BD9;
    border-color: #6C2BD9;
    margin-left: auto;
    color: #FFFFFF
}
</style>
