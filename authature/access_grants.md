---
title: Authorization Grants
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li>Authorization Grants</li>
</ol>

#Authorization Grants

Requesting a users authorization take the form of a uri with the request parameters uri encoded.


###Endpoint

    https://app.sign2pay.com/oauth/authorize

###Request

    GET https://app.sign2pay.com/oauth/authorize?
    client_id=[your authature client id]
    &redirect_uri=[your redirect uri]
    &response_type=code
    &state=[client session id]
    &device_uid=[unique id for this device]
    &scope=authenticate

---

###Response

When the redirect uri represents a native application, the authorization is returned as a json object. Otherwise, it will be sent as a redirect to the uri provided.

    {
      "code" : "829de1eb40385ebdfcc854e2ba94fb96",
      "state" : [client session id]
    }


####Response Headers

    HTTP/1.1 200 OK
    Content-Type: application/json; charset=utf-8


##Optional Param

- [User Parameters](/authature/user_params.html)
- [UX Style](/authature/ux_styles.html)