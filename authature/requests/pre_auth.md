---
title: Preauthorizations
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li><a href="/authature/requests/index.html">Requests</a></li>
  <li>Payment Preauthorization</li>
</ol>

#Preauthorize a Payment

> This call is under development and subject to change

Verify that you are able to use a users access_token to make a payment request _before_ capturing the actual payment.

##Endpoint

    https://app.sign2pay.com/api/v2/payment/authorize

##Authorization Header

    Authorization: Bearer [access_token you are validating]

##Request

    POST https://app.sign2pay.com/api/v2/payment/authorize
    client_id=[your authature client id]
    &device_uid=[unique id for this device]

##Response

    {
      "authorization_id":"55802ad24e696337a5140000"
    }

##Response Headers

    HTTP/1.1 201 OK
    Content-Type: application/json; charset=utf-8
