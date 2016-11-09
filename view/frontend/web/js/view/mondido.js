define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator'
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator
    ) {
        return Component.extend({
            defaults: {
                template: 'Mondido_Mondido/mondido'
            },
            isVisible: ko.observable(true),
            transaction: ko.observable(JSON.parse(window.checkoutConfig.quoteData.mondido_transaction).href),
            initialize: function () {
                this._super();

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