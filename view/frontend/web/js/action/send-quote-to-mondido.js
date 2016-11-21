/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Customer store credit(balance) application
 */
/*global define,alert*/
define(
    [
        'ko',
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/resource-url-manager',
        'Magento_Checkout/js/model/error-processor',
        'Magento_SalesRule/js/model/payment/discount-messages',
        'mage/storage',
        'mage/translate',
        'Magento_Checkout/js/action/get-payment-information',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (
        ko,
        $,
        quote,
        urlManager,
        errorProcessor,
        messageContainer,
        storage,
        $t,
        getPaymentInformationAction,
        totals,
        fullScreenLoader
    ) {
        'use strict';
        return function (view) {
            var urls = {
                'default': '/mondido/:cartId/update',
            };

            var serviceUrl = urlManager.getUrl(urls, {'cartId': quote.getQuoteId()});
            fullScreenLoader.startLoader();

            return storage.post(
                serviceUrl, {}
            ).done(
                function (response) {
                    var transaction = JSON.parse(response);

                    if (transaction.href) {
                        fullScreenLoader.stopLoader();
                        
                        view.transaction(transaction.href + '?ts=' + Date.now());
                    } else {
                        document.location.reload(true);
                    }
                }
            ).fail(
                function (response) {
                    errorProcessor.process(response, messageContainer);
                    fullScreenLoader.stopLoader();
                    document.location.reload(true);
                }
            );
        };
    }
);
