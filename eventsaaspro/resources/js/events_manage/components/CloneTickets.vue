<template>
    <div class="modal" :class="isOpen ? 'show' : ''" :style="isOpen ? 'display: block;' : 'display: none;'">
        <div class="modal-dialog modal-lg w-100">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-3 text-black" id="exampleModalLabel"> Clone Tickets </h1>
                    <button type="button" class="btn btn-sm bg-danger text-white close" data-bs-dismiss="modal" aria-label="Close" @click="close()"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="">
                        <div class="form-group">
                            <label class="control-label mb-2" for="event-title">Search Event</label>
                            <input type="text" class="form-control" id="event-title" @input="onSearch" v-model="searchEvent">
                        </div>
                    </form>

                    <div v-if="isloading">
                        Searching Please Wait...
                    </div>

                    <!-- Events and their Tickets -->
                    <div v-if="events.length > 0">
                        <template v-for="(event, index) in events">
                            <div :key="index">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="m-0"> {{ event.title }} </h6>
                                    <button class="btn btn-primary btn-sm" v-if="event.tickets.length > 0" @click="cloneTickets(event.id)"> Clone Tickets </button>
                                </div>
                                <!-- Tickets -->
                                <div class="table-responsive mt-4" v-if="event.tickets.length > 0">
                                    <table class="table table-hover" v-if="event.tickets.length > 0">
                                        <thead>
                                            <tr>
                                                <th>Name: </th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>No. of persons</th>
                                                <th>Customer Limit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(ticket, index2) in event.tickets" :key="index2">
                                                <td> {{ ticket.title }} </td>
                                                <td> {{ ticket.description }} </td>
                                                <td> {{ ticket.quantity }} </td>
                                                <td> {{ ticket.price }} </td>
                                                <td> {{ ticket.no_of_persons }} </td>
                                                <td> {{ ticket.customer_limit !== null ? ticket.customer_limit : " -- " }} </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-else class="no-ticket-found"> No Ticket Found </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: "CLoneTickets",
    props: {
        isOpen: {
            default: false,
            type: Boolean
        }
    },
    data () {
        return {
            searchEvent: "",
            events: [],
            isloading: false,
        };
    },
    computed: {
        // get global variables
        ...mapState(['event_id']),
    },
    methods: {
        onSearch() {
            this.isloading = true;
            this.search(this);
        },

        search: _.debounce((vm) => {
            if (vm.searchEvent.length < 3) return;

            let post_url = route('eventsaaspro.search_event', [vm.searchEvent]);
            axios.post(post_url)
            .then(res => {
                // console.log('Response: ', res);
                if (res.data.status) {
                    vm.events = res.data.events;
                }
                // vm.searchEvent = "";
                vm.isloading = false;
            })
            .catch(error => {
                vm.isloading = false;
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            });
        }, 500),

        cloneTickets(id) {
            let post_url = route('eventsaaspro.clone_ticket', [id, this.event_id]);
            axios.post(post_url)
            .then(res => {
                // console.log('Response: ', res);
                this.$parent.openCloneTicketModal = false;
                // if (res.data.status) {
                //     vm.events = res.data.events;
                // }
                // // vm.searchEvent = "";
                // vm.isloading = false;
            })
            .catch(error => {
                console.error(error);
                // let serrors = Vue.helpers.axiosErrors(error);
                // if (serrors.length) {
                //     this.serverValidate(serrors);
                // }
            });
        }
    }
}
</script>

<style scoped>
.form-group {
    margin-bottom: 20px;
}

.table>thead>tr>th{
    font-size: 12px;
    font-weight: 600;
    color: #526069;
    background: #f8fafc;
    border-bottom: 1px solid #e4eaec;
    border-color: #eaeaea;
}

.table>tbody>tr>td{
    font-size: 12px;
    font-weight: 400;
}

.no-ticket-found{
    font-size: 14px;
    margin: 15px 0;
    font-weight: 600;
    color: #526069;
}
</style>
