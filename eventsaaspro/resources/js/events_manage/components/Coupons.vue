<template>
  <div>
    <div class="mb-4">
        <button class="btn btn-primary btn-md ms-auto" @click="showCouponForm = true">+ Add New Coupon</button>
    </div>
    <form ref="form" @submit.prevent="isUpdate ? updateCoupon() : saveCoupon()" method="POST" enctype="multipart/form-data" class="lgx-contactform mb-4" v-if="showCouponForm">
        <input type="hidden" name="event_id" v-model="event_id">

        <div class="mb-3">
            <label class="form-label"> Coupon Title </label>
            <input type="text" class="form-control" name="title" v-model="coupon.title" v-validate="'required'">
            <!-- <span v-show="errors.has('title')" class="help text-danger">{{ errors.first('title') }}</span> -->
        </div>

        <div class="mb-3">
            <label class="form-label"> Coupon Amount </label>
            <input type="number" class="form-control" name="amount" v-model="coupon.amount" v-validate="'required'">
            <!-- <span v-show="errors.has('title')" class="help text-danger">{{ errors.first('title') }}</span> -->
        </div>

        <div class="mb-3">
            <label class="form-label"> Coupon Type </label>

            <div>
                <input type="radio" v-model="coupon.type" name="type" id="coupon-type-1" value="fixed"> 
                <label for="coupon-type-1">Fixed</label>
                
                <input type="radio" v-model="coupon.type" name="type" id="coupon-type-2" value="percent"> 
                <label for="coupon-type-2">Percentage</label>
            </div>
            <!-- <span v-show="errors.has('title')" class="help text-danger">{{ errors.first('title') }}</span> -->
        </div>

        <div class="mb-3">
            <label class="form-label"> Start Date </label>
            <input type="date" class="form-control" name="start_date" v-model="coupon.start_date" v-validate="'required'">
            <!-- <span v-show="errors.has('title')" class="help text-danger">{{ errors.first('title') }}</span> -->
        </div>

        <div class="mb-3">
            <label class="form-label"> Expire Date </label>
            <input type="date" class="form-control" name="expire_date" v-model="coupon.expire_date" v-validate="'required'">
            <!-- <span v-show="errors.has('title')" class="help text-danger">{{ errors.first('title') }}</span> -->
        </div>

        <div class="mb-3">
            <label class="form-label"> Tickets </label>
            <v-select
                multiple
                label="title" 
                class="style-chooser" 
                :placeholder="'Select Tickets'"
                v-model="coupon.ticket" 
                :options="tickets" 
            ><div slot="no-options">Ticket not found </div></v-select>
            <!-- <span v-show="errors.has('title')" class="help text-danger">{{ errors.first('title') }}</span> -->
        </div>

        <div class="mb-3">
            <label class="form-label"> Quantity </label>
            <input type="number" class="form-control" name="quantity" v-model="coupon.quantity" v-validate="'required'">
            <!-- <span v-show="errors.has('title')" class="help text-danger">{{ errors.first('title') }}</span> -->
        </div>

        <div class="mb-3">
            <label class="form-label"> Quantity Sold </label>
            <input type="number" class="form-control" name="quantity_sold" v-model="coupon.quantity_sold" v-validate="'required'">
            <!-- <span v-show="errors.has('title')" class="help text-danger">{{ errors.first('title') }}</span> -->
        </div>

        <button class="btn btn-info btn-lg mt-2" @click="showCouponForm = false">Cancel</button>
        <button type="submit" class="btn btn-primary btn-lg mt-2"><i class="fas fa-sd-card"></i> {{ !isUpdate ? trans('em.save') : 'Update' }}</button>

    </form>

    <div class="table-responsive">
        <table class="table text-wrap">
            <thead class="table-light text-nowrap">
                <tr>
                    <th>Title</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Tickets</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in eventCoupons" :key="index">
                    <td> {{ item.title }} </td>
                    <td> {{ item.amount }} </td>
                    <td> {{ item.type }} </td>
                    <td> {{ item.start_date }} </td>
                    <td> {{ item.expire_date || "Unlimited" }} </td>
                    <td> {{ item.ticket }} </td>
                    <td> {{ !!item.status ? 'Active' : 'In-active' }} </td>
                    <td> <button class="btn btn-secondary btn-md ml-auto" @click="setCouponforUpdate(item)"> Update </button> </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="px-4 pb-4"  v-if="eventCoupons.length > 0">
        <pagination-component v-if="pagination.last_page > 1" :pagination="pagination" :offset="pagination.total" :path="'/coupons'" @paginate="getCoupons()">
        </pagination-component>
    </div>
  </div>
</template>

<script>
import _ from 'lodash';
import { mapState, mapMutations} from 'vuex';
import mixinsFilters from '../../mixins.js';
import axios from 'axios';
import PaginationComponent from '../../common_components/Pagination'

export default {
    name: "Coupons",
    mixins:[ mixinsFilters ],
    components: { PaginationComponent },
    data() {
        return{
            showCouponForm: false,
            coupon: {
                event_id: null,
                title: "",
                amount: null,
                type: "fixed",
                start_date: "",
                expire_date: "",
                ticket: [],
                quantity: 0,
                quantity_sold: 0,
                status: 1
            },
            tickets: [],
            eventCoupons: [],
            pagination: {
                'current_page': 1
            },
            currentURL: '',
            isUpdate: false,
        };
    },
    mounted() {
        this.getEventTickets();
        this.getCoupons();
    },
    computed: {
        // get global variables
        ...mapState( ['event_id']),
        current_page() {
            // get page from route
            if(typeof this.page === "undefined")
                return 1;
            return this.page;
        },
    },
    methods: {
        async getEventTickets () {
            await axios.get(`/dashboard/myevents/api/get-events-tickets/${this.event_id}`).then(response => {
                this.tickets = response.data.tickets;
            })
        },

        async saveCoupon() {
            this.coupon.event_id = this.event_id;
            this.coupon.ticket = this.coupon.ticket.length > 0 ? this.coupon.ticket.map(item => item.id) : [];

            await axios.post('/dashboard/myevents/api/store-event-coupon', this.coupon).then((response) => {
                if(response.data.status)
                {
                    this.showNotification('success', response.data.message);

                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    this.showNotification('error', response.data.message);
                }
            }).catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            });
        },

        async getCoupons () {
            this.currentURL = window.location.pathname;
            console.log("currentUrl: ", this.currentURL);
            
            await axios.get(`/dashboard/myevents/api/event-coupons/${this.event_id}/100`).then(response => {
                
                this.eventCoupons   = response.data.coupon.data;
                this.pagination = {
                    'total' : response.data.coupon.total,
                    'per_page' : response.data.coupon.per_page,
                    'current_page' : response.data.coupon.current_page,
                    'last_page' : response.data.coupon.last_page,
                    'from' : response.data.coupon.from,
                    'to' : response.data.coupon.to
                };
            })
        },

        setCouponforUpdate(coupon) {
            this.coupon = coupon;
            this.showCouponForm = true;
            this.isUpdate = true;
        },
        
        async updateCoupon() {
            await axios.post('/dashboard/myevents/api/update-event-coupon/'+this.coupon.id, this.coupon).then((response) => {
                if(response.data.status)
                {
                    this.showNotification('success', response.data.message);

                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    this.showNotification('error', response.data.message);
                }
            }).catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            });
        }
    }
}
</script>

<style>

</style>