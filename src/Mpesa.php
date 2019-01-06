<?php
/**
 * Created by PhpStorm.
 * User: moses
 * Date: 15/10/17
 * Time: 4:59 PM
 */
namespace Safaricom\Mpesa;

include_once("../vendor/autoload.php");

use Symfony\Component\Dotenv\Dotenv;

/**
 * Class Mpesa
 * @package Safaricom\Mpesa
 */
class Mpesa
{

    /** 
    * Define env method similar to laravel's
    *
    * @param String $env_param | Environment Param Name
    * 
    * @return String | Actual Param
    */
    public static function env($env_param){

        $dotenv = new Dotenv();

        $dotenv->load('../.env');

        $env = getenv($env_param);

        return $env;
    }
    /**
     * This is used to generate tokens for the live environment
     * @return mixed
     */
    public static function generateLiveToken(){
        
        try {
            $consumer_key = env("MPESA_CONSUMER_KEY");
            $consumer_secret = env("MPESA_CONSUMER_SECRET");
        } catch (\Throwable $th) {
            $consumer_key = self::env("MPESA_CONSUMER_KEY");
            $consumer_secret = self::env("MPESA_CONSUMER_SECRET");
        }

        if(!isset($consumer_key)||!isset($consumer_secret)){
            die("please declare the consumer key and consumer secret as defined in the documentation");
        }
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $curl_response = curl_exec($curl);

        return json_decode($curl_response)->access_token;


    }


    /**
     * use this function to generate a sandbox token
     * @return mixed
     */
    public static function generateSandBoxToken(){
        
        try {
            $consumer_key = env("MPESA_CONSUMER_KEY");
            $consumer_secret = env("MPESA_CONSUMER_SECRET");
        } catch (\Throwable $th) {
            $consumer_key = self::env("MPESA_CONSUMER_KEY");
            $consumer_secret = self::env("MPESA_CONSUMER_SECRET");
        }

        if(!isset($consumer_key)||!isset($consumer_secret)){
            die("please declare the consumer key and consumer secret as defined in the documentation");
        }
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $curl_response = curl_exec($curl);

        return json_decode($curl_response)->access_token;
    }

