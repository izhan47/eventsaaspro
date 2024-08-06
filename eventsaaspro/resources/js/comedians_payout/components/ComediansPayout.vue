<template>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between flex-wrap p-4 bg-white border-bottom-0">
            <div class="d-flex flex-column">
                <div>
                    <h1 class="fw-bold h2"> Comedians Payout </h1>
                </div>

                <!-- Filters -->
                <div class="d-flex">
                    <div class="me-2">
                        <input type="text" class="form-control" v-model="search" placeholder="Enter to search">
                    </div>
                </div>

            </div>
        </div>

        <div class="row mx-3">
            <div class="col-lg-4 col-md-12 col-12">
                <div class="card bg-info mb-4 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div>
                            <h5 class="mb-0 text-white">Unpaid Total</h5>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <h2 class="fw-bold mb-0 fs-1 text-white"> {{currency}}{{comediansUnpaidTotal}} </h2>
                            <i class="fas fa-cart-arrow-down text-white fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-12">
                <div class="card bg-dark mb-4 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div>
                            <h5 class="mb-0 text-white"> Unpaid Count </h5>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <h2 class="fw-bold mb-0 fs-1 text-white"> {{comediansUnpaidCount}} </h2>
                            <i class="fas fa-user-shield text-white fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-12">
                <div class="card bg-success mb-4 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div>
                            <h5 class="mb-0 text-white"> Paid Total </h5>

                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <h2 class="fw-bold mb-0 fs-1 text-white"> {{currency}}{{comediansPaidTotal}} </h2>
                            <i class="fas fa-sack-dollar text-white fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3 d-flex align-items-center justify-content-end pe-3" v-if="allChecked" style="gap: 10px;">
            <button class="btn btn-success" @click="paymentStatusAll(1)"> Pay All Now </button>
            <button class="btn btn-warning" @click="paymentStatusAll(0)"> UnPaid All </button>
        </div>
        <div class="table-responsive">
            <table class="table text-wrap">
                <thead class="table-light text-nowrap">
                    <tr>
                        <th class="border-top-0 border-bottom-0"> <input type="checkbox" v-model="allChecked" @change="selectAll()"> </th>
                        <th class="border-top-0 border-bottom-0"> Event </th>
                        <th class="border-top-0 border-bottom-0"> Comedian Name </th>
                        <th class="border-top-0 border-bottom-0"> Comedian Email </th>
                        <th class="border-top-0 border-bottom-0"> Status </th>
                        <th class="border-top-0 border-bottom-0"> Fixed </th>
                        <th class="border-top-0 border-bottom-0"> Percentage </th>
                        <th class="border-top-0 border-bottom-0"> Amount </th>
                        <th class="border-top-0 border-bottom-0"> Actions </th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in comediansPayoutList" :key="index">
                        <td class="align-middle"> <input type="checkbox" name="comedians-payout" :checked="isSelected(item.id)" @change="toggleCheckbox(item.id)"> </td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <a :href="eventSlug(item.event.slug)">
                                    <img :src="item.event.thumbnail" :alt="item.event.title" class="rounded img-4by3-md ">
                                </a>
                                <div class="ms-3 lh-1">
                                    <h5 class="mb-1">
                                        <a :href="eventSlug(item.event.slug)" class="text-inherit text-wrap">{{ item.event.title }}</a>
                                    </h5>
                                    <small class="text-success strong"><i class="fas fa-bolt"></i> {{ item.event.bookings.length }} {{ trans('em.bookings') }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle"> {{ item.user.name }} </td>
                        <td class="align-middle"> {{ item.user.email }} </td>
                        <td class="align-middle">
                            <span v-if="item.status === 1" class="badge bg-success"> Paid </span>
                            <span v-if="item.status === 0" class="badge bg-danger"> Not Paid </span>
                        </td>
                        <td class="align-middle">
                            <div v-for="(fixed) in item.fixed_value" :key="fixed.min_person"> ${{fixed.amount}} at {{fixed.min_person}} to {{fixed.max_person}} </div>
                        </td>
                        <td class="align-middle">
                            <div v-for="(percent) in item.percent_value" :key="percent.percentage"> {{percent.percentage}}% at {{percent.min_person}} to {{percent.max_person}} </div>
                        </td>
                        <td class="align-middle"> {{ item.total ? `${currency}${item.total.toFixed(2)}` : 'N/A' }} </td>
                        <td class="align-middle">
                            <div class="custom_model">
                                <button class="btn" :class="item.status === 0 ? 'btn-success' : 'btn-warning'" @click="openModal(item.id)">
                                    {{ item.status === 0 ? 'Pay Now' : 'Un Paid'  }}
                                </button>
                                <div class="modal show" v-if="isModalOpen && currentModalId === item.id">
                                    <div class="modal-dialog modal-lg w-100">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-3" id="exampleModalLabel"> Confirmation </h1>
                                                <button type="button" class="btn btn-sm bg-danger text-white close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal()"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>
                                                        Are you sure about the {{ item.status === 0 ? 'pay' : 'un paid'  }} comedian,  {{ item.user.name }}
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" @click="closeModal()">Cancel</button>
                                                <button class="btn btn-primary" @click="paymentStatus(item.status === 1 ? 0 : 1)">Proceed</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-4 pb-4"  v-if="comediansPayoutList.length > 0">
            <pagination-component v-if="pagination.last_page > 1" :pagination="pagination" :offset="pagination.total" :path="'/dashboard/comedians-payout'" @paginate="getComediansPayout()">
            </pagination-component>
        </div>

    </div>
</template>

<script>
import PaginationComponent from '../../common_components/Pagination'
import mixinsFilters from '../../mixins.js';
export default {
    props: ['currency', 'page'],
    mixins:[
        mixinsFilters
    ],
    components: {
        PaginationComponent,
    },

    data () {
        return {
            isModalOpen: false,
            currentModalId: null,
            search: '',
            event_id: 0,
            pagination: {
                'current_page': 1
            },
            comediansPayoutList: [],
            comediansPaidTotal: 0,
            comediansUnpaidCount: 0,
            comediansUnpaidTotal: 0,
            eventIds: [],
            allChecked: false,
            eventId: null,
        };
    },
    async mounted() {
        await this.getComedianPayoutGridData();
        await this.getComediansPayout();
        await this.getMyEvents();
    },
    computed: {
        current_page() {
            // get page from route
            if(typeof this.page === "undefined")
                return 1;
            return this.page;
        },
    },
    methods: {
        eventSlug(slug){

            if(slug)
            {
                return route('eventsaaspro.events_show',[slug]);
            }
        },

        async getComediansPayout() {
            await axios.get(route('eventsaaspro.get_comedian_payout')+'?page='+this.current_page+'&search='+this.search)
            .then(res => {
                this.comediansPayoutList   = res.data.comedians.data;
                this.pagination = {
                    'total' : res.data.comedians.total,
                    'per_page' : res.data.comedians.per_page,
                    'current_page' : res.data.comedians.current_page,
                    'last_page' : res.data.comedians.last_page,
                    'from' : res.data.comedians.from,
                    'to' : res.data.comedians.to
                };
            })
            .catch(error => {

            });
        },

        async getComedianPayoutGridData () {
            await axios.get(route('eventsaaspro.get_comedian_payout_grid_data'))
            .then(response => {
                this.comediansPaidTotal =  response.data.comediansPaidTotal;
                this.comediansUnpaidCount =  response.data.comediansUnpaidCount;
                this.comediansUnpaidTotal =  response.data.comediansUnpaidTotal
            })
            .catch(error => {

            });
        },

        async getMyEvents() {
            await axios.get(route('eventsaaspro.all_myevents'))
            .then(res => {
                this.events  = res.data.myevents;
            })
            .catch(error => {
                console.error(error)
            });
        },

        isSelected(itemId) {
            return this.eventIds.includes(itemId);
        },

        toggleCheckbox(itemId) {
            if (this.isSelected(itemId)) {
                // Item is already selected, remove it from the array
                this.eventIds = this.eventIds.filter(id => id !== itemId);
            } else {
                // Item is not selected, add it to the array
                this.eventIds.push(itemId);
            }

            if (this.eventIds.length < this.comediansPayoutList.length && this.allChecked) {
                this.allChecked= false;
            }

            if (this.eventIds.length === this.comediansPayoutList.length) {
                this.allChecked= true;
            }
        },

        selectAll () {
            if (this.allChecked) {
                this.eventIds = this.comediansPayoutList.map(item => item.id);
            } else {
                this.eventIds = [];
            }
        },

        openModal(id) {
            this.currentModalId = id;
            this.isModalOpen = true;
            this.eventId = id;
        },

        closeModal() {
            this.currentModalId = null;
            this.isModalOpen = false;
        },

        paymentStatus (status) {
            axios.post(`/dashboard/comedians-payout/api/update-payment-status/${status}`, {comedian_event_ids: [this.eventId]})
            .then((response) => {
                    this.showNotification('success', response.data.message);
                    this.closeModal();
                    setTimeout(function() {
                        location.reload(true);
                    }, 1000);
            })
            .catch(error => {
                this.closeModal();
                Vue.helpers.axiosErrors(error);
            });
        },

        paymentStatusAll (status) {
            axios.post(`/dashboard/comedians-payout/api/update-payment-status/${status}`, {comedian_event_ids: this.eventIds})
            .then((response) => {
                    this.showNotification('success', response.data.message);
                    setTimeout(function() {
                        location.reload(true);
                    }, 1000);
            })
            .catch(error => {
                Vue.helpers.axiosErrors(error);
            });
        }
    },
    watch : {
        search: function () {
            this.getComediansPayout();
        },
    }
}
</script>
