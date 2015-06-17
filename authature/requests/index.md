---
title: Making Requests
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li>Requests</li>
</ol>


## Bearer Token Requests

When using your stored access_token to make a read request to our API, Authorization header should be set with Bearer and your access_token

##Authorization Header

    Authorization: Bearer [access_token]


| Token Requests  |
| :------------ |
| [Verify an Access Token](/authature/requests/verify_token.html) |
| [Preauthorize a Payment](/authature/requests/pre_auth.html) |



## Client Authenticated Requests

Any calls to our API that require client authentication must include the Authorization header set to the Base64 encoded client_id + client_secret. It is important to note that your Client Secret should never be public knowledge and is to be used for server to server calls only.

##Authorization Header

    Authorization: Basic [Base64 encoded client credentials client_id, client_secret]


| Authenticated Requests  |
| :------------ |
| [Capture a Payment](/authature/requests/payment.html) |
| [Request Signature Image](/authature/requests/signature.html) |

##Errors

[Reponses](/authature/error_responses.html)