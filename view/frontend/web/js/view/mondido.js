define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/quote',
        'Mondido_Mondido/js/action/send-quote-to-mondido',
        'iframeResizer',
        'jquery'
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator,
        selectShippingMethodAction,
        checkoutData,
        quote,
        sendQuoteToMondidoAction,
        iframeResizer,
        $
    ) {
        return Component.extend({
            defaults: {
                template: 'Mondido_Mondido/mondido'
            },
            isVisible: ko.observable(true),
            isLoading: ko.observable(false),
            transaction: ko.observable(JSON.parse(window.checkoutConfig.quoteData.mondido_transaction).href),
            initialize: function () {
                this._super();
                var self = this;

                var shippingMethod = {'carrier_code': 'flatrate', 'method_code': 'flatrate', 'carrier_title': 'Flat rate', 'method_title': 'Fixed'};
                selectShippingMethodAction(shippingMethod);
                checkoutData.setSelectedShippingRate(shippingMethod['carrier_code'] + '_' + shippingMethod['method_code']);

                stepNavigator.registerStep(
                    'mondido',
                    null,
                    'Secure Payment with Mondido',
                    this.isVisible,
                    _.bind(this.navigate, this),
                    20
                );

                quote.totals.subscribe(function (newValue) {
                    self.reload();
                });

                this.resizeIframe();

                return this;
            },
            resizeIframe: function() {
                el = $('#mondido-iframe');

                if (el.length != 1) {
                    setTimeout(this.resizeIframe, 500);
                    return;
                }

                $('#mondido-iframe').iFrameResize({checkOrigin: false});
            },
            navigate: function () {
            },
            reload: function(newValue) {
                sendQuoteToMondidoAction(this);
            }
        });
    }
);