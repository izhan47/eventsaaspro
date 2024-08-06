<template>
    <div class="modal" :class="isOpen ? 'show' : ''" :style="isOpen ? 'display: block;' : 'display: none;'">
        <div class="modal-dialog modal-lg w-100">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-3 text-black" id="exampleModalLabel"> Add New Comedian </h1>
                    <button type="button" class="btn btn-sm bg-danger text-white close" data-bs-dismiss="modal" aria-label="Close" @click="close()"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="formSubmit()" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label" for="name">Name</label>
                            <input class="form-control" id="name" type="text" placeholder="Enter name" v-model="comedian.name">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="email">Email</label>
                            <input class="form-control" id="email" type="email" placeholder="Enter Email" v-model="comedian.email">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="password">Password</label>
                            <input class="form-control" id="password" type="password" placeholder="Enter password" v-model="comedian.password">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="organization">Organization</label>
                            <input class="form-control" id="organization" type="text" placeholder="Enter Organization" v-model="comedian.organisation">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="bank-name">Bank Name</label>
                            <input class="form-control" id="bank-name" type="text" placeholder="Enter Bank Name" v-model="comedian.bank_name">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="bank-code">Bank Code</label>
                            <input class="form-control" id="bank-code" type="text" placeholder="Enter Bank Code" v-model="comedian.bank_code">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="branch-name">Branch Name</label>
                            <input class="form-control" id="branch-name" type="text" placeholder="Enter Branch Name" v-model="comedian.bank_branch_name">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="branch-code">Branch Code</label>
                            <input class="form-control" id="branch-code" type="text" placeholder="Enter Branch Code" v-model="comedian.bank_branch_code">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="account-no">Bank Account Number</label>
                            <input class="form-control" id="account-no" type="text" placeholder="Enter Bank Account Number" v-model="comedian.bank_account_number">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="bank-account-name">Bank Account Name</label>
                            <input class="form-control" id="bank-account-name" type="text" placeholder="Enter Bank Account Name" v-model="comedian.bank_account_name">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="name">Enter Bank Account Phone</label>
                            <input class="form-control" id="bank-account-phone" type="text" placeholder="Enter Bank Account Phone" v-model="comedian.bank_account_phone">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="address">Address</label>
                            <input class="form-control" id="address" type="text" placeholder="Enter Address" v-model="comedian.address">

                        </div>

                        <div class="form-group">
                            <label class="control-label" for="phone">Phone</label>
                            <input class="form-control" id="phone" type="text" placeholder="Enter Phone" v-model="comedian.phone">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="status">Status</label>
                            <select name="status" class="form-control group_select group_select_new" id="status" v-model="comedian.status">
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="stage-name">Stage Name</label>
                            <input class="form-control" type="text" id="stage-name" placeholder="Enter Stage Name" v-model="comedian.comedian_stage_name">
                        </div>


                         <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sd-card"></i> {{ trans('em.save') }}</button>
                        </div>
                    </form>
                    <!-- <form ref="form" @submit.prevent="validateForm" method="POST" enctype="multipart/form-data">
                        <input v-if="edit_ticket" type="hidden" class="form-control lgxname"  name="ticket_id" v-model="edit_ticket.id">
                        <input type="hidden" class="form-control lgxname"  name="event_id" v-model="event_id">
                        <input type="hidden" class="form-control lgxname"  name="organiser_id" v-model="organiser_id">
                        <input type="hidden" class="form-control lgxname"  name="taxes_ids"  v-model="taxes_ids">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="title">{{ trans('em.title') }}</label>
                                <input type="text" class="form-control lgxname"  name="title"  v-model="title" v-validate="'required'">
                                <span v-show="errors.has('title')" class="small text-danger">{{ errors.first('title') }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="price">{{ trans('em.price') }} ({{ currency }})</label>
                                <input type="text" class="form-control lgxname"  name="price" v-model="price" v-validate="'required'">
                                <span v-show="errors.has('price')" class="small text-danger">{{ errors.first('price') }}</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="quantity">{{ trans('em.max_ticket_qty') }}</label>
                                <input type="text" class="form-control lgxname"  name="quantity" v-model="quantity" v-validate="'required'">
                                <span v-show="errors.has('quantity')" class="small text-danger">{{ errors.first('quantity') }}</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="customer_limit">{{ trans('em.customer_limit') }}</label>
                                <input type="text" class="form-control lgxname"  name="customer_limit" v-model="customer_limit" >
                                <span class="small text-muted">{{ trans('em.customer_limit_info') }}</span>
                                <span v-show="errors.has('customer_limit')" class="small text-danger">{{ errors.first('customer_limit') }}</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="no_of_persons">{{ 'No of persons' }}</label>
                                <input type="text" class="form-control lgxname"  name="no_of_persons" v-model="no_of_persons" >
                                <span class="small text-muted">{{ 'How many persons enter with this one ticket' }}</span>
                                <span v-show="errors.has('no_of_persons')" class="small text-danger">{{ errors.first('no_of_persons') }}</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">{{ trans('em.description') }}</label>
                                <textarea name="description" class="form-control lgxname" rows="2" v-model="description"></textarea>
                                <span v-show="errors.has('description')" class="small text-danger">{{ errors.first('description') }}</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ trans('em.taxes') }}</label>
                                <multiselect
                                    v-model="tmp_taxes_ids"
                                    :options="taxes_options"
                                    :placeholder="'-- '+trans('em.select')+' --'"
                                    label="text"
                                    track-by="value"
                                    :multiple="true"
                                    :close-on-select="false"
                                    :clear-on-select="false"
                                    :hide-selected="false"
                                    :preserve-search="true"
                                    :preselect-first="false"
                                    :allow-empty="true"
                                    :class="'form-control px-0 py-0 border-0'"
                                >
                                </multiselect>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sd-card"></i> {{ trans('em.save') }}</button>
                        </div>
                    </form> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "AddNewComedian",
    props: {
        isOpen: {
            default: false,
            type: Boolean
        }
    },
    data() {
        return {
            comedian: {
                name: '',
                email: '',
                password: '',
                role_id: 4,
                organisation: '',
                bank_name: '',
                bank_code: '',
                bank_branch_name: '',
                bank_branch_code: '',
                bank_account_number: '',
                bank_account_name: '',
                bank_account_phone: '',
                address: '',
                phone: '',
                status: '',
                deleted_at: '',
                comedian_stage_name: '',
            }
        };
    },
    computed: {},
    methods: {
        close: function () {
            this.$parent.openModalAddComedian   = false;
        },

        validateForm(event) {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.formSubmit(event);
                }
            });
        },

        // show server validation errors
        serverValidate(serrors) {
            this.$validator.validateAll().then((result) => {
                this.$validator.errors.add(serrors);
            });
        },

        formSubmit() {
            let post_url = route('eventsaaspro.comedian_create');

            axios.post(post_url, this.comedian)
            .then(res => {
                console.log('Response: ', res);
            })
            .catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            });
        }
    },
}
</script>

<style scoped>

.form-group {
    margin-bottom: 15px
}

</style>
