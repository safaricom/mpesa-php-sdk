<?php
/**
 * Created by PhpStorm.
 * User: MNANDWA
 * Date: 10/19/2017
 * Time: 4:23 PM
 */

namespace safaricom\Mpesa;


/**
 * Class TransactionCallbacks
 * This class contains functions that will be used to obtain data from Mpesa callbacks
 * @package safaricom\Mpesa
 */
class TransactionCallbacks
{
    /**
     * Use this function to process the B2B request callback
     * @return string
     */
    public static function processB2BRequestCallback(){
        $callbackJSONData=file_get_contents('php://input');
        $callbackData=json_decode($callbackJSONData);
        $resultCode=$callbackData->Result->ResultCode;
        $resultDesc=$callbackData->Result->ResultDesc;
        $originatorConversationID=$callbackData->Result->OriginatorConversationID;
        $conversationID=$callbackData->Result->ConversationID;
        $transactionID=$callbackData->Result->TransactionID;
        $transactionReceipt=$callbackData->Result->ResultParameters->ResultParameter[0]->Value;
        $transactionAmount=$callbackData->Result->ResultParameters->ResultParameter[1]->Value;
        $b2CWorkingAccountAvailableFunds=$callbackData->Result->ResultParameters->ResultParameter[2]->Value;
        $b2CUtilityAccountAvailableFunds=$callbackData->Result->ResultParameters->ResultParameter[3]->Value;
        $transactionCompletedDateTime=$callbackData->Result->ResultParameters->ResultParameter[4]->Value;
        $receiverPartyPublicName=$callbackData->Result->ResultParameters->ResultParameter[5]->Value;
        $B2CChargesPaidAccountAvailableFunds=$callbackData->Result->ResultParameters->ResultParameter[6]->Value;
        $B2CRecipientIsRegisteredCustomer=$callbackData->Result->ResultParameters->ResultParameter[7]->Value;


        $result=[
          "resultCode"=>$resultCode,
          "resultDesc"=>$resultDesc,
            "originatorConversationID"=>$originatorConversationID,
            "conversationID"=>$conversationID,
            "transactionID"=>$transactionID,
            "transactionReceipt"=>$transactionReceipt,
            "transactionAmount"=>$transactionAmount,
            "b2CWorkingAccountAvailableFunds"=>$b2CWorkingAccountAvailableFunds,
            "b2CUtilityAccountAvailableFunds"=>$b2CUtilityAccountAvailableFunds,
            "transactionCompletedDateTime"=>$transactionCompletedDateTime,
            "receiverPartyPublicName"=>$receiverPartyPublicName,
            "B2CChargesPaidAccountAvailableFunds"=>$B2CChargesPaidAccountAvailableFunds,
            "B2CRecipientIsRegisteredCustomer"=>$B2CRecipientIsRegisteredCustomer
        ];

        return json_encode($result);
    }

