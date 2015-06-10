---
title: Sign2Pay Merchant Applications API
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/api/index.html">API</a></li>
  <li>Merchant Applications</li>
</ol>

## Applications

You are encouraged to create one or more application per merchant directly in the merchant create method. Provide a separate application for each storefront. Every application must have it’s own implementation and postback URL.

| Attribute         | Required            | Description                         |
| ----------------- |:---------------:| ------------------------------------|
| name       | yes          | Storefront name
| description       | yes   | Displays on Payment UI under your Application name
| logo              | no    | Brands the Payment UI. Provide as a publicly reachable URL and we'll handle grabbing it. (Aim for a square!)
| ref_id            | no    | If you are integrating multiple applications, pass your own ID to reference back to the site you’re integrating.
| implementation_url  | yes   | URL where the requests will originate
| postback_url        | no    | URL where Sign2Pay should post payment details
| mode                | no    | test, live, fail, archived, defaults to test

To create applications for an existing merchant, you must use Token authentication. After signup you will have received an API access token to authenticate with. It is important that this access token is kept strictly private. If it ever becomes compromised, you must revoke the old access token and generate a new one as soon as possible.

## POST

Creates a new merchant application.

{% highlight bash %}
https://sign2pay.com/api/v2/applications/[merchant_id].json

{% endhighlight %}

{% highlight json %}
{
"application": {
    "ref_id": "0123456789",
    "name": "Dicki, Kuhn and Hintz",
    "description": "Business-focused optimizing product",
    "implementation_url": "http://trompmante.biz/tad",
    "postback_url": "http://trompmante.biz/tad/postback",
    "logo" : "http://icons.iconarchive.com/icons/yellowicon/game-stars/256/Mario-icon.png",
    "mode" : "test"
  }
}
{% endhighlight %}

## PUT

Updates an existing merchant application.


{% highlight bash %}
https://sign2pay.com/api/v2/applications/[token].json

{% endhighlight %}

{% highlight json %}
{
"application": {
    "ref_id": "0123456789",
    "name": "Dicki, Kuhn and Hintz",
    "description": "Business-focused optimizing product",
    "implementation_url": "http://trompmante.biz/tad",
    "postback_url": "http://trompmante.biz/tad/postback",
    "logo" : "http://icons.iconarchive.com/icons/yellowicon/game-stars/256/Mario-icon.png",
    "mode" : "live"
  }
}
{% endhighlight %}

## Response

Returns an application object

{% highlight json %}
{
    "token": "5485855f4e69637dd21d0000",
    "ref_id": "0123456789",
    "name": "Dicki, Kuhn and Hintz",
    "description": "Business-focused optimizing product",
    "implementation_url": "http://trompmante.biz/tad",
    "postback_url": "http://trompmante.biz/tad/postback",
    "logo": "https://s3-eu-west-1.amazonaws.com/s2p-test/merchants/bradtke-heller/merchant_applications/logos/5485855f4e69637dd21d0000/original.png?1418036575",
    "mode": "test",
  	"created_at": "2014-12-08T11:02:55.645Z",
    "updated_at": "2014-12-08T11:02:55.645Z"
}
{% endhighlight %}

## Errors

| Code      | Description
| ----------|---------------|
| 201       | Application created
| 400       | Missing or malformed input fields
| 401       | Invalid authorization
| 403       | Validation error(s)
| 429       | Rate limited: enhance your calm

## Authorization

You need to pass a [Token Authorization header](authorization.html).
