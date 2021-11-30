# MPESA-PHP-SDK
[![GitHub forks](https://img.shields.io/github/forks/safaricom/mpesa-php-sdk?style=flat-square)](https://github.com/safaricom/mpesa-php-sdk/network)
[![GitHub stars](https://img.shields.io/github/stars/safaricom/mpesa-php-sdk?color=yellow&style=flat-square)](https://github.com/safaricom/mpesa-php-sdk/stargazers) [![GitHub issues](https://img.shields.io/github/issues/safaricom/mpesa-php-sdk?color=red&style=flat-square)](https://github.com/safaricom/mpesa-php-sdk/issues) [![GitHub license](https://img.shields.io/github/license/safaricom/mpesa-php-sdk?style=flat-square)](https://github.com/safaricom/mpesa-php-sdk)

## Introduction

This package seeks to help php developers implement the various Mpesa APIs without much hustle. It is based on the REST API whose documentation is available on https://developer.safaricom.co.ke.
 
 ## Installation using composer<br>
 `composer require safaricom/mpesa`<br>
 
 
 ## Configuration<br>
 At your project root, create a .env file and in it set the consumer key and consumer secret as follows   
 ```PHP
 MPESA_CONSUMER_KEY= [consumer key]
 MPESA_CONSUMER_SECRET=[consumer secret]
 MPESA_ENV=[live or sandbox]
 ```
 For Laravel users, open the Config/App.php file and add `\Safaricom\Mpesa\MpesaServiceProvider::class` under providers and ` 'Mpesa'=> \Safaricom\Mpesa\MpesaServiceProvider::class` under aliases. <br>
  
  > **NOTE:**
  > _Remember to edit the consumer_key and consumer_secret values appropriately when switching between sandbox and live_

  
## Usage
 
### Confirmation and validation urls

### B2C Payment Request
 
This creates transaction between an M-Pesa short code to a phone number registered on M-Pesa.
 
```PHP
$mpesa= new \Safaricom\Mpesa\Mpesa();

$b2cTransaction=$mpesa->b2c($InitiatorName, $SecurityCredential, $CommandID, $Amount, $PartyA, $PartyB, $Remarks, $QueueTimeOutURL, $ResultURL, $Occasion);
```
<br>


### Account Balance Request
 
This is used to enquire the balance on an M-Pesa BuyGoods (Till Number)

```PHP
$mpesa= new \Safaricom\Mpesa\Mpesa();

$balanceInquiry=$mpesa->accountBalance($CommandID, $Initiator, $SecurityCredential, $PartyA, $IdentifierType, $Remarks, $QueueTimeOutURL, $ResultURL);
```

<br>

### Transaction Status Request
This is used to check the status of transaction. 

```PHP
$mpesa= new \Safaricom\Mpesa\Mpesa();

$trasactionStatus=$mpesa->transactionStatus($Initiator, $SecurityCredential, $CommandID, $TransactionID, $PartyA, $IdentifierType, $ResultURL, $QueueTimeOutURL, $Remarks, $Occasion);
```
<br>


### B2B Payment Request

This is used to transfer funds between two companies.

```PHP
$mpesa= new \Safaricom\Mpesa\Mpesa();

$b2bTransaction=$mpesa->b2b($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber );
```
<br>


### C2B Payment Request

This is used to Simulate transfer of funds between a customer and business.


```PHP
$mpesa= new \Safaricom\Mpesa\Mpesa();

$b2bTransaction=$mpesa->c2b($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber );
```
<br>

>NOTE: _Also important to note is that you should have registered validation and confirmation urls where the callback responses will be sent._

<br>

### STK Push Simulation

This is used to initiate online payment on behalf of a customer.

```PHP
$mpesa= new \Safaricom\Mpesa\Mpesa();

$stkPushSimulation=$mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);
```

<br>

### STK Push Status Query

 This is used to check the status of a Lipa Na M-Pesa Online Payment.
 
```PHP
$mpesa= new \Safaricom\Mpesa\Mpesa();`

`$STKPushRequestStatus=$mpesa->STKPushQuery($checkoutRequestID,$businessShortCode,$password,$timestamp);
```

<br>


### Callback Routes
M-Pesa APIs are asynchronous. When a valid M-Pesa API request is received by the API Gateway, it is sent to M-Pesa where it is added to a queue. M-Pesa then processes the requests in the queue and sends a response to the API Gateway which then forwards the response to the URL registered in the CallBackURL or ResultURL request parameter. Whenever M-Pesa receives more requests than the queue can handle, M-Pesa responds by rejecting any more requests and the API Gateway sends a queue timeout response to the URL registered in the QueueTimeOutURL request parameter.

<br>

### Obtaining post data from callbacks
 This is used to get post data from callback in json format. The data can be decoded and stored in a database.
 
 ```PHP
 $mpesa= new \Safaricom\Mpesa\Mpesa();
 
 $callbackData=$mpesa->getDataFromCallback();
 ```
 <br>
 
  ### Finishing a transaction
  After obtaining the Post data from the callbacks, use this at the end of your callback routes to complete the transaction
  
  ```PHP
  $mpesa= new \Safaricom\Mpesa\Mpesa();
  
  $callbackData=$mpesa->finishTransaction();
  ```
<br>

  If validation fails, pass `false` to `finishTransaction()`

  ```PHP
  $mpesa= new \Safaricom\Mpesa\Mpesa();`
  
  $callbackData=$mpesa->finishTransaction(false);
  ```



