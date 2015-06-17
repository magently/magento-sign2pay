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


#Authorize & Capture Error Responses

| Error             | Error Description               | Code     |
|:------------------|:--------------------------------| --------:|
|HAS_A_VERIFIED_ACCOUNT| Sorry, but you will need to verify your bank account with us before being able to use Sign2Pay again. Keep an eye on your transaction records for a transfer from us. It will have a verification pin in the remarks.|403
|HAS_NO_ACCOUNTS_FLAGGED_FOR_REVIEW|Sorry, but an account of yours is currently being reviewed for fraud.|403
|BELOW_GLOBAL_TRANSACTION_LIMIT|Sorry, but the amount of your purchase is higher than S2P is processing at the moment.|403
|BELOW_CONSUMER_TRANSACTION_LIMIT|Sorry, but the amount of your purchase is higher than is currently allowed by by your account.|403
|DEVICE_NOT_FLAGGED|Sorry, but this device is currently being reviewed for fraud.|403
|CONSUMER_EMAIL_VALID|Your email address appears to be invalid.|403
|CONSUMER_HAS_NO_FLAGGED_DEVICES|Sorry, but one of your devices is currently being reviewed for fraud.|403
|BELOW_FRAUD_RISK_SCORE_THRESHOLD|Sorry, but computer says no...|403
|BELOW_GLOBAL_VELOCITY_LIMIT|Sorry, but computer says too soon!|403
|BELOW_CONSUMER_VELOCITY_LIMIT|Sorry, but computer says too soon!|403
|MERCHANT_RISK_SCORE_THRESHOLD|This merchant has been disabled|403
|MERCHANT_ENABLED|This merchant has been disabled|403
|MERCHANT_PAYMENTS_ENABLED|Sign2Pay Payment processing has been disabled|403
|CONSUMER_NOT_BANNED|We're sorry, but this account cannot be used with Sign2Pay|403
|GLOBAL_PAYMENTS_ENABLED|Sign2Pay Payment processing has been disabled|403
|BELOW_CONSUMER_DAILY_LIMIT|Sorry, but you've reached your daily limit for Sign2Pay transactions.|403
|GENERIC_FAIL_MESSAGE|We are currently unable to process this transaction. Please select an alternative payment method.|403














