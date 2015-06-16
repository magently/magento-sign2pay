---
title: UX Styles
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li><a href="/authature/access_grants.html">Authorization Grants</a></li>
  <li>UX Styles</li>
</ol>

#UX Styles

For browser based implementations, you have the option of displaying the signature UI as an iframe that overlays the current page OR to link directly to a popup window. Both have their advantages, but it's your call. This param defaults to "inline"

###Inline

    GET https://app.sign2pay.com/oauth/authorize?
    client_id=[your authature client id]
    &redirect_uri=[your redirect uri]
    &response_type=code
    &state=[client session id]
    &device_uid=[unique id for this device]
    &scope=authenticate
    &ux_style=inline

###Pop-up

    GET https://app.sign2pay.com/oauth/authorize?
    client_id=[your authature client id]
    &redirect_uri=[your redirect uri]
    &response_type=code
    &state=[client session id]
    &device_uid=[unique id for this device]
    &scope=authenticate
    &ux_style=popup
