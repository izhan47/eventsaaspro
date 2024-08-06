<template>
<div :class="event && event.status === 2 ? 'complete-event' : ''">
    <div v-if="event && event.status === 2"> You can not edit comedians because event is completed </div>
    <div class="d-flex align-items-center justify-content-between">
        <h5> Comedians </h5>
        <button class="btn btn-primary btn-lg" @click="openModalAddComedian = true">Add New Comedian</button>
    </div>

    <AddNewComedian :isOpen="openModalAddComedian" />
    
    <form ref="form" @submit.prevent="addComedian" method="POST" enctype="multipart/form-data"  class="lgx-contactform">
        <input type="hidden" name="event_id" v-model="event_id">

        <div class="form-group mb-3">
            <label for="" class="form-label mb-2">Select Comedian</label>
            <v-select label="name" :options="comedianList" v-model="selectedComedian"></v-select>
        </div>

        <div v-if="selectedComedian !== null" class="d-flex align-items-center mb-3" style="flex-wrap: wrap; gap: 15px;">
            <div class="form-check" v-for="(item, index) in paymentList" :key="index">
                <input class="form-check-input" :id="`payment-${item.value}-${index}`" type="checkbox" v-model="selectPayment[item.value]" :value="item.value" name="payment-type">
                <label class="form-check-label" :for="`payment-${item.value}-${index}`">
                    {{ item.text }}
                </label>
            </div>
        </div>

        <div v-if="selectPayment.isFixed" class="mb-3">
            <!-- <label class="form-label" for="fixed-amount">Enter Fixed Amount:</label>
            <input type="text" id="fixed-amount" class="form-control" @input="onlyNumeric()" v-model="fixedAmount"> -->
            <table class="table">
                <thead>
                    <th>Amount</th>
                    <th>Minimum Person</th>
                    <th>Maximum Person</th>
                    <th>Unlimited</th>
                    <th class="text-end">Actions</th>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in fixedAmount" :key="index">
                        <td><input type="text" class="form-control" @input="onlyNumeric('amount', index)" v-model="item.amount"></td>
                        <td>
                            <input type="text" class="form-control" @input="onlyNumeric('min_person', index)" v-model="item.min_person">
                            <small class="d-none text-danger" :id="`fixed-error-${index}`">Min. person should be greater than to previously added max person</small>
                        </td>
                        <td><input type="text" class="form-control" :disabled="item.unlimited" @input="onlyNumeric('max_person', index)" v-model="item.max_person"></td>
                        <td>
                            <label>
                                <input type="checkbox" v-model="item.unlimited" :value="true"/>
                                Check if you want to Maximum Person to infinite
                            </label>
                        </td>
                        <td align="right">
                            <button type="button" @click.prevent="addFixedRow()" v-if="fixedAmount.length -1 === index" class="btn btn-primary">add</button>
                            <button type="button" @click="removeFixedRow(index)" v-if="fixedAmount.length > 1" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="selectPayment.isPercentage" class="mt-5 mb-3">
            <!-- <label class="form-label" for="percentage">Enter percentage %:</label>
            <input type="text" v-model="percentage" id="percentage" class="form-control" @input="onlyNumeric2()"> -->
            <table class="table">
                <thead>
                    <th>Percentage %</th>
                    <th>Minimum Person</th>
                    <th>Maximum Person</th>
                    <th>Unlimited</th>
                    <th class="text-end">Actions</th>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in percentage" :key="index">
                        <td><input type="text" class="form-control" @input="onlyNumeric2('percentage', index)" v-model="item.percentage"></td>
                        <td>
                            <input type="text" class="form-control" @input="onlyNumeric2('min_person', index)" v-model="item.min_person">
                            <small class="d-none text-danger" :id="`percentage-error-${index}`">Min. person should be greater than to previously added max person</small>
                        </td>
                        <td><input type="text" class="form-control" :disabled="item.unlimited" @input="onlyNumeric2('max_person', index)" v-model="item.max_person"></td>
                        <td>
                            <label>
                                <input type="checkbox" v-model="item.unlimited" :value="true"/>
                                Check if you want to Maximum Person to infinite
                            </label>
                        </td>
                        <td align="right">
                            <button type="button" @click.prevent="addPercentageRow()" v-if="percentage.length -1 === index" class="btn btn-primary">add</button>
                            <button type="button" @click="removePercentageRow(index)" v-if="percentage.length > 1" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-primary btn-lg mt-2" :disabled="selectedComedian === null" :style="selectedComedian === null ? 'cursor: not-allowed' : ''" id="add-comedian"> Add Comedian </button>
    </form>

    <!-- <ul class="event-comedians" v-if="eventsComedians && eventsComedians.length > 0">
        <li v-for="(comedian, index) in eventsComedians" :key="index">
            <div class="image">
                <img :src="`${imageBaseUrl}${comedian.user.avatar}`" alt="...">
            </div>
            <div class="title"> {{ comedian.user.name }} </div>
            <div class="email"> {{ comedian.user.email }} </div>

            <div v-if="!!comedian.fixed" class="payment d-block">
                <div class="type"> Payment Fixed:  </div> -->
                <!-- <div class="value"> {{ comedian.fixed_value }} </div> -->
                <!-- <div v-for="(item) in comedian.fixed_value" :key="item.min_person"> ${{item.amount}} at {{item.min_person}} to {{item.max_person}} </div>
            </div>

            <div v-if="!!comedian.percent" class="payment d-block">
                <div class="type"> Payment Percentage:  </div> -->
                <!-- <div class="value"> {{ comedian.percent_value }}% </div> -->
                <!-- <div v-for="(item) in comedian.percent_value" :key="item.percentage"> {{item.percentage}}% at {{item.min_person}} to {{item.max_person}} </div>
            </div>

            <div class="payment">
                <div class="type"> Stage Name: </div>
                <div class="value"> {{ comedian.user.comedian_stage_name }} </div>
            </div>
            <button class="btn btn-danger delete-comedians text-white" @click="deAttachComedian(comedian.user.id)">
                <i class="fa fa-trash"></i>
            </button>
        </li>
    </ul> -->

    <div class="table-responsive mt-5">
        <table id="dataTable" class="table table-hover" v-if="eventsComedians && eventsComedians.length > 0">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Stage Name</th>
                    <th>Email</th>
                    <th>Payment Fixed</th>
                    <th>Payment Percentage</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(comedian, index) in eventsComedians" :key="index">
                    <td>
                        <div class="image">
                            <img :src="`${imageBaseUrl}${comedian.user.avatar}`" width="64" alt="...">
                        </div>
                    </td>
                    <td> {{ comedian.user.name }} </td>
                    <td> {{ comedian.user.comedian_stage_name }} </td>
                    <td> {{ comedian.user.email }} </td>
                    <td v-if="!!comedian.fixed">
                        <ul>
                            <li v-for="(item) in comedian.fixed_value" :key="item.min_person"> ${{item.amount}} at {{item.min_person}} to {{item.max_person}} </li> 
                        </ul>
                    </td>
                    <td v-else> -- </td>
                    <td v-if="!!comedian.percent"> <div v-for="(item) in comedian.percent_value" :key="item.percentage"> {{item.percentage}}% at {{item.min_person}} to {{item.max_person}} </div> </td>
                    <td v-else> -- </td>
                    <td>
                        <button class="btn btn-danger delete-comedians text-white position-relative" @click="deAttachComedian(comedian.user.id)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</template>

