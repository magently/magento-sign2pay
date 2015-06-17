---
title: Error Responses
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li>Error Responses</li>
</ol>

#Authature Error Responses

| Error             | Error Description               | Code     |
|:------------------|:--------------------------------| --------:|
|invalid_request    |The request is missing a required parameter, includes an unsupported parameter value, or is otherwise malformed.|400
|unauthorized_client|The client is not authorized to request an access token using this method.|401
|access_denied      |The user or authorization server denied the request.|403
|unsupported_response_type|The authorization server does not support this response type.|400
|invalid_scope|The requested scope is invalid, unknown, or malformed.|400
|server_error|The authorization server encountered an unexpected condition that prevented it from fulfilling the request.
|temporarily_unavailable|The authorization server is currently unable to handle the request due to a temporary overloading or maintenance of the server.
|missing_state|Client is not passing state param. This is required for your own protection.|400
|invalid_client|Client authentication failed|401
|invalid_grant|The provided authorization grant is invalid, expired, revoked, does not match the redirection URI used in the authorization request, or was issued to another client.|400
|unsupported_grant_type|The authorization grant type is not supported by the authorization server.|400
|invalid_token|The access token was revoked, has expired, or is invalid|400