    /**
     * Use this function to initiate a reversal request
     * @param $CommandID | Takes only 'TransactionReversal' Command id
     * @param $Initiator | The name of Initiator to initiating  the request
     * @param $SecurityCredential | 	Encrypted Credential of user getting transaction amount
     * @param $TransactionID | Unique Id received with every transaction response.
     * @param $Amount | Amount
     * @param $ReceiverParty | Organization /MSISDN sending the transaction
     * @param $RecieverIdentifierType | Type of organization receiving the transaction
     * @param $ResultURL | The path that stores information of transaction
     * @param $QueueTimeOutURL | The path that stores information of time out transaction
     * @param $Remarks | Comments that are sent along with the transaction.
     * @param $Occasion | 	Optional Parameter
     * @return mixed|string
     */
    public static function reversal($CommandID, $Initiator, $SecurityCredential, $TransactionID, $Amount, $ReceiverParty, $RecieverIdentifierType, $ResultURL, $QueueTimeOutURL, $Remarks, $Occasion){
        
        try {
            $environment = env("MPESA_ENV");
        } catch (\Throwable $th) {
            $environment = self::env("MPESA_ENV");
        }

        if( $environment =="live"){
            $url = 'https://api.safaricom.co.ke/mpesa/reversal/v1/request';
            $token=self::generateLiveToken();
        }elseif ($environment=="sandbox"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';
            $token=self::generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }



        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));


        $curl_post_data = array(
            'CommandID' => $CommandID,
            'Initiator' => $Initiator,
            'SecurityCredential' => $SecurityCredential,
            'TransactionID' => $TransactionID,
            'Amount' => $Amount,
            'ReceiverParty' => $ReceiverParty,
            'RecieverIdentifierType' => $RecieverIdentifierType,
            'ResultURL' => $ResultURL,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'Remarks' => $Remarks,
            'Occasion' => $Occasion
        );

        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response = curl_exec($curl);
        return json_decode($curl_response);

    }

    /**
     * @param $InitiatorName | 	This is the credential/username used to authenticate the transaction request.
     * @param $SecurityCredential | Encrypted password for the initiator to autheticate the transaction request
     * @param $CommandID | Unique command for each transaction type e.g. SalaryPayment, BusinessPayment, PromotionPayment
     * @param $Amount | The amount being transacted
     * @param $PartyA | Organization’s shortcode initiating the transaction.
     * @param $PartyB | Phone number receiving the transaction
     * @param $Remarks | Comments that are sent along with the transaction.
     * @param $QueueTimeOutURL | The timeout end-point that receives a timeout response.
     * @param $ResultURL | The end-point that receives the response of the transaction
     * @param $Occasion | 	Optional
     * @return string
     */
    public static function b2c($InitiatorName, $SecurityCredential, $CommandID, $Amount, $PartyA, $PartyB, $Remarks, $QueueTimeOutURL, $ResultURL, $Occasion){
        
        try {
            $environment = env("MPESA_ENV");
        } catch (\Throwable $th) {
            $environment = self::env("MPESA_ENV");
        }

        if( $environment =="live"){
            $url = 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
            $token=self::generateLiveToken();
        }elseif ($environment=="sandbox"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
            $token=self::generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));


        $curl_post_data = array(
            'InitiatorName' => $InitiatorName,
            'SecurityCredential' => $SecurityCredential,
            'CommandID' => $CommandID ,
            'Amount' => $Amount,
            'PartyA' => $PartyA ,
            'PartyB' => $PartyB,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'ResultURL' => $ResultURL,
            'Occasion' => $Occasion
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);

        return json_encode($curl_response);

    }
    /**
     * Use this function to initiate a C2B transaction
     * @param $ShortCode | 6 digit M-Pesa Till Number or PayBill Number
     * @param $CommandID | Unique command for each transaction type.
     * @param $Amount | The amount been transacted.
     * @param $Msisdn | MSISDN (phone number) sending the transaction, start with country code without the plus(+) sign.
     * @param $BillRefNumber | 	Bill Reference Number (Optional).
     * @return mixed|string
     */
    public  static  function  c2b($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber ){
        
        try {
            $environment = env("MPESA_ENV");
        } catch (\Throwable $th) {
            $environment = self::env("MPESA_ENV");
        }
        
        if( $environment =="live"){
            $url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate';
            $token=self::generateLiveToken();
        }elseif ($environment=="sandbox"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
            $token=self::generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }



        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));

        $curl_post_data = array(
            'ShortCode' => $ShortCode,
            'CommandID' => $CommandID,
            'Amount' => $Amount,
            'Msisdn' => $Msisdn,
            'BillRefNumber' => $BillRefNumber
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response = curl_exec($curl);
        return $curl_response;

    }


    /**
     * Use this to initiate a balance inquiry request
     * @param $CommandID | A unique command passed to the M-Pesa system.
     * @param $Initiator | 	This is the credential/username used to authenticate the transaction request.
     * @param $SecurityCredential | Encrypted password for the initiator to autheticate the transaction request
     * @param $PartyA | Type of organization receiving the transaction
     * @param $IdentifierType |Type of organization receiving the transaction
     * @param $Remarks | Comments that are sent along with the transaction.
     * @param $QueueTimeOutURL | The path that stores information of time out transaction
     * @param $ResultURL | 	The path that stores information of transaction
     * @return mixed|string
     */
    public static function accountBalance($CommandID, $Initiator, $SecurityCredential, $PartyA, $IdentifierType, $Remarks, $QueueTimeOutURL, $ResultURL){
        
        try {
            $environment = env("MPESA_ENV");
        } catch (\Throwable $th) {
            $environment = self::env("MPESA_ENV");
        }

        if( $environment =="live"){
            $url = 'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query';
            $token=self::generateLiveToken();
        }elseif ($environment=="sandbox"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/accountbalance/v1/query';
            $token=self::generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token)); //setting custom header


        $curl_post_data = array(
            'CommandID' => $CommandID,
            'Initiator' => $Initiator,
            'SecurityCredential' => $SecurityCredential,
            'PartyA' => $PartyA,
            'IdentifierType' => $IdentifierType,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'ResultURL' => $ResultURL
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response = curl_exec($curl);
        return $curl_response;
    }

    /**
     * Use this function to make a transaction status request
     * @param $Initiator | The name of Initiator to initiating the request.
     * @param $SecurityCredential | 	Encrypted password for the initiator to autheticate the transaction request.
     * @param $CommandID | Unique command for each transaction type, possible values are: TransactionStatusQuery.
     * @param $TransactionID | Organization Receiving the funds.
     * @param $PartyA | Organization/MSISDN sending the transaction
     * @param $IdentifierType | Type of organization receiving the transaction
     * @param $ResultURL | The path that stores information of transaction
     * @param $QueueTimeOutURL | The path that stores information of time out transaction
     * @param $Remarks | 	Comments that are sent along with the transaction
     * @param $Occasion | 	Optional Parameter
     * @return mixed|string
     */
    public function transactionStatus($Initiator, $SecurityCredential, $CommandID, $TransactionID, $PartyA, $IdentifierType, $ResultURL, $QueueTimeOutURL, $Remarks, $Occasion){
        
        try {
            $environment = env("MPESA_ENV");
        } catch (\Throwable $th) {
            $environment = self::env("MPESA_ENV");
        }
        
        if( $environment =="live"){
            $url = 'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query';
            $token=self::generateLiveToken();
        }elseif ($environment=="sandbox"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query';
            $token=self::generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token)); //setting custom header


        $curl_post_data = array(
            'Initiator' => $Initiator,
            'SecurityCredential' => $SecurityCredential,
            'CommandID' => $CommandID,
            'TransactionID' => $TransactionID,
            'PartyA' => $PartyA,
            'IdentifierType' => $IdentifierType,
            'ResultURL' => $ResultURL,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'Remarks' => $Remarks,
            'Occasion' => $Occasion
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response = curl_exec($curl);


        return $curl_response;
    }


    /**
     * Use this function to initiate a B2B request
     * @param $Initiator | This is the credential/username used to authenticate the transaction request.
     * @param $SecurityCredential | Encrypted password for the initiator to autheticate the transaction request.
     * @param $Amount | Base64 encoded string of the B2B short code and password, which is encrypted using M-Pesa public key and validates the transaction on M-Pesa Core system.
     * @param $PartyA | Organization’s short code initiating the transaction.
     * @param $PartyB | Organization’s short code receiving the funds being transacted.
     * @param $Remarks | Comments that are sent along with the transaction.
     * @param $QueueTimeOutURL | The path that stores information of time out transactions.it should be properly validated to make sure that it contains the port, URI and domain name or publicly available IP.
     * @param $ResultURL | The path that receives results from M-Pesa it should be properly validated to make sure that it contains the port, URI and domain name or publicly available IP.
     * @param $AccountReference | Account Reference mandatory for “BusinessPaybill” CommandID.
     * @param $commandID | Unique command for each transaction type, possible values are: BusinessPayBill, MerchantToMerchantTransfer, MerchantTransferFromMerchantToWorking, MerchantServicesMMFAccountTransfer, AgencyFloatAdvance
     * @param $SenderIdentifierType | Type of organization sending the transaction.
     * @param $RecieverIdentifierType | Type of organization receiving the funds being transacted.

     * @return mixed|string
     */
    public function b2b($Initiator, $SecurityCredential, $Amount, $PartyA, $PartyB, $Remarks, $QueueTimeOutURL, $ResultURL, $AccountReference, $commandID, $SenderIdentifierType, $RecieverIdentifierType){
        
        try {
            $environment = env("MPESA_ENV");
        } catch (\Throwable $th) {
            $environment = self::env("MPESA_ENV");
        }
        
        if( $environment =="live"){
            $url = 'https://api.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
            $token=self::generateLiveToken();
        }elseif ($environment=="sandbox"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
            $token=self::generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token)); //setting custom header
        $curl_post_data = array(
            'Initiator' => $Initiator,
            'SecurityCredential' => $SecurityCredential,
            'CommandID' => $commandID,
            'SenderIdentifierType' => $SenderIdentifierType,
            'RecieverIdentifierType' => $RecieverIdentifierType,
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $PartyB,
            'AccountReference' => $AccountReference,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'ResultURL' => $ResultURL
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        return $curl_response;

    }

    /**
     * Use this function to initiate an STKPush Simulation
     * @param $BusinessShortCode | The organization shortcode used to receive the transaction.
     * @param $LipaNaMpesaPasskey | The password for encrypting the request. This is generated by base64 encoding BusinessShortcode, Passkey and Timestamp.
     * @param $TransactionType | The transaction type to be used for this request. Only CustomerPayBillOnline is supported.
     * @param $Amount | The amount to be transacted.
     * @param $PartyA | The MSISDN sending the funds.
     * @param $PartyB | The organization shortcode receiving the funds
     * @param $PhoneNumber | The MSISDN sending the funds.
     * @param $CallBackURL | The url to where responses from M-Pesa will be sent to.
     * @param $AccountReference | Used with M-Pesa PayBills.
     * @param $TransactionDesc | A description of the transaction.
     * @param $Remark | Remarks
     * @return mixed|string
     */
    public function STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remark){
        
        try {
            $environment = env("MPESA_ENV");
        } catch (\Throwable $th) {
            $environment = self::env("MPESA_ENV");
        }
        
        if( $environment =="live"){
            $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $token = self::generateLiveToken();
        }elseif ($environment=="sandbox"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $token = self::generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }



        $timestamp='20'.date(    "ymdhis");
        $password=base64_encode($BusinessShortCode.$LipaNaMpesaPasskey.$timestamp);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));


        $curl_post_data = array(
            'BusinessShortCode' => $BusinessShortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => $TransactionType,
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $PartyB,
            'PhoneNumber' => $PhoneNumber,
            'CallBackURL' => $CallBackURL,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionType
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response=curl_exec($curl);
        return $curl_response;


    }


    /**
     * Use this function to initiate an STKPush Status Query request.
     * @param $checkoutRequestID | Checkout RequestID
     * @param $businessShortCode | Business Short Code
     * @param $password | Password
     * @param $timestamp | Timestamp
     * @return mixed|string
     */
    public static function STKPushQuery($environment, $checkoutRequestID, $businessShortCode, $password, $timestamp){
        
        try {
            $environment = env("MPESA_ENV");
        } catch (\Throwable $th) {
            $environment = self::env("MPESA_ENV");
        }
        
        if( $environment =="live"){
            $url = 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query';
            $token=self::generateLiveToken();
        }elseif ($environment=="sandbox"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
            $token=self::generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));


        $curl_post_data = array(
            'BusinessShortCode' => $businessShortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestID
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);

        $curl_response = curl_exec($curl);

        return $curl_response;
    }

    /**
     *Use this function to confirm all transactions in callback routes
     */
    public function finishTransaction($status = true)
    {
        if ($status === true) {
            $resultArray=[
                "ResultDesc"=>"Confirmation Service request accepted successfully",
                "ResultCode"=>"0"
            ];
        } else {
            $resultArray=[
                "ResultDesc"=>"Confirmation Service not accepted",
                "ResultCode"=>"1"
            ];
        }

        header('Content-Type: application/json');

        echo json_encode($resultArray);
    }


    /**
     *Use this function to get callback data posted in callback routes
     */
    public function getDataFromCallback(){
        $callbackJSONData=file_get_contents('php://input');
        return $callbackJSONData;
    }


}
