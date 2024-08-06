<template>
  <div class="tab-pane">
        <div class="panel-group">
            <div class="panel panel-default lgx-panel">
                <div class="panel-heading px-5">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="form-label">Additional Information</label>
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control"  rows="3" name="description" :value="additionalInformation" style="display:none;"></textarea>
                            <ckeditor  v-model="additionalInformation"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-md-12">
                            <button v-if="additionalInformation === null" @click="saveAdditional()" class="btn btn-primary">
                                <i class="fas fa-sd-card"></i>
                                Save
                            </button>
                            <button v-else @click="updateAdditional()" class="btn btn-primary">
                                <i class="fas fa-sd-card"></i>
                                Update
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import mixinsFilters from '../../mixins.js';
export default {
    props: ["user", "csrf_token"],
    name: "AdditionalInformation",
    mixins:[ mixinsFilters ],
    data () {
        return {
            additionalInformation: null
        };
    },
    mounted() {
        this.getAdditionalInformation();
    },
    methods: {
        getAdditionalInformation () {
            axios.get(route('eventsaaspro.get_additional_information',this.user.id))
            .then((response) => {
                this.additionalInformation = response.data.additional !== null ? response.data.additional.additional_information : null
            })
            .catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            })
        },

        saveAdditional () {
            axios.post(route('eventsaaspro.add_additional_information'), {
                "additional_information": this.additionalInformation
            })
            .then((response) => {
                if (response.data.status) {
                    this.showNotification('success', response.data.message)
                    this.additionalInformation = response.data.additional.additional_information
                } else {
                    this.showNotification('error', response.data.message);
                }
            })
            .catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            })
        },

        updateAdditional () {
            axios.post(route('eventsaaspro.update_additional_information'), {
                "additional_information": this.additionalInformation
            })
            .then((response) => {
                if (response.data.status) {
                    this.showNotification('success', response.data.message)
                    this.additionalInformation = response.data.additional.additional_information
                } else {
                    this.showNotification('error', response.data.message);
                }
            })
            .catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            })
        },

        // show server validation errors
        serverValidate(serrors) {
            this.$validator.validateAll().then((result) => {
                this.$validator.errors.add(serrors);
            });
        },
    }
}
</script>

<style>

</style>
