const config =  {

    account: {

        customRoutes: [],

        /**
         * If true, the "download invoice PDF" button will be displayed in the order details view.
         */
        orderInvoicePdfDownloadAvailable: false
    },

    checkout: {

        /**
         * If true, users are allowed to make purchases as guest (not logged in).
         */
        allowGuests: true,
    },

    customer: {

        requireGender: false,

        requireDateOfBirth: false,

        /**
         * If true, an email address confirmation field is displayed during customer registration.
         */
        confirmEmailAddress: false,
    }

};

export default config;