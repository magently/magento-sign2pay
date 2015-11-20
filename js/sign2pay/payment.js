'use strict';

(function($) {

    var throwError = function(msg) {
        throw new Error('Sign2Pay: ' + msg);
    }

    var Sign2Pay = (function () {

        /**
         * Sign2Pay constructor
         *
         * @param object settings
         */

        function Sign2Pay(settings) {
            this.baseUrl = settings.baseUrl || throwError('No base url');

            // Remove protocol from base url
            this.baseUrl = this.baseUrl.replace(/^http:/, '');
        }

        /**
         * Fetches payment logo
         *
         * @param function callback
         */

        Sign2Pay.prototype.fetchPaymentLogo = function(callback) {
            var self = this;

            $.ajax(this.baseUrl + 'sign2pay/payment/fetchPaymentLogo', {
                type: 'POST',
                dataType: 'json',
                success: function(options) {
                    callback(options);
                },
                error: function(err) {
                    console.log(err);
                    throwError('Could not fetch payment options');
                }
            });
        }

        /**
         * Perform logo update
         */

        Sign2Pay.prototype.logoUpdate = function() {
            var self = this;

            var $mark = $('#sign2pay-mark');

            if (!$mark.size()) return;

            var callback = function(options) {
                $mark.attr('src', options.logo);
            }

            this.fetchPaymentLogo(callback);
        };

        /**
         * Perform updates
         */

        Sign2Pay.prototype.update = function() {
            this.logoUpdate();
        };

        return Sign2Pay;
    })();

    $(window).load(function() {
        window.sign2payPayment = new Sign2Pay(s2pOptions);
    });

    window.updateSign2pay = function() {
        var interval;
        interval = setInterval(function() {
            if (typeof window.sign2payPayment !== 'object') return;
            clearInterval(interval);

            // Perform update
            window.sign2payPayment.update();
        });
    };

})(jQuery.noConflict());
