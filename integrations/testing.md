---
layout: default
title: Testing
---

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/integrations/index.html">Integrations</a></li>
  <li>Testing</li>
</ol>

# Testing

New applications are created in **test** mode.

* money is never transferred, either in or out
* mobile device checks are disabled

All our other checks are still active, as detailed below.

## Purchase limits

Our normal agreements will deny any transaction above €100 per purchase. This is a way for us to mitigate risks. We run the risk analysis precisely at this moment so that we can disable Sign2Pay as a payment method for your consumers once the total goes over €100. That way, our method doesn't even show up for the consumer.

As you process more transactions, or have other mechanisms in place to lower the risk, this limit can be increased for you.

For testing, don't use purchase amounts higher than €100 initially.

### Delivery address

When doing a test purchase, make sure the address you're using is a real address with matching postcode.

### Email

Use a normal email as far as possible. We not only check email syntax but also do a realtime background check on the email domain. The test domain *example.com* is not going to work, and neither will for example *me@gmail.com* since gmail addresses can't be just two characters.

### IBAN

If you want to use an IBAN for testing, you can use one in the test ranges:

* ZZnnnnnnnnnnnnn, for example ZZ12345
* YYnnnnnnnnnnnnn, for example YY67890

We purge test IBANs periodically. If a test IBAN is being shown as already used, just tack an extra number after it.

It's completely safe to use real IBANs in test mode too. As with email, not only is IBAN syntax checked but a connection into SEPA is made in realtime to check existence of the IBAN and the associated bank.
