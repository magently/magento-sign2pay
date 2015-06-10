---
layout: default
title: Magento integration
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/integrations/index.html">Integrations</a></li>
  <li>Magento</li>
</ol>

# Integrating Sign2Pay with Magento

Sign2Pay has native integration with Magento. This guide will show you how to upload and configure our official extension.

## Create an Application

The first step is to register your merchant account with us. Our general setup guide has you covered. Please go ahead and follow that, we’ll wait here when you get back. ;-) [Go to general setup now.](http://docs.sign2pay.com/merchant-admin/)

## Download Module

After you’ve created your merchant application with us, choose the native Magento integration. In your Sign2Pay admin homepage choose “Manage Application” and then select Magento as the implementation type.

<img src="/images/integrations/magento/Schermafbeelding_2014-12-02_om_14_30_36-1024x899.png">

You can then download the module on the right hand side of the screen.

<img src="/images/integrations/magento/Schermafbeelding_2014-12-02_om_14_30_361-1024x899.png">

You can also download the extension directly from our website: [https://sign2pay.com/integrations/magento/latest.tgz](https://sign2pay.com/integrations/magento/latest.tgz).

## Installation

Installation of our extension works like any other Magento extension. In the Magento Admin, choose System > Magento Connect > Magento Connect Manager:

<img src="/images/integrations/magento/Schermafbeelding_2014-12-03_om_10_33_51-1024x899.png">

After entering your admin password again, look for the heading “Direct package file upload”. At step 2 (Upload Package File) choose the extension file you’ve downloaded from our website and click Upload.

<img src="/images/integrations/magento/Schermafbeelding_2014-12-03_om_10_39_11-1024x899.png">

This will install the extension. You should see the process at the bottom of the screen. When it’s installed, you can return to the Magento Admin.

## Configuration

Choose System > Configuration.

In the menu on the left, choose *Payment Methods*. Sign2Pay will be listed as one of the payment methods. Click on the heading Sign2Pay. You should see this screen:

<img src="/images/integrations/magento/Schermafbeelding-2014-12-03-om-10.43.19-1024x899.png">

In the fields for Merchant ID, Application Token and API Token fill in the values you find in your Application Settings in the [Sign2Pay Merchant Admin](https://merchant.sign2pay.com/).

When you are done, click *Save Config* at the top of the screen.
