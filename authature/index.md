---
layout: authature
title: Authature
---

<p style="text-align:center; float:right;">
  <a class="btn btn-flat btn-labeled btn-info btn-lg btn-quick text-lg text-slim" data-ux="inline">
    <span class="btn-label icon fa fa-lock"></span>
    <span class="sk-text">
      Login with
      <strong>Authature</strong>
    </span>
  </a>
</p>

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li>Authature</li>
</ol>

<div class="sign-thumb">
  <img src="/images/authature/4.rotate.png">
</div>

#Authature

<div class="tagline">
  "Imagine a password that stays secure, even when you share it."
</div>

Authature provides the ability for an app owner to capture, login/authenticate, pre-approve payments, or pay - all via secure signature within their application or website.

At it’s core, Authature is modelled after the <a href="https://tools.ietf.org/html/rfc6749" target="_blank">OAuth 2.0 Spec (RFC 6749)</a>, with the obvious difference that instead of logging in and granting permission, the user is asked to verify their signature thereby granting access to the client requesting access on behalf of the user.

We can wait for you to try it out, but Authature is currently in closed beta. If you have not received an invite, [drop us a line](mailto:hello@sign2pay.com?subject=Authature Beta Access) to request access.



##Getting Started


As soon as you have your access credentials, you can get started by creating your first Authature client by
[signing in](mailto:hello@sign2pay.com?subject=Authature Beta Access:Let me In!) to your client admin. Links to all of the information you should need are listed below, but as always, should you need help, don't hesitate to [let us know](mailto:support@sign2pay.com?subject=Authature Assistance).

<div class="link-lists">
  <ul class="link-list">
    <li class="link-list-title">General</li>
    <li>
      <a href="/authature/sdks/index.html">SDKs</a>
    </li>
    <li>
      <a href="/authature/error_responses.html">Errors</a>
    </li>

    <li>
      <a href="/authature/user_params.html">User Params</a>
    </li>
    <li>
      <a href="/authature/ux_styles.html">UX Style</a>
    </li>

  </ul>


  <ul class="link-list">
    <li class="link-list-title">OAuth Guides</li>
    <li>
      <a href="/authature/clients.html">Authature Client Applications</a>
    </li>
    <li>
      <a href="/authature/scopes/index.html">Authorization Scopes</a>
    </li>
    <li>
      <a href="/authature/access_grants.html">Request Authorization</a>
    </li>
    <li>
      <a href="/authature/access_tokens.html">Access Tokens</a>
    </li>
  </ul>


  <ul class="link-list">
    <li class="link-list-title">Authenticated Requests</li>

    <li>
      <a href="/authature/requests/pre_auth.html">Authorize a Payment</a>
    </li>

    <li>
      <a href="/authature/requests/payment.html">Capture a Payment</a>
    </li>

    <li>
      <a href="/authature/requests/verify_token.html">Verify an Access Token</a>
    </li>

    <li>
      <a href="/authature/requests/signature.html">Request Signature Image</a>
    </li>
  </ul>
</div>
<script type="text/javascript">
  (function() {
    $(function() {
      var config;
      config = {
        authature_site: "app.staging2pay.com",
        client_id: "c509fd593742b6b08adf4f0b41a4801c",
        response_type: "code",
        redirect_uri: "http://authature.com/oauth/callback",
        state: '60208d752c576baad4839fa4ef401472d735f2e160af8d78342f5af8b0bd537b',
        device_uid: 'f815c953ad712af1448679b90a4973e5a8544e4a886f686ed901654aa3f0b143',
        complete: function() {
          alert("Authature Complete");
        }
      };
      $(".btn-quick").on("click", function(e) {
        var authatureClient;
        e.preventDefault();
        config.scope = "authenticate";
        config.ux_style = "popup";
        return authatureClient = new window.authature.Client(config);
      });
    });

  }).call(this);

</script>