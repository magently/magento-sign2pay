---
title: Payment
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li><a href="/authature/requests/index.html">Requests</a></li>
  <li>Payment</li>
</ol>

#Capture a Payment

> This call is under development and subject to change

> This is an authenticated request.

An authenticated client can make a request to capture a payment using an access token anytime as long as the access token is authorized for "preapproval". An *amount* attribute is required for this request.

###Endpoint

    https://app.sign2pay.com/api/v2/payment/capture/:authorization_id

###Authorization Header

    Authorization: Basic [encodes credentials client_id, client_secret]

###Request

    POST token=[access_token]
    &device_uid=[unique id for device making request]
    &authourization_id=[authorization_id returned by preauthorization]
    &ref_id=[your order/transaction id]
    &amount=1000

###Response

    {
      "purchase_id":"55802ad24e696337a5140000"
    }