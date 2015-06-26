'use strict';

(function($) {

    var throwError = function(msg) {
        throw new Error('Sign2Pay: ' + msg);
    }

    var Sign2Pay = (function (settings) {

        /**
         * Sign2Pay constructor
         *
         * @param object settings
         */

        function Sign2Pay(settings) {
            this.merchantId = settings.merchantId || throwError('No merchant id');
            this.token = settings.token || throwError('No token');
            this.baseUrl = settings.baseUrl || throwError('No base url');

            if (settings.initialize) this.initializePayment();
        }

        /**
         * @var Default options
         */

        Sign2Pay.prototype.defaultOptions = {
            domain: "sign2pay.com",
            el: "#sign2pay"
        }

        /**
         * Fetches all payment related options
         *
         * @param function callback
         */

        Sign2Pay.prototype.fetchPaymentOptions = function(callback) {
            var self = this;

            $.ajax(this.baseUrl + 'sign2pay/payment/fetchPaymentOptions', {
                type: 'POST',
                dataType: 'json',
                success: function(options) {
                    var options = $.extend(self.defaultOptions, options, {
                        merchantId: self.merchantId,
                        token: self.token
                    });

                    callback(options);
                },
                error: function(err) {
                    console.log(err);
                    throwError('Could not fetch payment options');
                }
            });
        }

        /**
         * Perform riskAssessment
         */

        Sign2Pay.prototype.riskAssessment = function() {
            var self = this;

            var callback = function(options) {
                window.sign2PayOptions = options;
                window.s2p.options.initTransport();
            }

            this.fetchPaymentOptions(callback);
        };

        /**
         * Initialize payment
         */

        Sign2Pay.prototype.initializePayment = function() {
            var self = this;

            var callback = function(options) {
                options['launch'] = "on_load";
                options['map'] = {};
                options['success']  = function() {
                    $(".s2p-button-text").addClass("button btn-cart").html("Pay with Sign2Pay");
                    $(".loading").hide();
                };
                options['close'] = function() {
                    window.location = self.baseUrl + "sign2pay/payment/cancel";
                }

                window.sign2PayOptions = options;
                window.s2p.options.initTransport();
            }

            this.fetchPaymentOptions(callback);
        }

        return Sign2Pay;
    })();

    $(document).ready(function() {
        $('#opc-shipping_method .button, .shipping_method_handle').on("click", function (event) {
            window.sign2pay.riskAssessment();
        });

        //check if shipping methode is allready filled in or not
        if ($('input[name=shipping_method]:checked').length > 0) {
            window.sign2pay.riskAssessment();
        }
    });

    $(window).load(function() {
        if (!s2pOptions || !s2pOptions['merchantId'] || !s2pOptions['token']) {
            throwError('The Sign2Pay Module is enabled, but you are missing required settings.');
        } else {
            window.sign2pay = new Sign2Pay(s2pOptions);
        }
    });

})(jQuery.noConflict());
