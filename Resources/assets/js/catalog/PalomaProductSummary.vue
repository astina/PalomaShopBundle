<script>

    import paloma from '../paloma';
    import jQuery from 'jquery';

    export default {

        name: 'PalomaProductSummary',

        data() {
            return {
                variant: null,
                product: null,
            }
        },

        mounted() {

            paloma.events.$on('paloma.variant_selected', (variant, product) => {
                this.variant = variant;
                this.product = product;
                this.refreshData();
            });
        },

        methods: {

            refreshData() {

                if (!this.variant) {
                    return;
                }

                const variant = this.variant;

                /**
                 * Refresh content of HTML elements with data-variant-prop="..." attributes.
                 */
                jQuery(this.$el).find('[data-variant-prop]').each(function() {
                    const elem = jQuery(this);
                    const prop = elem.data('variant-prop');
                    elem.html(variant[prop]);
                });

            }
        }
    }
</script>