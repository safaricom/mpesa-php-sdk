**Introduction**

This package seeks to help php developers implement the various Mpesa APIs without much hustle. It is based on the REST API whose documentation is available on http://developer.safaricom.co.ke.
 
 **Installation using composer**<br>
 `composer require safaricom/mpesa`<br>
 
 
 **Configuration**<br>
 At your project root, create a .env file and in it set the consumer key and consumer secret as follows   
 `consumer_key= [consumer key]` <br>
 `consumer_secret=[consumer secret]`<br>
 For Laravel users, open the Config/App.php file and add `\Safaricom\Mpesa\MpesaServiceProvider::class` under providers and ` 'Mpesa'=> \Safaricom\Mpesa\MpesaServiceProvider::class` under aliases.
  
  _Remember to edit the consumer_key and consumer_secret values appropriately when switching between sandbox and live_

  
 **Usage**
 
 **Confirmation and validation urls**

**B2C Payment Request**
 
 This creates transaction between an M-Pesa short code to a phone number registered on M-Pesa.
 
`$mpesa= new \Safaricom\Mpesa\Mpesa();`

`$b2cTransaction=$mpesa->b2c($live, $InitiatorName, $SecurityCredential, $CommandID, $Amount, $PartyA, $PartyB, $Remarks, $QueueTimeOutURL, $ResultURL, $Occasion);`

_$live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications_


**Account Balance Request**
 
This is used to enquire the balance on an M-Pesa BuyGoods (Till Number)

`$mpesa= new \Safaricom\Mpesa\Mpesa();`

`$balanceInquiry=$mpesa->accountBalance($live,$CommandID, $Initiator, $SecurityCredential, $PartyA, $IdentifierType, $Remarks, $QueueTimeOutURL, $ResultURL);`

_$live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications_


**Transaction Status Request**
This is used to check the status of transaction. 

`$mpesa= new \Safaricom\Mpesa\Mpesa();`

`$trasactionStatus=$mpesa->transactionStatus($live,$Initiator, $SecurityCredential, $CommandID, $TransactionID, $PartyA, $IdentifierType, $ResultURL, $QueueTimeOutURL, $Remarks, $Occasion);`

_$live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications_


**B2B Payment Request**

This is used to transfer funds between two companies.

`$mpesa= new \Safaricom\Mpesa\Mpesa();`

`$b2bTransaction=$mpesa->b2b($live,$ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber );`

_$live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications_


**C2B Payment Request**

This is used to Simulate transfer of funds between a customer and business.


`$mpesa= new \Safaricom\Mpesa\Mpesa();`

`$b2bTransaction=$mpesa->c2b($live, $ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber );`

_$live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications_
_Also important to note is that you should have registered validation and confirmation urls where the callback responses will be sent._



**STK Push Simulation**

This is used to initiate online payment on behalf of a customer.

`$mpesa= new \Safaricom\Mpesa\Mpesa();`

`$stkPushSimulation=$mpesa->STKPushSimulation($live,$BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);`

_$live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications_


**STK Push Status Query**

 This is used to check the status of a Lipa Na M-Pesa Online Payment.
 
`$mpesa= new \Safaricom\Mpesa\Mpesa();`

`$STKPushRequestStatus=$mpesa->STKPushQuery($live,$checkoutRequestID,$businessShortCode,$password,$timestamp);`

_$live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications_



**Callback Routes**
M-Pesa APIs are asynchronous. When a valid M-Pesa API request is received by the API Gateway, it is sent to M-Pesa where it is added to a queue. M-Pesa then processes the requests in the queue and sends a response to the API Gateway which then forwards the response to the URL registered in the CallBackURL or ResultURL request parameter. Whenever M-Pesa receives more requests than the queue can handle, M-Pesa responds by rejecting any more requests and the API Gateway sends a queue timeout response to the URL registered in the QueueTimeOutURL request parameter.

**Obtaining post data from callbacks**
 This is used to get post data from callback in json format. The data can be decoded and stored in a database.
 
 `$mpesa= new \Safaricom\Mpesa\Mpesa();`
 
 `$callbackData=$mpesa->getDataFromCallback();`
  
  **Finishing a transaction**
  After obtaining the Post data from the callbacks, use this at the end of your callback routes to complete the transaction
  
  `$mpesa= new \Safaricom\Mpesa\Mpesa();`
  
  `$callbackData=$mpesa->finishTransaction();`


