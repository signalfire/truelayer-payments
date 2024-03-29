# TrueLayer Payments Package

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d2242da11423415e840699cf87b71026)](https://www.codacy.com/manual/signalfire/truelayer-payments?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=signalfire/truelayer-payments&amp;utm_campaign=Badge_Grade)

Allows taking payments via the [TrueLayer](https://truelayer.com/) Fintech API

## Authentication

To make a payment request we first have to get a token. To do this we must authenticate with TrueLayer...

1.  Firstly create a instance of the request class. This is assuming sandbox. Change URL's when live.

```php
$request = new Signalfire\TruePayments\Request([
  'base_uri' => 'https://auth.truelayer-sandbox.com',
  'timeout'  => 60,
]);
```

2.  Then instantiate a credentials object passing in the clientId and clientSecret from TrueLayer

```php
$credentials = new Signalfire\TruePayments\Credentials($clientId, $clientSecret);
```

3.  Then create the auth object passing the $request and $credentials

```php
$auth = new Signalfire\TruePayments\Auth($request, $credentials);
```

4.  Call the getAccessToken method

```php
$response = $auth->getAccessToken();
```

5.  The method will return an array containing the token to use. The token will be a child of the body element of the returned array in a key of access_token. If there is an exception in the request an array containing the element error = true will be returned along with the reason. 

On success...

```php
[
  [statusCode] => 200
  [reason] => 'OK'
  [body] => [
    [access_token] => '...',
    [expires_in] => 3600,
    [token_type] => 'Bearer',
    [scope] => 'payments'
  ]
]
```
On error...

```php
[
  [error] => true,
  [reason] => '...'
]
```

## Creating a payment

1.  Recreate the request instance with the new API basepath for payments...

```php
$request = new Signalfire\TruePayments\Request([
   'base_uri' => 'https://pay-api.truelayer-sandbox.com',
   'timeout'  => 60,
]);
```

2.  Create an instance of the payment class, passing in the request and token from body

```php
$payment = new Signalfire\TruePayments\Payment($request, $token['body']['access_token']);
```

3.  Call the createPayment method on $payment passing the details for the request

```php
$response = $payment->createPayment([
  'amount' => 350,
  'currency' => 'GBP',
  'remitter_reference' => 'ECOM-12345-ABCD',
  'beneficiary_name' => 'Ecommerce Shop',
  'beneficiary_sort_code' => '102030',
  'beneficiary_account_number' => '88881234',
  'beneficiary_reference' => 'ecommerce-12345', 
  'redirect_uri' => 'http://www.ecommerce.com/redirect'
]);
```

4.  In your code follow the TrueLayer link to authorise payment directly with bank. This is found as the first element in a results array inside body in response...

```php
$response['body']['results'][0]['auth_uri']
```

5.  You will be returned back to the page on your site that you passed as 'redirect_uri' when you created the payment (This URI has to be whitelisted in the TrueLayer console). Once returned to site to check the status of the payment (that it has been paid and has status of executed) by calling the following method passing in the payment_id appended to the 'redirect_uri' on returning to your site.

```php
$response = $payment->getPaymentStatus($payment_id);
```

## Tests

To run tests call ```composer test```