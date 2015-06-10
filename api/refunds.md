---
title: Sign2Pay Refund API
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/api/index.html">API</a></li>
  <li>Refunds</li>
</ol>

## Create a refund

A Purchase ID is required to create a refund. However the amount you’re refunding may be different from purchase amount.

{% highlight bash %}
POST https://sign2pay.com/api/v2/purchases/[purchase ID]/refunds.json

{% endhighlight %}

The amount to be refunded should be posted in the request body, as a JSON dictionary:

{% highlight json %}
{"refund": {"amount": 2300}}

{% endhighlight %}

Curl example

{% highlight bash %}
curl -d '{"refund": {"amount":2000}}' https://api.sign2pay.com/api/v2/purchases/21345762483653182/refunds.json -H "Content-Type: application/json" \-H "Authorization: Token token=\"23467542678547365324\""
{% endhighlight %}

## Response

Returns a refund object as JSON.

A succesful refund has response code **201 Created**

{% highlight json %}
{  
   "message":"Refund succesfully processed.",
   "data":{  
      "refund":{  
         "id":"5577faf16761734ebc280000",
         "created_at":"2015-06-10T10:53:05.306+02:00",
         "updated_at":"2015-06-10T10:53:05.306+02:00",
         "amount":2000,
         "purchase":{  
            "id":"5524f85c62616426324a0700",
            "created_at":"2015-04-08T11:43:56.875+02:00",
            "updated_at":"2015-04-08T11:44:28.040+02:00",
            "amount":603,
            "ref_id":"5524f8566261642665030200"
         },
         "entries":[  
            {  
               "id":"5577faf16761734ebc290000",
               "created_at":"2015-06-10T10:53:05.294+02:00",
               "updated_at":"2015-06-10T10:53:05.294+02:00",
               "type":"refund",
               "purchase_id":"5524f85c62616426324a0700",
               "ref_id":"5524f8566261642665030200",
               "amount":2000
            },
            {  
               "id":"5577faf16761734ebc2a0000",
               "created_at":"2015-06-10T10:53:05.301+02:00",
               "updated_at":"2015-06-10T10:53:05.301+02:00",
               "type":"refund_fee",
               "purchase_id":"5524f85c62616426324a0700",
               "ref_id":"5524f8566261642665030200",
               "amount":25
            },
            {  
               "id":"5577faf16761734ebc2b0000",
               "created_at":"2015-06-10T10:53:05.305+02:00",
               "updated_at":"2015-06-10T10:53:05.305+02:00",
               "type":"refund",
               "purchase_id":"5524f85c62616426324a0700",
               "ref_id":"5524f8566261642665030200",
               "amount":-2025
            }
         ]
      },
      "trump":{  
         "credit_transfer_id":"5577faf167617356f6050000"
      }
   }
}
{% endhighlight %}

## Errors

| Code      | Description
| ----------|---------------|
| 401       | Invalid authorization
| 429       | Rate limited: enhance your calm

## Authorization

You need to pass a [Token Authorization header](authorization.html).
