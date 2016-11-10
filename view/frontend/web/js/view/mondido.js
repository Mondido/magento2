define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/checkout-data'
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator,
        selectShippingMethodAction,
        checkoutData
    ) {
        return Component.extend({
            defaults: {
                template: 'Mondido_Mondido/mondido'
            },
            isVisible: ko.observable(true),
            transaction: ko.observable(JSON.parse(window.checkoutConfig.quoteData.mondido_transaction).href),
            initialize: function () {
                this._super();

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

                return this;
            },

            navigate: function () {
            }
        });
    }
);