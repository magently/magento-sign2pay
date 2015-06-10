---
layout: default
title: Single Page checkout
---

# Single Page Checkout Javascript

You’ll use the Single Page integration if: your customers enter their contact and shipping details on the same page they will select a payment method from. or returning users do not enter shipping information as it is already saved in their account info. and all required variables can be populated by parsing the DOM or calcuated via js function.

## THE MAP OBJECT

The map object within sign2PayOptions provides the means for us to parse your page for values either by providing css selectors OR if a js function is provided, we’ll call it and use the return value. The function approach is useful say if your site/application stores the amount in euros.

{% highlight javascript %}
<script>
  window.sign2PayOptions = {
    merchant_id: 'e29550b84e6963064d000000',      // grab this from your merchant pages
    token: '52fa46da537061f622000000',            // grab this from your merchant pages
    el: '#sign2pay',                              // the DOM element to initiate payment with
    checkout_type: 'single',
    domain : "sign2pay.com",
    map: {                                        // DOM elements that contain purchase values
      first_name: '#consumer_first_name',
      last_name: '#consumer_last_name',
      email: '#consumer_email',
      address: '#consumer_address',
      postal_code: '#consumer_postal_code',
      city: '#consumer_city',
      region: '#consumer_region',
      country: '#consumer_country',
      amount: #consumer_amount,
      ref_id : '#order_id'
    }
  };
  (function() {
    var s = document.createElement("script");
    s.type = "text/javascript";
    s.src = "//sign2pay.com/merchant.js";
    s.async = true;
    t = document.getElementsByTagName('script')[0];
    t.parentNode.insertBefore(s, t);
  })();
</script>
{% endhighlight %}

## Payment Button

Next create an element on the page which will trigger the Sign2Pay method. This element should be hidden by default. Our script will show this element as soon as the Risk Assessment passes. This means that potentially fraudulent consumers won’t even see Sign2Pay as an option.

{% highlight html %}
<button id="sign2pay" style="display: none;">Pay now</button>

<!-- it could be a list item -->
<ul>
  <li>Pay with Credit Card</li>
  <li id="sign2pay" style="display: none;">Pay with your signature</li>
</ul>

<!-- or a div. Your markup, your call. -->
<div id="sign2pay" style="display: none;">Pay now</div>
{% endhighlight %}

When the consumer presses this element, the Sign2Pay window is shown on the screen and we take care of the payment process. When the payment is successful we send a [postback](/integrations/postback.html) to your server. Only when this postback succeeds, is the payment finished.
