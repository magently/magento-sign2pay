---
title: Access Tokens
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li>Access Tokens</li>
</ol>

#Access Tokens

> This is an authenticated request.

Exchanging Authorization Code for an Access Token

##Endpoint

    https://app.sign2pay.com/oauth/token

##Authorization Header

    Authorization: Basic [encodes credentials client_id, client_secret]

##Request


    POST https://app.sign2pay.com/oauth/token
    client_id=[your authature client id]
    &redirect_uri=[your redirect uri]
    &code=[authorization code]
    &state=[client session id]
    &scope=authenticate


---

##Response

    "access_token": {
      "token": "87724f208bc194ca9e5e66d556de53a5e5d08d7de55327000793e6a7c80009e4",
      "type": "bearer",
      "scopes": "authenticate",
      "user": {
        "first_name": "Rick",
        "last_name": "Floyd",
        "identifier": "example@sign2pay.com"
      },
      "expires": "2025-06-16T10:48:24.025+00:00"
    }

##Response Headers

    HTTP/1.1 201 OK
    Content-Type: application/json; charset=utf-8

##Errors

[Reponses](/authature/error_responses.html)