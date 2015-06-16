---
title: Client Applications
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li>Client Applications</li>
</ol>

#Creating an Authature Client Application

When creating a new client application, you'll be asked for the following information:

| Attribute         | Required            | Description                         |
| :----------------- |:---------------:| :------------------------------------|
| name              | yes             | Your application name is displayed to the user when asking their for their authorization.
| description       | yes             | Your application description, which will be shown in user-facing authorization screens under your application name.
| redirect_uri      | yes             | Where should we return after successfully authenticating?
| scopes            | no              | The scope of permissions you are asking the user for authorization to grant.
| logo              | no              | Optional, but this brands the Authorization UI.

> More information about the different scopes is available [here](/authature/scopes)