<template>
    <div>
        <div class="schedule-row mb-3" v-for="(month, index) in months" :key="month">
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="badge bg-primary d-flex p-2 fs-6">
                        #{{index+1}} &nbsp; {{ moment(month, 'YYYY-MM').format('MMMM') }} {{ trans('em.schedule') }}
                        <!-- #{{sch_index+1}} &nbsp;<span>{{ moment(month, 'YYYY-MM').format('MMMM') }} {{ trans('em.schedule') }}  </span> -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-2">
                        <label class="form-label">{{ trans('em.repetitive_dates') }} ({{ trans('em.repeats_on') }})</label>
                        <multiselect
                            :class="'form-control px-0 py-0 border-0'"
                            v-model="repetitive_dates[index]" 
                            :options="repetitive_dates_options" 
                            :placeholder="trans('em.select_dates')" 
                            label="text" 
                            track-by="value" 
                            :multiple="true"
                            :close-on-select="false" 
                            :clear-on-select="false" 
                            :hide-selected="false" 
                            :preserve-search="true" 
                            :preselect-first="schedules ? false : true"
                            :allow-empty="true"
                            :disabled="sch_r_type == 2  ? true : false "	
                            @input="schedules ? schedules.repetitive_type = null : ''"
                            @select="isDirty()"
                        >
                        </multiselect>
                    </div>
                </div>

                <div class="col-12">
                    <div v-for="(item, index2) in repetitive_dates[index]" :key="index2">
                        <div class="alert alert-primary mb-4">
                            <p class="text-primary fw-bold m-0">
                                {{item.text}} {{ moment(month, 'YYYY-MM').format('MMMM, YYYY') }}
                                <!-- {{item.text}} {{ moment(month, 'YYYY-MM').format('MMMM, YYYY') }} -->
                            </p>
                        </div>

                        <div class="row mb-4">
                            <!-- Start Time -->
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="from_time">{{ trans('em.start_time') }}</label><br>
                                    <date-picker 
                                        class="form-control"
                                        v-model="from_time[index][index2]" 
                                        type="time" 
                                        format="h:mm A" 
                                        :placeholder="trans('em.select_start_time')" 
                                        :lang="$vue2_datepicker_lang"
                                        @change="isDirty()"
                                    ></date-picker>
                                    <span v-show="errors.has('end_time')" class="help text-danger">{{ errors.first('from_time') }}</span> 
                                </div>
                            </div>
                            <!-- End Time -->
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="to_time">{{ trans('em.end_time') }}</label><br>
                                    <date-picker 
                                        class="form-control"
                                        v-model="to_time[index][index2]" 
                                        type="time" 
                                        format="h:mm A" 
                                        :placeholder="trans('em.select_end_time')" 
                                        :lang="$vue2_datepicker_lang"
                                        @change="isDirty()"
                                        :disabled="infinite_to_time[index][index2]"
                                    ></date-picker>
                                    <span v-show="errors.has('to_time')" class="help text-danger">{{ errors.first('to_time') }}</span>
                                </div>
                                <div>
                                    <input type="checkbox" :id="`infinite-${index}-${index2}`" v-model="infinite_to_time[index][index2]">
                                    <label :for="`infinite-${index}-${index2}`"> Check, if you want to infinite end time </label>
                                </div>
                            </div>

                            <div class="alert alert-danger mt-3 mb-0"
                                v-if="from_time[index][index2] && !moment(to_time[index][index2], 'HH:mm:ss').isAfter(moment(from_time[index][index2], 'HH:mm:ss'))"
                            >Please Select Future time</div>
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
import moment from 'moment';

