---
title: Endpoints
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li><a href="/authature/requests/index.html">Requests</a></li>
  <li>Endpoint Reference</li>
</ol>

#Endpoints

    GET   /authorize
    POST  '/token',               to: 'tokens#create'
    POST  '/revoke',              to: 'tokens#revoke'
    GET   '/token',               to: 'tokens#info'
    GET   '/signatures/:token',   to: 'signatures#show'