    /**
     * Use this function to process the B2C request callback
     * @return string
     */
    public static function processB2CRequestCallback(){
    $callbackJSONData=file_get_contents('php://input');
    $callbackData=json_decode($callbackJSONData);
    $resultCode=$callbackData->Result->ResultCode;
    $resultDesc=$callbackData->Result->ResultDesc;
    $originatorConversationID=$callbackData->Result->OriginatorConversationID;
    $conversationID=$callbackData->Result->ConversationID;
    $transactionID=$callbackData->Result->TransactionID;
    $initiatorAccountCurrentBalance=$callbackData->Result->ResultParameters->ResultParameter[0]->Value;
    $debitAccountCurrentBalance=$callbackData->Result->ResultParameters->ResultParameter[1]->Value;
    $amount=$callbackData->Result->ResultParameters->ResultParameter[2]->Value;
    $debitPartyAffectedAccountBalance=$callbackData->Result->ResultParameters->ResultParameter[3]->Value;
    $transCompletedTime=$callbackData->Result->ResultParameters->ResultParameter[4]->Value;
    $debitPartyCharges=$callbackData->Result->ResultParameters->ResultParameter[5]->Value;
    $receiverPartyPublicName=$callbackData->Result->ResultParameters->ResultParameter[6]->Value;
    $currency=$callbackData->Result->ResultParameters->ResultParameter[7]->Value;

    $result=[
        "resultCode"=>$resultCode,
        "resultDesc"=>$resultDesc,
        "originatorConversationID"=>$originatorConversationID,
        "conversationID"=>$conversationID,
        "transactionID"=>$transactionID,
        "initiatorAccountCurrentBalance"=>$initiatorAccountCurrentBalance,
        "debitAccountCurrentBalance"=>$debitAccountCurrentBalance,
        "amount"=>$amount,
        "debitPartyAffectedAccountBalance"=>$debitPartyAffectedAccountBalance,
        "transCompletedTime"=>$transCompletedTime,
        "debitPartyCharges"=>$debitPartyCharges,
        "receiverPartyPublicName"=>$receiverPartyPublicName,
        "currency"=>$currency
    ];


        return json_encode($result);
    }

    /**
     * Use this function to process the C2B Validation request callback
     * @return string
     */
    public static function processC2BRequestValidation(){
        $callbackJSONData=file_get_contents('php://input');
        $callbackData=json_decode($callbackJSONData);
        $transactionType=$callbackData->TransactionType;
        $transID=$callbackData->TransID;
        $transTime=$callbackData->TransTime;
        $transAmount=$callbackData->TransAmount;
        $businessShortCode=$callbackData->BusinessShortCode;
        $billRefNumber=$callbackData->BillRefNumber;
        $invoiceNumber=$callbackData->InvoiceNumber;
        $orgAccountBalance=$callbackData->OrgAccountBalance;
        $thirdPartyTransID=$callbackData->ThirdPartyTransID;
        $MSISDN=$callbackData->MSISDN;
        $firstName=$callbackData->FirstName;
        $middleName=$callbackData->MiddleName;
        $lastName=$callbackData->LastName;

        $result=[
            $transTime=>$transTime,
            $transAmount=>$transAmount,
            $businessShortCode=>$businessShortCode,
            $billRefNumber=>$billRefNumber,
            $invoiceNumber=>$invoiceNumber,
            $orgAccountBalance=>$orgAccountBalance,
            $thirdPartyTransID=>$thirdPartyTransID,
            $MSISDN=>$MSISDN,
            $firstName=>$firstName,
            $lastName=>$lastName,
            $middleName=>$middleName,
            $transID=>$transID,
            $transactionType=>$transactionType

        ];

        return json_encode($result);

    }

    /**
     * Use this function to process the C2B Confirmation result callback
     * @return string
     */
    public static function processC2BRequestConfirmation(){
        $callbackJSONData=file_get_contents('php://input');
        $callbackData=json_decode($callbackJSONData);
        $transactionType=$callbackData->TransactionType;
        $transID=$callbackData->TransID;
        $transTime=$callbackData->TransTime;
        $transAmount=$callbackData->TransAmount;
        $businessShortCode=$callbackData->BusinessShortCode;
        $billRefNumber=$callbackData->BillRefNumber;
        $invoiceNumber=$callbackData->InvoiceNumber;
        $orgAccountBalance=$callbackData->OrgAccountBalance;
        $thirdPartyTransID=$callbackData->ThirdPartyTransID;
        $MSISDN=$callbackData->MSISDN;
        $firstName=$callbackData->FirstName;
        $middleName=$callbackData->MiddleName;
        $lastName=$callbackData->LastName;

        $result=[
            $transTime=>$transTime,
            $transAmount=>$transAmount,
            $businessShortCode=>$businessShortCode,
            $billRefNumber=>$billRefNumber,
            $invoiceNumber=>$invoiceNumber,
            $orgAccountBalance=>$orgAccountBalance,
            $thirdPartyTransID=>$thirdPartyTransID,
            $MSISDN=>$MSISDN,
            $firstName=>$firstName,
            $lastName=>$lastName,
            $middleName=>$middleName,
            $transID=>$transID,
            $transactionType=>$transactionType

        ];

        return json_encode($result);
    }

