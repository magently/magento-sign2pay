---
title: Sign2Pay Merchant Applications API
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/api/index.html">API</a></li>
  <li>Authorization</li>
</ol>

# Authorization

Sign2Pay uses tokens to allow access to the API. Access tokens are handed to every merchant directly after succesful registration. Store the tokens securely and pass one with every request. For protected resources, you must pass a valid token in a HTTP Token Authorization header.

{% highlight bash %}
Authorization: Token token="0047f40cf37dbb5cc6301d17194ed2e2"
{% endhighlight %}


[Sign into your Merchant Admin](https://merchant.sign2pay.com/merchant_applications) to view your API Token or request a new one.
