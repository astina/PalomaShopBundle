<template>
    <div v-if="notifications.length > 0" class="notifications">
        <div v-for="notification in notifications" class="notification is-danger">
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

        data() {
            return {
                notifications: [],
                max: 5
            }
        },

        mounted() {

            paloma.events.$on('paloma.error', () => {
                this.add(
                    {message: this.$trans('error.general.message')},
                    {timeout: 5000});
            });
        },

        methods: {

            add(notification, opts) {

                while (this.notifications.length >= this.max) {
                    this.dismiss(this.notifications[this.notifications.length - 1]);
                }

                this.notifications.push(notification);

                const timeout = (opts && opts.timeout) || null;
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