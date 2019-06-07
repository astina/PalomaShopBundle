<template>
    <div v-if="notifications.length > 0" class="notifications" :class="'notifications--' + position">
        <div v-for="notification in notifications"
             :class="{'is-danger': notification.type === 'error', 'is-success': notification.type === 'success'}"
             class="notification">
            <button @click.prevent="dismiss(notification)" class="delete"></button>
            {{ notification.message }}
        </div>
    </div>
</template>

<script>

    import paloma from '../paloma';
    import utils from '../utils';

    export default {
        name: "PalomaNotifications",

        props: {
            initial: Array,
            position: {
                type: String,
                default: 'bottom-left'
            },
            defaultTimeout: {
                type: Number,
                default: 5000
            }
        },

        data() {
            return {
                notifications: [],
                max: 5
            }
        },

        mounted() {

            paloma.events.$on('paloma.error', () => {

                this.add(
                    {
                        message: this.$trans('error.general.message'),
                        type: 'error',
                    },
                    {});
            });

            paloma.events.$on('paloma.success', (message, opts) => {

                this.add(
                    {
                        message: this.$trans(message),
                        type: 'success',
                    },
                    opts);
            });

            (this.initial || []).forEach(notification => this.add(notification));
        },

        methods: {

            add(notification, opts) {

                const options = opts || {};

                while (this.notifications.length >= this.max) {
                    this.dismiss(this.notifications[this.notifications.length - 1]);
                }

                this.notifications.push(notification);

                const timeout = options.timeout || this.defaultTimeout;
                if (timeout) {
                    window.setTimeout(() => {
                        this.dismiss(notification);
                    }, timeout);
                }
            },

            dismiss(notification) {
                utils.removeElem(this.notifications, notification);
            }
        }
    }
</script>