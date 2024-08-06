<template>
    <a :href="eventSlug(event.slug)" class="card event-card">
        <div class="event-image">
            <img :src="`${event.thumbnail}`" class="img-fluid w-100" alt="...">
        </div>
        <div class="event-content">
            <div class="owner" v-if="event.user.organisation && event.user.organisation !== ''">
                <div class="info">
                    <div class="avatar"> {{ event.user.organisation.charAt(0) }} </div>
                    <!-- <div class="img" style="width: 22px; height: 22px;">
                        <img :src="`${imageBaseUrl}${event.user.avatar}`" class="img-fluid" alt="...">
                    </div> -->
                    <div class="name"> {{ event.user.organisation }} </div>
                </div>
                <div class="action">
                    <button>
                        <img :src="`${imageBaseUrl}icons/share.png`" alt="...">
                    </button>
                    <!-- <button>
                        <img :src="`${imageBaseUrl}icons/heart.png`" alt="...">
                    </button> -->
                </div>
            </div>
            <!-- Title -->
            <div class="title-wrapper">
                <h3>
                    {{ event.title.substring(0, 30) + `${event.title.length > 30 ? '...' : '' }` }}
                </h3>

                <!-- Price -->
                <div class="price" v-if="event.tickets && event.tickets.length > 0 ">
                    <span> {{ `${currency}${ticketPrice(event.tickets)}` }} </span>
                </div>
            </div>

            <!-- Date -->
            <div class="date">
                <span v-if="event.start_date === event.end_date"> {{moment(event.start_date).format("ddd MMM D")}} </span>
                <span v-else> {{moment(event.start_date).format("ddd MMM D")}} - {{moment(event.end_date).format("ddd MMM D")}} </span>
                - {{ (moment(event.start_time, 'HH:mm:ss')).format('h:mm A') }}
            </div>

            <!-- Description -->
            <div class="excerpt">
                <p>
                    {{ event.venue }}
                </p>
            </div>
        </div>
    </a>
</template>

<script>

import mixinsFilters from '../mixins.js';

export default {

    props: ['event', 'currency', 'date_format'],


    mixins:[
        mixinsFilters
    ],

    data() {
        return {
            imageBaseUrl: window.config.s3Url
        }
    },

    methods:{

        // check free tickets of events
        checkFreeTickets(event_tickets = []){
            let free = false;
            event_tickets.forEach(function(value, key) {
                if(parseFloat(value.price) <= parseFloat(0))
                {
                    free = true;
                }
            });
            return free;
        },


        // return route with event slug
        eventSlug: function eventSlug(slug) {
            return route('eventsaaspro.events_show', [slug]);
        },


        ticketPrice(tickets) {
            if (tickets.length === 0) {
                return 'N/A'; // or handle empty array case accordingly
            }

            let lowestPriceTicket = tickets[0];

            // Loop through the rest of the tickets
            for (let i = 1; i < tickets.length; i++) {
                if (JSON.parse(tickets[i].price) < JSON.parse(lowestPriceTicket.price)) {
                    lowestPriceTicket = tickets[i];
                }
            }

            return lowestPriceTicket.price;
        }


    },

    mounted(){
    }

}
</script>
