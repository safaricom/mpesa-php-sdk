# Introduction

This package seeks to help php developers implement the various Mpesa APIs without much hustle. It is based on the REST API whose documentation is available on [Safaricom Developer](http://developer.safaricom.co.ke).

## Installation using composer

 ```
 composer require safaricom/mpesa
 ```

## Configuration

At your project root, create a .env file and in it set the consumer key and consumer secret as follows
```.dotenv
MPESA_CONSUMER_KEY=[consumer key]
MPESA_CONSUMER_SECRET=[consumer secret]
MPESA_ENV=[live or sandbox]
```
 
For Laravel users, open the Config/App.php file and add `\Safaricom\Mpesa\MpesaServiceProvider::class` under providers and ` 'Mpesa'=> \Safaricom\Mpesa\MpesaServiceProvider::class` under aliases.
  
  _Remember to edit the consumer_key and consumer_secret values appropriately when switching between sandbox and live_
  

In order to transact from multiple paybill numbers or till numbers , you can change the configs at runtime using the config helper.

So we need to add the following to the services config file (config/services.php)

```php
return = [

// More configs here

'mpesa' => [
        'MPESA_CONSUMER_KEY' => env('MPESA_CONSUMER_KEY'),
        'MPESA_CONSUMER_SECRET' => env('MPESA_CONSUMER_SECRET'),
        'MPESA_ENV' => env('MPESA_ENV')
    ]
];
```

## Usage

### Confirmation and validation urls

### B2C Payment Request

This creates transaction between an M-Pesa short code to a phone number registered on M-Pesa.

```php
$mpesa= new \Safaricom\Mpesa\Mpesa();

$b2cTransaction=$mpesa->b2c($InitiatorName, $SecurityCredential, $CommandID, $Amount, $PartyA, $PartyB, $Remarks, $QueueTimeOutURL, $ResultURL, $Occasion);
```

### Account Balance Request

This is used to enquire the balance on an M-Pesa BuyGoods (Till Number)

```php
$mpesa= new \Safaricom\Mpesa\Mpesa();

$balanceInquiry = $mpesa->accountBalance($CommandID, $Initiator, $SecurityCredential, $PartyA, $IdentifierType, $Remarks, $QueueTimeOutURL, $ResultURL);
```

### Transaction Status Request

This is used to check the status of transaction.

```php
$mpesa = new \Safaricom\Mpesa\Mpesa();`

$trasactionStatus = $mpesa->transactionStatus($Initiator, $SecurityCredential, $CommandID, $TransactionID, $PartyA, $IdentifierType, $ResultURL, $QueueTimeOutURL, $Remarks, $Occasion);
```

### B2B Payment Request

This is used to transfer funds between two companies.

```php
$mpesa = new \Safaricom\Mpesa\Mpesa();

$b2bTransaction = $mpesa->b2b($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber );
```

### C2B Payment Request

This is used to Simulate transfer of funds between a customer and business.

```php 
$mpesa = new \Safaricom\Mpesa\Mpesa();

$b2bTransaction = $mpesa->c2b($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber );
```

_Also important to note is that you should have registered validation and confirmation urls where the callback responses will be sent._

### STK Push Simulation

This is used to initiate online payment on behalf of a customer.

```php
$mpesa = new \Safaricom\Mpesa\Mpesa();

$stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);
```

### STK Push Status Query

 This is used to check the status of a Lipa Na M-Pesa Online Payment.

 ```php
$mpesa = new \Safaricom\Mpesa\Mpesa();`

$STKPushRequestStatus = $mpesa->STKPushQuery($checkoutRequestID,$businessShortCode,$password,$timestamp);
```

### Callback Routes

M-Pesa APIs are asynchronous. When a valid M-Pesa API request is received by the API Gateway, it is sent to M-Pesa where it is added to a queue. M-Pesa then processes the requests in the queue and sends a response to the API Gateway which then forwards the response to the URL registered in the CallBackURL or ResultURL request parameter. Whenever M-Pesa receives more requests than the queue can handle, M-Pesa responds by rejecting any more requests and the API Gateway sends a queue timeout response to the URL registered in the QueueTimeOutURL request parameter.

### Obtaining post data from callbacks

This is used to get post data from callback in json format. The data can be decoded and stored in a database.

 ```php
 $mpesa= new \Safaricom\Mpesa\Mpesa();

 $callbackData = $mpesa->getDataFromCallback();
 ```
  
### Finishing a transaction

  After obtaining the Post data from the callbacks, use this at the end of your callback routes to complete the transaction
  
  ```php
  $mpesa = new \Safaricom\Mpesa\Mpesa();
  
  $callbackData = $mpesa->finishTransaction();
  ```

  If validation fails, pass `false` to `finishTransaction()`

  ```php
  $mpesa = new \Safaricom\Mpesa\Mpesa();
  
  $callbackData = $mpesa->finishTransaction(false);
  ```

## Multitenancy support

When handling transactions you do as follows

```php
/** Get client preferably from a database somewhere.
 * You can use a dynamic url or even an api key to identify which client the transaction belongs to.
 * This can be done using a middleware implementation triggered before the transaction is processed.
 * (Ignore this part if you don't want Multitenancy support as it will default to the values in the .env file)
 */
$clientA = [
    'MPESA_CONSUMER_KEY' => 'MPESA_CONSUMER_KEY_HERE',
    'MPESA_CONSUMER_SECRET' => 'MPESA_CONSUMER_SECRET_HERE',
    'MPESA_ENV' => 'MPESA_ENV_HERE'
];

// change the configs depending on the client
config(['services.mpesa' => $clientA ]);

// Instanciate the mpesa class ONLY after changing the configs
$mpesa = new \Safaricom\Mpesa\Mpesa();

// Do your business logic here
$b2bTransaction=$mpesa->b2b($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber );

```