export default {
    name: "RepetitiveSchedule",

    props: [
        'sch_index', 'sch_r_type', 'start_time_p', 'end_time_p', 'start_date_p', 'end_date_p', 'schedules_p', 'month', 'months', 'schedule_repetitive_dates', 'schedule_from_time', 'schedule_to_time'
    ],

    mixins:[
        mixinsFilters
    ],
    
    data() {
        return {
            schedules      : this.schedules_p ? this.schedules_p : [],
            repetitive_dates : [[{value : 1,   text : "1"}], [{value : 2,   text : "2"}]],
            repetitive_dates_options : [],

            from_time   : [[''], ['']],
            to_time     : [[''], ['']],
            infinite_to_time     : [[''], ['']],
        };
    },

    mounted(){
        // make date options dynamically
        this.make_date_options();

        if (this.schedule_repetitive_dates.length > 0) {
            this.repetitive_dates = this.schedule_repetitive_dates.map(pairString => pairString.split(',').map(this.convertToObjects.bind(this)));
        }

        if (this.schedule_from_time.length > 0) {
            // this.from_time = this.schedule_from_time.map(item => item.map(item2 => item2.split(',')));
            this.from_time = this.schedule_from_time.map(innerArray => {
                return innerArray[0].split(',').map((time) => {
                    return time;
                });
            });

            this.from_time.map((item, index) => {
                item.map((item2, index2) => {
                    let monthYear = this.months[index];
                    let day = this.repetitive_dates[index][index2].text;
                    let dateTime = this.userTimezone(`${monthYear}-${day} ${item2}`, 'YYYY-MM-DD HH:mm:ss');
                    this.from_time[index][index2] = this.setDateTime(dateTime);
                });
            });

            // this.to_time = this.schedule_to_time.map(item => item.split(','));
            this.to_time = this.schedule_to_time.map(innerArray => {
                return innerArray[0].split(',').map((time) => {
                    return time;
                });
            });

            this.to_time.map((item, index) => {
                item.map((item2, index2) => {
                    let monthYear = this.months[index];
                    let day = this.repetitive_dates[index][index2].text;
                    let dateTime = this.userTimezone(`${monthYear}-${day} ${item2}`, 'YYYY-MM-DD HH:mm:ss');
                    this.to_time[index][index2] = this.setDateTime(dateTime);
                });
            });

            /* For Edit Timing */
            let array = [];
            this.schedule_to_time.forEach((innerArray, index) => {
               innerArray.forEach((item2, index2) => {
                array[index] = item2.split(',')
               })
            });

            let _this = this;
            array.forEach((item, index) => {
                item.forEach((item2, index2) => {
                    if (item2 === '23:59:00') {
                        _this.infinite_to_time[index][index2] = true;
                    } else {
                        _this.infinite_to_time[index][index2] = false;
                    }
                })
            })
            /* For Edit Timing */
        }
    },

    computed: {
        // get global variables
        ...mapState( ['v_repetitive_dates', 'v_from_time', 'v_to_time', 'is_dirty']),
    },

    methods: {
        ...mapMutations(['add', 'update']),

        make_date_options(){

            this.repetitive_dates_options = [];
            let month_end_date   = moment(this.month).daysInMonth();
            let i                = 1;
            
            for(i = 1; i <= month_end_date; i++)
            {   
                this.repetitive_dates_options.push({value : (i.toString().length == 1 ? ('0'+i) : i ) ,text : i});
            }
        },

        isDirty() {
            // this.add({is_dirty: true});
        },

        convertDatesToTime (arr) {
            return arr.map(subArr => {
                return subArr.map(dateString => {
                    // const time = moment(dateString).format('HH:mm:ss');
                    const time = this.convert_time(dateString);
                    return time;
                });
            })
        },

        updateSchedule () {
            let formatted_from = this.from_time;
            
            formatted_from = this.convertDatesToTime(formatted_from);

            let formatted_to = this.to_time;
            formatted_to = this.convertDatesToTime(formatted_to);

            if (this.schedule_to_time.length === 0) {
                this.repetitive_dates.forEach((item, index) => {
                    item.forEach((item2, index2) => {
                        this.infinite_to_time[index][index2] = false;
                    })
                })
            }

            this.update({
                v_repetitive_dates  : this.repetitive_dates,
                v_from_time         : formatted_from,
                v_to_time           : formatted_to,
            });
        },

        convertToObjects(pairString) {
            return { value: parseInt(pairString), text: pairString }, { value: parseInt(pairString), text: pairString };
        },
    },

    watch: {
        repetitive_dates: function () {
            this.updateSchedule();
        },

        from_time: function () {
            this.updateSchedule();
        },

        to_time: function () {
            this.updateSchedule();
        },

        infinite_to_time: {
            deep: true,
            handler(newValue) {
                newValue.forEach((item, index) => {
                    item.forEach((item2, index2) => {
                        if (item2) {
                            let monthYear = this.months[index];
                            let day = this.repetitive_dates[index][index2].text;
                            let dateTime = this.userTimezone(`${monthYear}-${day} "23:59:00"`, 'YYYY-MM-DD HH:mm:ss');
                            this.to_time[index][index2] = this.setDateTime(dateTime);
                            this.updateSchedule();
                        }
                    })
                })
            }
        }
    }
}
</script>

<style>

</style>