<script>
import { mapState } from 'vuex';
import mixinsFilters from '../../mixins.js';
import AddNewComedian from './AddNewComedian.vue';
export default {
    mixins:[
        mixinsFilters
    ],
    components: { AddNewComedian },
    data () {
        return {
            eventsComedians: [],
            comedianList: [],
            selectedComedian: null,
            paymentList: [
                { text: 'Fixed', value: 'isFixed' },
                { text: 'Percent', value: 'isPercentage' },
            ],
            selectPayment: {
                isFixed: false,
                isPercentage: false,
            },
            fixedAmount: [],
            percentage: [],
            event: {},
            imageBaseUrl: window.config.s3Url,
            openModalAddComedian  : false,
        };
    },
    async mounted() {
        await this.getMyEvent();

        await this.getEventsComedians();

        await this.getAllComedians();

        this.addFixedRow();
        this.addPercentageRow();
    },
    computed: {
        // get global variables
        ...mapState( ['event_id', 'organiser_id']),
    },
    methods: {

        async getMyEvent () {
            await axios.post('/dashboard/myevents/api/get_myevent', {
                event_id: this.event_id,
                organiser_id: this.organiser_id
            }).then((response) => {
                this.event = response.data.event;
            })
            .catch(error => {
                console.error(error)
            });
        },

        async getEventsComedians() {
            await axios.get(`/comedians/api/event-comedian/${this.event_id}`)
            .then(res => {
                this.eventsComedians = res.data.comedians || [];
            })
            .catch(error => {
                console.error(error)
            });
        },

        async getAllComedians() {
            await axios.get('/comedians/api/comedians/100')
            .then(res => {
                this.comedianList = res.data.comedians.data
            })
            .catch(error => {
                console.error(error)
            });
        },

        onlyNumeric(key, index) {
            this.fixedAmount[index][key] = this.fixedAmount[index][key].replace(/[^0-9]/g, "");
            this.fixedAmount[index][key] = parseInt(this.fixedAmount[index][key]) || 0;

            if(index > 0 && key === 'min_person') {
                if (this.fixedAmount[index]['min_person'] <= this.fixedAmount[index-1]['max_person']){
                    document.getElementById(`fixed-error-${index}`).classList.remove('d-none');
                } else {
                    document.getElementById(`fixed-error-${index}`).classList.add('d-none')

                }
            }
        },

        onlyNumeric2 (key, index) {
            this.percentage[index][key] = this.percentage[index][key].replace(/[^0-9]/g, "");

            if (parseInt(this.percentage[index].percentage) > 100) {
                this.percentage[index].percentage = "100";
            }

            this.percentage[index][key] = parseInt(this.percentage[index][key]) || 0;

            if(index > 0 && key === 'min_person') {
                if (this.percentage[index]['min_person'] <= this.percentage[index-1]['max_person']){
                    document.getElementById(`percentage-error-${index}`).classList.remove('d-none')
                } else {
                    document.getElementById(`percentage-error-${index}`).classList.add('d-none')

                }
            }
        },

        async addComedian () {
            if (!this.selectPayment.isFixed && !this.selectPayment.isPercentage) {
                this.showNotification('error', 'Please select any payment type');
                return;
            }
            let invalid = false;
            if (this.selectPayment.isFixed) {
                this.fixedAmount.forEach((item, index) => {
                    if (item.amount === 0) {
                        this.showNotification('error', 'Please Enter Amount');
                        invalid = true;
                        return;
                    }

                    if (index > 0 && item.min_person === 0) {
                        this.showNotification('error', 'Please Enter Minimum Person');
                        invalid = true;
                        return;
                    }

                    if (!item.unlimited && item.max_person === 0) {
                        this.showNotification('error', 'Please Enter Maximum Person');
                        invalid = true;
                        return;
                    }

                    if (!item.unlimited && item.max_person < item.min_person) {
                        this.showNotification('error', 'Maximum Person value is less than Minimum Person');
                        invalid = true;
                        return;
                    }
                })
            }

            if (this.selectPayment.isPercentage) {
                this.percentage.forEach((item, index) => {
                    if (item.percentage === 0) {
                        this.showNotification('error', 'Please Enter Percentage');
                        invalid = true;
                        return;
                    }

                    if (index > 0 && item.min_person === 0) {
                        this.showNotification('error', 'Please Enter Minimum Person');
                        invalid = true;
                        return;
                    }

                    if (!item.unlimited && item.max_person === 0) {
                        this.showNotification('error', 'Please Enter Maximum Person');
                        invalid = true;
                        return;
                    }

                    if (!item.unlimited && item.max_person < item.min_person) {
                        this.showNotification('error', 'Maximum Person value is less than Minimum Person');
                        invalid = true;
                        return;
                    }
                })
            }

            if (invalid) return;

            this.fixedAmount.map(item => {
                if (item.unlimited) {
                    item.max_person = 'unlimited';
                }
            });

            this.percentage.map(item => {
                if (item.unlimited) {
                    item.max_person = 'unlimited';
                }
            })

            let formData = {
                event_id: this.event_id,
                user_id: this.selectedComedian.id,
                fixed: this.selectPayment.isFixed,
                fixed_value: this.fixedAmount,
                percent: this.selectPayment.isPercentage,
                percent_value: this.percentage,
            }

            await axios.post('/comedians/api/attach', formData)
            .then((response)=> {
                if (response.data.status) {
                    this.eventsComedians.push(response.data.comedianEvent);
                    this.selectedComedian = null;
                    this.fixedAmount = {};
                    this.percentage = {};
                    this.showNotification('success', response.data.message);
                    // reload page
                    setTimeout(function() {
                        location.reload(true);
                    }, 1000);
                } else {
                    this.showNotification('error', response.data.message);
                }
            })
            .catch(error => {
                Vue.helpers.axiosErrors(error);
            });
        },

        async deAttachComedian (comedianId) {
            let formData = {
                event_id: this.event_id,
                user_id: comedianId,
            }

            await axios.post('/comedians/api/de-attach', formData)
            .then((response)=> {
                if (response.data.status) {
                    this.showNotification('success', response.data.message);
                    // reload page
                    setTimeout(function() {
                        location.reload(true);
                    }, 1000);
                } else {
                    this.showNotification('error', response.data.message);
                }
            })
            .catch(error => {
                Vue.helpers.axiosErrors(error);
            });
        },

        addFixedRow () {
            this.fixedAmount.push({
                amount: 0,
                min_person: 0,
                max_person: 0,
                unlimited: false,
            });
        },

        removeFixedRow(index) {
            this.fixedAmount.splice(index,1)
        },

        addPercentageRow () {
            this.percentage.push({
                percentage: 0,
                min_person: 0,
                max_person: 0,
                unlimited: false,
            });
        },

        removePercentageRow(index) {
            this.percentage.splice(index,1)
        }
    },
}
</script>

