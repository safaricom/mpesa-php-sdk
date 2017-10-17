<?php
/**
 * Created by PhpStorm.
 * User: moses
 * Date: 15/10/17
 * Time: 4:59 PM
 */
namespace Safaricom\Mpesa;


/**
 * Class Mpesa
 * @package Safaricom\Mpesa
 */
class Mpesa
{
    /**
     * @return mixed
     */
    public static function generateLiveToken(){
        $consumer_key=env("consumer_key");
        $consumer_secret=env("consumer_secret");
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


        $curl_response = curl_exec($curl);


        return json_decode($curl_response)->access_token;
    }


    /**
     * @return mixed
     */
    public static function generateSandBoxToken(){
        $consumer_key=env("consumer_key");
        $consumer_secret=env("consumer_secret");
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


        $curl_response = curl_exec($curl);


        return json_decode($curl_response)->access_token;
    }

    /**
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $CommandID
     * @param $Initiator
     * @param $SecurityCredential
     * @param $TransactionID
     * @param $Amount
     * @param $ReceiverParty
     * @param $RecieverIdentifierType
     * @param $ResultURL
     * @param $QueueTimeOutURL
     * @param $Remarks
     * @param $Occasion
     * @return mixed|string
     */
    public static function reversal($live, $CommandID, $Initiator, $SecurityCredential, $TransactionID, $Amount, $ReceiverParty, $RecieverIdentifierType, $ResultURL, $QueueTimeOutURL, $Remarks, $Occasion){
        if( $live =="true"){
            $url = 'https://api.safaricom.co.ke/mpesa/reversal/v1/request';
            $token=self::generateLiveToken();
        }elseif ($live=="false"){
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
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $ShortCode
     * @param $CommandID
     * @param $Amount
     * @param $Msisdn
     * @param $BillRefNumber
     * @return mixed|string
     */
    public  static  function  b2c($live, $ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber ){
        if( $live =="true"){
            $url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate';
            $token=self::generateLiveToken();
        }elseif ($live=="false"){
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
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $CommandID
     * @param $Initiator
     * @param $SecurityCredential
     * @param $PartyA
     * @param $IdentifierType
     * @param $Remarks
     * @param $QueueTimeOutURL
     * @param $ResultURL
     * @return mixed|string
     */
    public static function accountBalance($live, $CommandID, $Initiator, $SecurityCredential, $PartyA, $IdentifierType, $Remarks, $QueueTimeOutURL, $ResultURL){
        if( $live =="true"){
            $url = 'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query';
            $token=self::generateLiveToken();
        }elseif ($live=="false"){
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
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $Initiator
     * @param $SecurityCredential
     * @param $CommandID
     * @param $TransactionID
     * @param $PartyA
     * @param $IdentifierType
     * @param $ResultURL
     * @param $QueueTimeOutURL
     * @param $Remarks
     * @param $Occasion
     * @return mixed|string
     */
    public function transactionStatus($live , $Initiator, $SecurityCredential, $CommandID, $TransactionID, $PartyA, $IdentifierType, $ResultURL, $QueueTimeOutURL, $Remarks, $Occasion){
        if( $live =="true"){
            $url = 'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query';
            $token=self::generateLiveToken();
        }elseif ($live=="false"){
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
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $Initiator
     * @param $SecurityCredential
     * @param $Amount
     * @param $PartyA
     * @param $PartyB
     * @param $Remarks
     * @param $QueueTimeOutURL
     * @param $ResultURL
     * @param $AccountReference
     * @param $commandID
     * @param $SenderIdentifierType
     * @param $RecieverIdentifierType
     * @return mixed|string
     */
    public function b2b($live , $Initiator, $SecurityCredential, $Amount, $PartyA, $PartyB, $Remarks, $QueueTimeOutURL, $ResultURL, $AccountReference, $commandID, $SenderIdentifierType, $RecieverIdentifierType){
        if( $live =="true"){
            $url = 'https://api.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
            $token=self::generateLiveToken();
        }elseif ($live=="false"){
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
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $BusinessShortCode
     * @param $LipaNaMpesaPasskey
     * @param $TransactionType
     * @param $Amount
     * @param $PartyA
     * @param $PartyB
     * @param $PhoneNumber
     * @param $CallBackURL
     * @param $AccountReference
     * @param $TransactionDesc
     * @param $Remark
     * @return mixed|string
     */
    public function STKPushSimulation($live , $BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remark){
        if( $live =="true"){
            $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $token=self::generateLiveToken();
        }elseif ($live=="false"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $token=self::generateSandBoxToken();
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
            'TransactionDesc' => $TransactionType,
            'Remark'=> $Remark
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
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $checkoutRequestID
     * @param $businessShortCode
     * @param $password
     * @param $timestamp
     * @return mixed|string
     */
    public static function STKPushQuery($live, $checkoutRequestID, $businessShortCode, $password, $timestamp){
        if( $live =="true"){
            $url = 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query';
            $token=self::generateLiveToken();
        }elseif ($live=="false"){
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
     *
     */
    public function confirm(){
        $resultArray=[
            "ResultDesc"=>"Confirmation Service request accepted successfully",
            "ResultCode"=>"0"
        ];
        header('Content-Type: application/json');

        echo json_encode($resultArray);
    }


    /**
     *
     */
    public function validate(){
        $resultArray=[
            "ResultDesc"=>"Confirmation Service request accepted successfully",
            "ResultCode"=>"0"
        ];

        header('Content-Type: application/json');

        echo json_encode($resultArray);
    }

    /**
     *
     */
    public function decline(){
        $resultArray=[
            "ResultDesc"=>"Confirmation Service request declined",
            "ResultCode"=>"1"
        ];

        header('Content-Type: application/json');

        echo json_encode($resultArray);
    }

}
