---
layout: default
title: Signature Capture
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li>Signature Capture</li>
</ol>

#Signature Capture

Use Authature for simple signature capture. Facilitate the signing of documents that do not require user authentication. When configuring your client, pass "capture" in the scope attribute.

By granting authorization for this scope, you will be able to use their access token to retrieve the image data to display their signature or embed within a document. Basic contact detail is returned as part of the token response.

###Request

<pre><code>https://app.sign2pay.com/oauth/authorize?
client_id=[your authature client id]
&amp;redirect_uri=[your redirect uri]
&amp;response_type=code
&amp;state=[client session id]
&amp;device_uid=[unique id for device making request]
&amp;scope=<strong>capture</strong>
</code></pre>

###Response

<pre>
<code>{
  "token": "87724f208bc194ca9e5e66d556de53a5e5d08d7de55327000793e6a7c80009e4",
  "type": "bearer",
  "scopes": "capture",
  "user": {
    "first_name": "Rick",
    "last_name": "Floyd",
    "identifier": "example@sign2pay.com"
  },
  "expires": "2025-06-16T10:48:24.025+00:00"
}</code>
</pre>

###Response Headers

    HTTP/1.1 201 OK
    Content-Type: application/json; charset=utf-8

###Options

- [User Parameters](/authature/user_params.html)
- [UX Style](/authature/ux_styles.html)