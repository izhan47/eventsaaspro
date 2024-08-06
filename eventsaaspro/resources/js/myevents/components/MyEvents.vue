<template>
    <div>
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between flex-wrap p-4 bg-white border-bottom-0">
                <div>
                    <h1 class="mb-0 fw-bold h2">{{ trans('em.myevents') }}</h1>
                </div>
                <div>
                    <a class="btn btn-primary btn-block" :href="createEvent()"><span><i class="fas fa-calendar-plus"></i> {{ trans('em.create_event') }}</span></a>
                </div>
            </div>
            <div class="card-header d-flex justify-content-between flex-wrap p-4 bg-white border-bottom-0">
                <div class="d-flex">
                    <div class="me-2">
                        <date-picker class="form-control" :shortcuts="shortcuts" v-model="date_range" range :lang="$vue2_datepicker_lang" :placeholder="trans('em.booking_date')" format="YYYY-MM-DD "></date-picker>
                    </div>
                    <div class="me-2">
                        <input type="text" class="form-control" v-model="search" placeholder="Enter event title" />
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table text-wrap">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <th class="border-top-0 border-bottom-0">{{ trans('em.event') }}</th>
                            <th class="border-top-0 border-bottom-0">{{ trans('em.timings') }}</th>
                            <th class="border-top-0 border-bottom-0">{{ trans('em.repetitive') }}</th>
                            <th class="border-top-0 border-bottom-0">{{ trans('em.payment_frequency') }}</th>
                            <th class="border-top-0 border-bottom-0">{{ trans('em.publish') }}</th>
                            <th class="border-top-0 border-bottom-0">{{ trans('em.status') }}</th>
                            <th class="border-top-0 border-bottom-0">{{ trans('em.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(event, index) in events" :key="index" >
                            <td :data-title="trans('em.event')">
                                <div class="d-flex align-items-center">
                                    <a :href="eventSlug(event.slug)">
                                        <img :src="event.poster" :alt="event.title" class=" rounded img-4by3-md ">
                                    </a>
                                    <div class="ms-3 lh-1">
                                        <h5 class="mb-1">
                                            <a class="text-inherit text-wrap" :href="eventSlug(event.slug)">{{ event.title }}</a>
                                            <span style="cursor: pointer;" @click="copyToClipboard(eventSlug(event.slug))"> <i class="fa fa-clone"></i> </span>
                                        </h5>
                                        <small class="text-success strong" v-if="event.count_bookings > 0"><i class="fas fa-bolt"></i> {{ event.count_bookings }} {{ trans('em.bookings') }}</small>
                                        <small class="text-muted strong" v-else><i class="fas fa-hourglass"></i> {{ event.count_bookings }} {{ trans('em.bookings') }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle text-nowrap" :data-title="trans('em.start_date')">
                                <small class="text-muted">
                                    {{ changeDateFormat(userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'), 'YYYY-MM-DD') }}
                                    {{ userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format(date_format.vue_time_format) }} {{ showTimezone()  }}
                                </small>
                                <br>
                                <small class="text-muted" v-if="userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD') <= userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')" :data-title="trans('em.end_date')">
                                    {{ changeDateFormat(userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'), 'YYYY-MM-DD') }}
                                    {{ userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format(date_format.vue_time_format) }} {{  showTimezone()  }}
                                </small>
                                <small class="text-muted" v-else :data-title="trans('em.end_date')">
                                    {{ changeDateFormat(userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'), 'YYYY-MM-DD') }}
                                    {{ userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format(date_format.vue_time_format) }} {{  showTimezone()  }}
                                </small>
                            </td>
                            <td class="align-middle" :data-title="trans('em.repetitive')">
                                <span class="badge bg-success" v-if="event.repetitive">{{ trans('em.yes')  }}</span>
                                <span class="badge bg-danger" v-else>{{ trans('em.no') }}</span>
                            </td>
                            <td class="align-middle" :data-title="trans('em.payment_frequency')">
                                <span class="badge bg-info" v-if="event.merge_schedule">{{ trans('em.monthly_weekly')  }}</span>
                                <span class="badge bg-primary" v-else>{{ trans('em.full_advance') }}</span>
                            </td>
                            <td class="align-middle" :data-title="trans('em.publish')">
                                <span class="badge bg-success" v-if="event.publish">{{ trans('em.published')  }}</span>
                                <span class="badge bg-secondary" v-else>{{ trans('em.unpublished') }}</span>
                            </td>
                            <td class="align-middle" :data-title="trans('em.status')">
                                <span class="badge bg-success" v-if="event.status == 1">{{ trans('em.enabled')  }}</span>
                                <span class="badge bg-success" v-else-if="event.status == 2">{{ trans('em.completed')  }}</span>
                                <span class="badge bg-danger" v-else>{{ trans('em.disabled') }}</span>
                            </td>
                            <td class="align-middle" :data-title="trans('em.actions')">
                                <div class="d-grid gap-2 text-nowrap">
                                    <a class="btn btn-primary btn-sm" :href="eventEdit(event.slug)"><i class="fas fa-edit"></i> {{ trans('em.edit') }}</a>
                                    <a class="btn btn-success btn-sm" :class="{ 'disabled' : event.count_bookings < 1 }" :href="exportAttendies(event.slug, event.count_bookings)">
                                        <i class="fas fa-file-csv"></i> {{ trans('em.export_attendees') }}
                                    </a>
                                    <button class="btn btn-info btn-sm" @click="cloneEvent(event.id)">
                                        <i class="fa fa-clone"></i> Clone this Event
                                    </button>
                                </div>
                            </td>

                        </tr>
                        <tr v-if="events.length <= 0">
                            <td class="text-center align-middle">{{ trans('em.no_events') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-4 pb-4" v-if="events.length > 0">
                <pagination-component v-if="pagination.last_page > 1" :pagination="pagination" :offset="pagination.total" :path="'myevents'" @paginate="getMyEvents()"></pagination-component>
            </div>
        </div>
    </div>
</template>

<script>

import PaginationComponent from '../../common_components/Pagination'

import mixinsFilters from '../../mixins.js';
import DatePicker from 'vue2-datepicker';

export default {
    props: [
        // pagination query string
        'page',
        'category',
        'date_format'
    ],

    components: {
        PaginationComponent,
        DatePicker
    },

    mixins:[
        mixinsFilters
    ],

    data() {
        return {
            events           : [],
            pagination: {
                'current_page': 1
            },
            moment           : moment,
            date_range : [],
            start_date : '',
            end_date   : '',

            // date shortucts like today, tommorrow
            shortcuts: [
                {
                    text: trans('em.today'),
                    onClick: () => {
                        this.date_range = [moment().toDate(), moment().toDate() ]
                    }
                },
                {
                    text: trans('em.tomorrow'),
                    onClick: () => {
                        this.date_range = [moment().add(1,'day').toDate(), moment().add(1,'day').toDate()]
                    }
                },
                {
                    text: trans('em.this')+' '+trans('em.weekend'),
                    onClick: () => {
                        this.date_range = [moment().endOf("week").toDate(), moment().endOf("week").toDate()]
                    }
                },
                {
                    text: trans('em.this')+' '+trans('em.week'),
                    onClick: () => {
                        this.date_range = [moment().startOf("week").toDate(), moment().endOf("week").toDate()]
                    }
                },
                {
                    text: trans('em.next')+' '+trans('em.week'),
                    onClick: () => {
                        this.date_range = [moment().add(1, 'weeks').startOf("week").toDate(), moment().add(1, 'weeks').endOf("week").toDate()]
                    }
                },
                {
                    text: trans('em.this')+' '+trans('em.month'),
                    onClick: () => {
                        this.date_range = [moment().startOf("month").toDate(), moment().endOf("month").toDate()]
                    }
                },
                {
                    text: trans('em.next')+' '+trans('em.month'),
                    onClick: () => {
                        this.date_range = [moment().add(1, 'months').startOf("month").toDate(), moment().add(1, 'months').endOf("month").toDate()]
                    }
                },
            ],

            search: '',
        }
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

        // get all events
        getMyEvents() {
            if(typeof this.start_date === "undefined") {
                this.start_date     = '';
            }
            if(typeof this.end_date === "undefined") {
                this.end_date     = '';
            }

            axios.get(route('eventsaaspro.myevents')+'?page='+this.current_page+'&start_date='+this.start_date+'&end_date='+this.end_date+'&search='+this.search)
            .then(res => {

                this.events  = res.data.myevents.data;

                this.pagination = {
                    'total' : res.data.myevents.total,
                    'per_page' : res.data.myevents.per_page,
                    'current_page' : res.data.myevents.current_page,
                    'last_page' : res.data.myevents.last_page,
                    'from' : res.data.myevents.from,
                    'to' : res.data.myevents.to
                };
            })
            .catch(error => {

            });
        },

        // edit myevents
        eventEdit(event_id) {
            return route('eventsaaspro.myevents_form', {id: event_id});
        },

        // create newevents
        createEvent() {
            return route('eventsaaspro.myevents_form');
        },

        // return route with event slug
        eventSlug(slug){
            return route('eventsaaspro.events_show',[slug]);
        },

        // ExportAttendies
        exportAttendies(event_slug = null, event_bookings = 0){
            if(event_slug != null && event_bookings > 0)
                return route('eventsaaspro.export_attendees', [event_slug]);

        },

        // searching by date
        dateRange: function () {
            var is_date_null = 0;
            if(Object.keys(this.date_range).length > 0 )
            {
                // convert date range to server side date
                this.date_range.forEach(function(value, key) {
                    if(value != null) {
                        is_date_null = 1;

                        if(key == 0)
                            this.start_date   =  this.convert_date(value); // convert local start_date to server date then searching by date

                        if(key == 1)
                            this.end_date     =  this.convert_date(value); // convert local end_date to server date then searching by date
                    }
                }.bind(this));

                // date reset
                if(is_date_null <= 0){
                    this.start_date  = '';
                    this.end_date    = '';
                }

                this.getMyEvents()
            }

        },

        copyToClipboard(url) {
            // Create a temporary textarea element
            const textarea = document.createElement('textarea');

            // Set the value to the provided URL
            textarea.value = url;

            // Make the textarea out of the viewport
            textarea.style.position = 'fixed';
            textarea.style.top = '-9999px';

            // Append the textarea to the document
            document.body.appendChild(textarea);

            // Select the text in the textarea
            textarea.select();

            try {
                // Execute the copy command
                document.execCommand('copy');
                this.showNotification('success', 'Copied');
            } catch (err) {
                console.error('Unable to copy text to clipboard', err);
            } finally {
                // Remove the temporary textarea from the document
                document.body.removeChild(textarea);
            }
        },

        cloneEvent (id) {
            axios.get(`dashboard/myevents/api/duplicate-event/${id}`).then(response => {
                console.log('Response: ', response);
                this.showNotification('success', response.data.message);

                setTimeout(() => {
                    window.location.href = `/dashboard/myevents/manage/${response.data.new_event_slug}`
                }, 1000)
            }).catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            });
        }
    },
    mounted() {
        this.getMyEvents();
    },

     watch : {
        date_range: function () {
            this.dateRange();
        },

        search: function () {
            this.getMyEvents();
        },
    }
}
</script>
<style>

</style>
