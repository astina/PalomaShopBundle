<template>
    <div class="section">

        <div class="content">
            <h1>
                {{ $trans('customer.account.address.' + type) }}
            </h1>
        </div>

        <paloma-spinner :loading="!address"></paloma-spinner>

        <paloma-address-form
                v-if="address"
                :address="address"
                :model="model"
                :loading="loading"
                @submit="submit"
                @cancel="cancel"
                submit-label="button.save"
                cancel-label="nav.back"
        ></paloma-address-form>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaAddressForm from "../common/PalomaAddressForm";
    import PalomaSpinner from "../common/PalomaSpinner";

    export default {
        name: "PalomaAccountAddressForm",

        components: {PalomaSpinner, PalomaAddressForm},

        data() {
            return {
                loading: true,
                address: null,
                model: null,
                type: this.$route.params['type']
            }
        },

        mounted() {
            this.loadCustomer();
        },

        methods: {

            loadCustomer() {

                this.loading = true;

                paloma.customer
                    .get()
                    .then(customer => {
                        this.address = this._findAddress(customer);
                        this.model = this._findAddressModel(customer);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            submit() {

                this.loading = true;

                paloma.customer
                    .updateAddress(this.type, this.address)
                    .then(() => {
                        paloma.events.$emit('paloma.success', 'customer.account.address_saved');
                        this.$router.push({name: 'address_list'});
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            cancel() {
                this.$router.go(-1);
            },

            _findAddress(customer) {

                if (!customer) {
                    return null;
                }

                const prop = this.type + 'Address';

                return customer.hasOwnProperty(prop)
                    ? (customer[prop] || {})
                    : null;
            },

            _findAddressModel(customer) {

                if (!customer) {
                    return null;
                }

                const prop = this.type + 'Address';

                return customer['_validation']['properties'].hasOwnProperty(prop)
                    ? customer['_validation']['properties'][prop]['properties']
                    : null;
            }
        }
    }
</script>
