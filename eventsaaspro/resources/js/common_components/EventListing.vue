<template>
    <div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12 mb-4" v-for="(event, index) in events" :key="index">
                <Event :event="event" :currency='currency' :date_format='date_format'/>
            </div>
        </div>

        <div class="row" v-if="not_found">
            <div class="col-12">
                <h4 class="heading text-center mt-30"><i class="fas fa-exclamation-triangle"></i> {{ trans('em.events_not_found') }}</h4>
            </div>
        </div>
    </div>

</template>

<script>

import mixinsFilters from '../mixins.js';

import { Carousel, Slide } from 'vue-carousel';

import Event from './Event.vue';

export default {

    props: ['events', 'currency', 'date_format', 'item_count'],

    components: {
        Carousel,
        Slide,
        Event
    },

    mixins:[
        mixinsFilters
    ],

    data() {
        return {
            not_found   : false,
            events_slider   : events_slider,
            dir         : false,
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

        getDirection(){
            document.documentElement.dir == 'rtl' ? this.dir = true : this.dir = false;
        },


    },


    watch: {
        events: function () {
            this.not_found = false;
            if(this.events.length <= 0)
                this.not_found = true;

        },

    },
    mounted(){
        this.getDirection();
    }

}
</script>
