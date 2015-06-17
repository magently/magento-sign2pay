---
layout: default
title: Authentication
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li>Authentication</li>
</ol>


#Authentication

Similar to "Login with Facebook", Authature can be used to provide user authentication for any site or install based application.

By granting authorization for this scope, the user authenticates their signature and you'll receive their basic contact detail as part of the token response.

##Request

<pre><code>https://app.sign2pay.com/oauth/authorize?
client_id=[your authature client id]
&amp;redirect_uri=[your redirect uri]
&amp;response_type=code
&amp;state=[client session id]
&amp;device_uid=[unique id for this device]
&amp;scope=<strong>authenticate</strong>
</code></pre>

##Response

When the redirect uri represents a native application, the authorization is returned as a json object. Otherwise, it will be sent as a redirect to the uri provided.

<pre>
<code>{
  "code" : "829de1eb40385ebdfcc854e2ba94fb96",
  "state" : [client session id]
}</code>
</pre>

##Options

- [User Parameters](/authature/user_params.html)
- [UX Style](/authature/ux_styles.html)

##Errors

[Reponses](/authature/error_responses.html)