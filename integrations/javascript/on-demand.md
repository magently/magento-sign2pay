---
layout: default
title: On Demand JavaScript
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/integrations/index.html">Integrations</a></li>
  <li><a href="/integrations/javascript/index.html">JavaScript</a></li>
  <li>On Demand</li>
</ol>

# On Demand Javascript

If you want/need control over when to initiate the Sign2Pay Risk Assessment, it is possible to call it from your own js, providing that all required param values are available.

In this scenario, you include the asynchronous script as usual, but without providing the window.sign2payOptions object. When you have all of the required data, simply create the window.sign2payOptions, and call window.s2p.options.initTransport();.

To ensure the sign2pay.js has completed loading and the s2p namespace is available for use, it’s wise to wrap this call within an interval. An example of how you might approach this is shown below.

## Script

{% highlight javascript %}
jQuery(document).ready(function($) {
  (function() {
    var mySleep=setInterval(function(){sleep()},3000);
    function sleep(){
      if(typeof(window.s2p) !== "undefined" ){
        window.clearInterval(mySleep);
        window.sign2PayOptions = {
          merchant_id: "e29550b84e6963064d000000",
          token: "52fa46da537061f622000000",
          el : '#sign2pay',
          address: "Any Street 53",
          amount: 1780,
          checkout_type: "multi",
          city: "Anytown",
          country: "BE",
          domain: "sign2pay.com",
          email: "example@gmail.com
",
          first_name: "Pink",
          last_name: "Floyd",
          postal_code: "2018",
          ref_id: "b133441981683b86"
        };
        window.s2p.options.initTransport();
      }
    }
  })();
});
(function() {
    var s = document.createElement("script");
    s.type = "text/javascript";
    s.src = "//sign2pay.com/merchant.js";
    s.async = true;
    t = document.getElementsByTagName('script')[0];
    t.parentNode.insertBefore(s, t);
  })();
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
