---
title: Verify Token
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li><a href="/authature/requests/index.html">Requests</a></li>
  <li>Verify an Access Token</li>
</ol>

#Verify an Access Token

You can quickly check the validity of a users access token

###Endpoint

    https://app.sign2pay.com/oauth/token

###Authorization Header

    Authorization: Bearer [access_token you are validating]

###Request

    GET https://app.sign2pay.com/oauth/token?
    client_id=c509fd593742b6b08adf4f0b41a4801c
    &scope=authenticate
    &device_uid=[unique id for device making request]

###Response

    {
      "status": "ok"
    }

###Response Headers

    HTTP/1.1 200 OK
    Content-Type: application/json; charset=utf-8