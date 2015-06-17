---
title: User Params
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li><a href="/authature/access_grants.html">Authorization Grants</a></li>
  <li>User Params</li>
</ol>

#Passing Additonal User Parameters

Depending on your needs, you may have collected information for your users that you don't want them to have to fill in again. In this case, you can pass a "user_params" object to prefill the data for them when asking for authorization.

By identifying the user in advance, they will get to signing sooner! If you have it, send it.

##Request

    GET https://app.sign2pay.com/oauth/authorize?
    client_id=[your authature client id]
    &redirect_uri=[your redirect uri]
    &response_type=code
    &state=[client session id]
    &device_uid=[unique id for this device]
    &scope=authenticate
    &user_params[identifier]=rick@sign2pay.com
    &user_params[first_name]=Rick
    &user_params[last_name]=Floyd
    &user_params[mobile]=32484123456