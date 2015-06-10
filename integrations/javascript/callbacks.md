---
title: Sign2Pay JavaScript callbacks
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/integrations/index.html">Integrations</a></li>
  <li><a href="/integrations/javascript/index.html">JavaScript</a></li>
  <li>Callbacks</li>
</ol>

# JavaScript Callbacks

## success

Any function provided here will be called when the Sign2Pay RiskAssessment has completed successfully.

{% highlight javascript %}
success : function(){
  console.log("risk assessment complete");
}
{% endhighlight %}

## error

Any function provided here will be called when the Sign2Pay RiskAssessment returned an error.

{% highlight javascript %}
error : function(){
  console.log("risk assessment returned an error");
}
{% endhighlight %}

## open

Any function provided here will be called when the Sign2Pay Payment Overlay is displayed.

{% highlight javascript %}
open : function(){
  console.log("Customer viewing Sign2Pay");
}
{% endhighlight %}

## close

Any function provided here will be called when the Sign2Pay Payment Overlay is closed.

{% highlight javascript %}
close : function(){
  console.log("Customer closed Sign2Pay");
}
{% endhighlight %}
