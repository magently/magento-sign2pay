---
layout: default
title: Multi Page checkout
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/integrations/index.html">Integrations</a></li>
  <li><a href="/integrations/javascript/index.html">JavaScript</a></li>
  <li>Multi Page</li>
</ol>

# Multi Page Checkout Javascript

You’ll use the Multi Page integration if:

* your customers contact and shipping information has been entered before they are sent to a seperate payment method page.
* you prefer to provide all of the required params within the window.sign2PayOptions object instead of adding hidden inputs to your markup.

The Multi Page integration looks similar to the Single Page one, however, each of the required param’s values are set rather than supplying a map to retrieve them.

First paste your JavaScript snippet on the checkout page. You’ll find the snippet on your [Merchant Applications](https://merchant.sign2pay.com/merchant_applications) page.

{% highlight javascript %}
<script>// <![CDATA[
  window.sign2PayOptions = {
    merchant_id: 'e29550b84e6963064d000000',  // grab this from your merchant pages
    token: '52fa46da537061f622000000',        // grab this from your merchant pages
    el : '#sign2pay',                         // DOM element that initiates payment
    checkout_type: 'multi',
    domain : "sign2pay.com",
    first_name: "[first_name]",
    last_name: "[last_name]",
    email: "[email]",
    address: "[address]",
    postal_code: "[postal_code]",
    city: "[city]",
    region: "[region]",
    country: "[country]",
    amount:[amount in cents],
    ref_id : "[your order id]"
  };
  (function() {
    var s = document.createElement("script");
    s.type = "text/javascript";
    s.src = "//sign2pay.com/merchant.js";
    s.async = true;
    t = document.getElementsByTagName('script')[0];
    t.parentNode.insertBefore(s, t);
  })();
// ]]></script>
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