    /**
     * Use this function to process the Account Balance request callback
     * @return string
     */
    public static function processAccountBalanceRequestCallback(){
        $callbackJSONData=file_get_contents('php://input');
        $callbackData=json_decode($callbackJSONData);
        $resultType=$callbackData->Result->ResultType;
        $resultCode=$callbackData->Result->ResultCode;
        $resultDesc=$callbackData->Result->ResultDesc;
        $originatorConversationID=$callbackData->Result->OriginatorConversationID;
        $conversationID=$callbackData->Result->ConversationID;
        $transactionID=$callbackData->Result->TransactionID;
        $accountBalance=$callbackData->Result->ResultParameters->ResultParameter[0]->Value;
        $BOCompletedTime=$callbackData->Result->ResultParameters->ResultParameter[1]->Value;

        $result=[
          "resultDesc"=>$resultDesc,
          "resultCode"=>$resultCode,
          "originatorConversationID"=>$originatorConversationID,
          "conversationID"=>$conversationID,
          "transactionID"=>$transactionID,
          "accountBalance"=>$accountBalance,
          "BOCompletedTime"=>$BOCompletedTime,
          "resultType"=>$resultType
        ];

        return json_encode($result);


    }

    /**
     * Use this function to process the Reversal request callback
     * @return string
     */
    public static function processReversalRequestCallBack(){
        $callbackJSONData=file_get_contents('php://input');
        $callbackData=json_decode($callbackJSONData);
        $resultType=$callbackData->Result->ResultType;
        $resultCode=$callbackData->Result->ResultCode;
        $resultDesc=$callbackData->Result->ResultDesc;
        $originatorConversationID=$callbackData->Result->OriginatorConversationID;
        $conversationID=$callbackData->Result->ConversationID;
        $transactionID=$callbackData->Result->TransactionID;

        $result=[
          "resultType"=>$resultType,
          "resultCode"=>$resultCode,
          "resultDesc"=>$resultDesc,
          "conversationID"=>$conversationID,
          "transactionID"=>$transactionID,
          "originatorConversationID"=>$originatorConversationID
        ];

        return json_encode($result);

    }

    /**
     * Use this function to process the STK push request callback
     * @return string
     */
    public static function processSTKPushRequestCallback(){
        $callbackJSONData=file_get_contents('php://input');
        $callbackData=json_decode($callbackJSONData);
        $resultCode=$callbackData->Body->stkCallback->ResultCode;
        $resultDesc=$callbackData->Body->stkCallback->ResultDesc;
        $merchantRequestID=$callbackData->Body->stkCallback->MerchantRequestID;
        $checkoutRequestID=$callbackData->Body->stkCallback->CheckoutRequestID;

        $amount=$callbackData->stkCallback->Body->CallbackMetadata->Item[0]->Value;
        $mpesaReceiptNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;
        $balance=$callbackData->stkCallback->Body->CallbackMetadata->Item[2]->Value;
        $b2CUtilityAccountAvailableFunds=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
        $transactionDate=$callbackData->Body->stkCallback->CallbackMetadata->Item[4]->Value;
        $phoneNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[5]->Value;

        $result=[
            "resultDesc"=>$resultDesc,
            "resultCode"=>$resultCode,
            "merchantRequestID"=>$merchantRequestID,
            "checkoutRequestID"=>$checkoutRequestID,
            "amount"=>$amount,
            "mpesaReceiptNumber"=>$mpesaReceiptNumber,
            "balance"=>$balance,
            "b2CUtilityAccountAvailableFunds"=>$b2CUtilityAccountAvailableFunds,
            "transactionDate"=>$transactionDate,
            "phoneNumber"=>$phoneNumber
        ];

        return json_encode($result);
    }

