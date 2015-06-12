---
title: Sign2Pay Payout API
layout: default
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/api/index.html">API</a></li>
  <li>Payouts</li>
</ol>

## Payouts

A payout contains the details for all line items in your ledger that have been remitted for payment.

| Attribute         | Description
| ----------------- |------------
| gross             | Total sum of transactions before applying adjustments
| adjustments       | sum of all Sign2Pay fees associated with this payout
| total             | net sum of transactions after applying adjustments
| ref_id            | If you are integrating multiple applications, pass your own ID to reference back to the site you're integrating
| line_items        | This is an array of all transactions within a given payout

## Line items

A line item is an entry in your Merchant Ledger that represents a transaction. This could be a purchase, a purchase fee, refund, refund fee etc.

| Attribute         | Description
| ----------------- |------------
| type              | The type of entry
| adjustments       | sum of all Sign2Pay fees associated with this payout
| total             | net sum of transactions after applying adjustments
| ref_id            | If you are integrating multiple applications, pass your own ID to reference back to the site you’re integrating.
| line_items        | this is an array of all transactions within a given payout

# GET

Fetches payout object details based on date OR payout_type.

## Payout by Date

{% highlight bash %}
https://sign2pay.com/api/v2/reports/payouts/date/[date]
{% endhighlight %}
(_date format: 2015-04-14_)

Passing a date param  will return payouts generated on that date, or an empty array if none exist.

## Payout by Type

{% highlight bash %}
https://sign2pay.com/api/v2/reports/payouts/[payout_type]

{% endhighlight %}

| Type          | Description
| --------------|------------
| latest        | Your most recent payout
| pending       | All line items in your ledger that have yet to be remitted at the time of request

## Response

Returns a payout object as JSON

{% highlight json %}
{
    "id": "54e736dc686172292e0f0000",
    "created_at": 2015-02-19T06:35:53.164+01:00,
    "updated_at": 2015-02-19T06:35:53.164+01:00,
    "gross": 920,
    "adjustments": -48,
    "total": 872,
    "line_items": [
        {
            "id": "54e736dc686172292e100000",
            "created_at": "2015-01-29T21:10:34.331+01:00",
            "updated_at": "2015-02-19T06:35:53.164+01:00",
            "type": "purchase",
            "purchase_id": "54ca93386261641b3c040000",
            "ref_id": "GQ446738",
            "amount": 920
        },
        {
            "id": "54e736dc686172292e110000",
            "created_at": "2015-01-29T21:10:34.408+01:00",
            "updated_at": "2015-02-19T06:35:53.210+01:00",
            "type": "purchase_fee",
            "purchase_id": "54ca93386261641b3c040000",
            "ref_id": "GQ446738",
            "amount": -48
        }
    ]
}
{% endhighlight %}

## Errors

| Code      | Description
| ----------|---------------|
| 401       | Invalid authorization
| 429       | Rate limited: enhance your calm

## Authorization

You need to pass a [Token Authorization header](authorization.html).