<style>

.event-comedians{
    margin: 30px 0;
    padding: 0;
    list-style: none;
    display: flex;
    align-items: center;
    gap: 30px;
    flex-wrap: wrap;
}

.event-comedians li {
    border-radius: 5px;
    padding: 10px 13px 7px 13px;
    box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.1);
    -webkit-box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.1);
    text-align: center;
    position: relative;
    min-height: 255px;
}

.delete-comedians {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 14px;
    border-radius: 4px;
}

.event-comedians li .image{
    width: 64px;
    height: 64px;
    margin: 0 auto;
    border-radius: 50%;
}

.event-comedians li .image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
}

.event-comedians li .title {
    font-family: "Inter", sans-serif;
    font-weight: 600;
    font-size: 16px;
    line-height: 1.3;
    color: #000000;
    margin: 10px 0 0 0;
}

.event-comedians li .email {
    font-family: "Inter", sans-serif;
    font-weight: 400;
    font-size: 16px;
    line-height: 1.3;
    color: #6C757D;
}

.event-comedians li .payment {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    margin: 10px 0;
    font-size: 14px;
    line-height: 1.3;
    font-weight: 400;
}

.event-comedians li .payment .type {
    font-weight: 700;
}

.complete-event{
    opacity: 0.5;
    pointer-events: none;
    cursor: not-allowed;
}
</style>