    /**
     * Use this function to process the STK Push  request callback
     * @return string
     */
    public static function processSTKPushQueryRequestCallback(){
        $callbackJSONData=file_get_contents('php://input');
        $callbackData=json_decode($callbackJSONData);
        $responseCode=$callbackData->ResponseCode;
        $responseDescription=$callbackData->ResponseDescription;
        $merchantRequestID=$callbackData->MerchantRequestID;
        $checkoutRequestID=$callbackData->CheckoutRequestID;
        $resultCode=$callbackData->ResultCode;
        $resultDesc=$callbackData->ResultDesc;

        $result=[
            "resultCode"=>$resultCode,
            "responseDescription"=>$responseDescription,
            "responseCode"=>$responseCode,
            "merchantRequestID"=>$merchantRequestID,
            "checkoutRequestID"=>$checkoutRequestID,
            "resultDesc"=>$resultDesc
        ];

        return json_encode($result);
    }

    /**
     * Use this function to process the Transaction status request callback
     * @return string
     */
    public static function processTransactionStatusRequestCallback(){
        $callbackJSONData=file_get_contents('php://input');
        $callbackData=json_decode($callbackJSONData);
        $resultCode=$callbackData->Result->ResultCode;
        $resultDesc=$callbackData->Result->ResultDesc;
        $originatorConversationID=$callbackData->Result->OriginatorConversationID;
        $conversationID=$callbackData->Result->ConversationID;
        $transactionID=$callbackData->Result->TransactionID;
        $ReceiptNo=$callbackData->Result->ResultParameters->ResultParameter[0]->Value;
        $ConversationID=$callbackData->Result->ResultParameters->ResultParameter[1]->Value;
        $FinalisedTime=$callbackData->Result->ResultParameters->ResultParameter[2]->Value;
        $Amount=$callbackData->Result->ResultParameters->ResultParameter[3]->Value;
        $TransactionStatus=$callbackData->Result->ResultParameters->ResultParameter[4]->Value;
        $ReasonType=$callbackData->Result->ResultParameters->ResultParameter[5]->Value;
        $TransactionReason=$callbackData->Result->ResultParameters->ResultParameter[6]->Value;
        $DebitPartyCharges=$callbackData->Result->ResultParameters->ResultParameter[7]->Value;
        $DebitAccountType=$callbackData->Result->ResultParameters->ResultParameter[8]->Value;
        $InitiatedTime=$callbackData->Result->ResultParameters->ResultParameter[9]->Value;
        $OriginatorConversationID=$callbackData->Result->ResultParameters->ResultParameter[10]->Value;
        $CreditPartyName=$callbackData->Result->ResultParameters->ResultParameter[11]->Value;
        $DebitPartyName=$callbackData->Result->ResultParameters->ResultParameter[12]->Value;

        $result=[
            "resultCode"=>$resultCode,
            "resultDesc"=>$resultDesc,
            "originatorConversationID"=>$originatorConversationID,
            "conversationID"=>$conversationID,
            "transactionID"=>$transactionID,
            "ReceiptNo"=>$ReceiptNo,
            "ConversationID"=>$ConversationID,
            "FinalisedTime"=>$FinalisedTime,
            "Amount"=>$Amount,
            "TransactionStatus"=>$TransactionStatus,
            "ReasonType"=>$ReasonType,
            "TransactionReason"=>$TransactionReason,
            "DebitPartyCharges"=>$DebitPartyCharges,
            "DebitAccountType"=>$DebitAccountType,
            "InitiatedTime"=>$InitiatedTime,
            "OriginatorConversationID"=>$OriginatorConversationID,
            "CreditPartyName"=>$CreditPartyName,
            "DebitPartyName"=>$DebitPartyName
        ];

        return json_encode($result);
    }

}
