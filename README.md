# TrueLayer Payments Package

** WIP - Testing **

Allows taking payments via the [TrueLayer](https://truelayer.com/) Fintech API

## Authentication

To make a payment request we first have to get a token. To do this we must authenticate with TrueLayer...

1. Firstly create a instance of the request class

```
$request = new Signalfire\TruePayments\Request([
  'base_uri' => 'https://auth.truelayer.com',
  'timeout'  => 60,
]);
```

2. Then instantiate a credentials object passing in the clientId and clientSecret from TrueLayer

```
$credentials = new Signalfire\TruePayments\Credentials($clientId, $clientSecret);
```

3. Then create the auth object passing the $request and $credentials

```
$auth = new Signalfire\TruePayments\Auth($request, $credentials);
```

4. Call the getAccessToken method

```
$token = $auth->getAccessToken();
```

5. The method will return an array containing the token to use. The token will be a child of the body element of the returned array in a key of access_token. If there is an error an array containing the element error = true will be returned along with the reason.

## Creating a payment

1. Recreate the request instance with the new API basepath for payments...

```
$request = new Signalfire\TruePayments\Request([
   'base_uri' => 'https://pay-api.truelayer.com',
   'timeout'  => 60,
]);
```

2. Create an instance of the payment class, passing in the request and token from body

```
$payment = new Signalfire\TruePayments\Payment($request, $token['body']['access_token']);
```

3. Call the createPayment method on $payment passing the details for the request

```
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
4. Check the status of the payment (that it has been paid and has status of executed) by polling the following method passing in the simp_id from the previous response

```
$response = $payment->getPaymentStatus($response['simp_id']);
```