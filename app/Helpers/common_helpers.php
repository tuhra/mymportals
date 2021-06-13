<?php

use Propaganistas\LaravelPhone\PhoneNumber;
use Carbon\Carbon;
use App\Model\Subscriber;
use App\Model\SubscriberLog;
use App\Model\MptCallbackLog;

/***  For WEB  ***/
function subscriber_creation ($customer_id, $service_id)
{
    $valid_date = Carbon::now()->addDays(1);
    Subscriber::create([
        'customer_id' => $customer_id,
        'is_active' => 1,
        'is_subscribed' => 1,
        'valid_date' => $valid_date,
        'service_id' => $service_id
    ]);
}

function unsubscribe($subscriber_id)
{
    $row = Subscriber::find($subscriber_id)
        ->update([
            'is_active' => 0,
            'is_subscribed' => 0,
            'is_not_enough' => 0,
            'is_new_user' => 0
        ]);
}

function subscriber_log ($customer_id, $event, $service_id) 
{
    SubscriberLog::create([
        'customer_id' => $customer_id,
        'event' => $event,
        'service_id' => $service_id
    ]);
}

function renewal ($subscriber_id)
{
    $valid_date = Carbon::now()->addDays(1);
    Subscriber::find($subscriber_id)
        ->update([
            'is_subscribed' => 1,
            'valid_date' => $valid_date,
            'is_new_user' => 0
        ]);
}

function check_callback ($customer_id, $tranid)
{
    $callback_log = MptCallbackLog::where('customer_id', $customer_id)->where('tranid', $tranid)->first();
    return $callback_log;
}

function getUUID()
{
    return rand(100,999).time().rand(100,999);
}

function checkoperator($msisdn) {
    $carrierMapper = \libphonenumber\PhoneNumberToCarrierMapper::getInstance();
    $chNumber = \libphonenumber\PhoneNumberUtil::getInstance()->parse($msisdn, null);
    $operator_name=$carrierMapper->getNameForNumber($chNumber, 'en');
    return $operator_name;
}

function setMsisdn($msisdn) {
	Session::put('msisdn', $msisdn);
}

function getMsisdn() {
	return Session::get('msisdn');
}

function setTranid($tranid) {
	Session::put('tranid', $tranid);
}

function getTranid() {
	return Session::get('tranid');
}

function setServiceType($signature) {
    Session::put('service_type', $signature);
}

function getServiceType() {
    return Session::get('service_type');
}

function setServiceId($service_id) {
    Session::put('service_id', $service_id);
}

function getServiceId() {
    return Session::get('service_id');
}

function curlRequest($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return ['req' => $url , 'res' => $result];
}

function otpSend() {
    $otp_tranid = getUUID();
    Session::put('otp_tranid', $otp_tranid);
    $service_type = getServiceType();
    $env = config('app')['env'];
    $url = config('custom')['URL'][$env]. 'GetOtp?';
    $params = 'mobile='.getMsisdn().'&regUser=REGIS_MPT&regPassword=UkVHSVNfT1RQQDU0MzI=&otpMsgLang=2&serviceName='.config('custom')[$service_type]['pName'].'&serviceDesc='.config('custom')[$service_type]['CpPwd'].'&CLI=8934&transId='. $otp_tranid .'&cpId='.config('custom')[$service_type]['CpId'].'&cpPassWord='.config('custom')[$service_type]['CpPwd'].'&email=&requestChannel=PIN';
    $result = curlRequest($url.$params);
    \Log::info($result);
    return $result;
}

function otpValidation($otp) {
    $env = config('app')['env'];
    $otp_tranid = Session::get('otp_tranid');
    $service_type = getServiceType();
    $url = config('custom')['URL'][$env]. 'VerifyOtp';
    $params = 'mobile='.getMsisdn().'&regUser=REGIS_MPT&regPassword=UkVHSVNfT1RQQDU0MzI=&otpMsgLang=2&otp='.$otp.'&serviceName='.config('custom')[$service_type]['pName'].'&serviceDesc='.config('custom')[$service_type]['CpPwd'].'&CLI=8934&transId='. $otp_tranid .'&cpId='.config('custom')[$service_type]['CpId'].'&cpPassWord='.config('custom')[$service_type]['CpPwd'].'&email=&requestChannel=PIN';
    $result = curlRequest($url.$params);
    return $result;
}

function otpRegeneration() {
    $otp_tranid = Session::get('otp_tranid');
    $service_type = getServiceType();
    $env = config('app')['env'];
    $url = config('custom')['URL'][$env] . 'ResendOtp';
    $params = 'mobile='.getMsisdn().'&regUser=REGIS_MPT&regPassword=UkVHSVNfT1RQQDU0MzI=&otpMsgLang=2&serviceName='.config('custom')[$service_type]['pName'].'&serviceDesc='.config('custom')[$service_type]['CpPwd'].'&CLI=8934&transId='. $otp_tranid .'&cpId='.config('custom')[$service_type]['CpId'].'&cpPassWord='.config('custom')[$service_type]['CpPwd'].'&email=&requestChannel=PIN';
    $result = curlRequest($url.$params);
    return $result;
}

function unsubscribe_process($msisdn, $tranid) {
    $service_type = getServiceType();
    $append = '&MSISDN=' . $msisdn . '&transID=' . $tranid;
    $env = config('app')['env'];
    $endpoint = config('custom')['URL'][$env];
    $query = build_http_query(config('custom')[$service_type]) . $append;
    $url = $endpoint . 'CGUnsubscribe?' . $query;
    $result = curlRequest($url);
    return $result;
}

/**
* Builds an http query string.
* @param array $query  // of key value pairs to be used in the query
* @return string       // http query string.
**/
function build_http_query( $query ){
    $query_array = array();
    foreach( $query as $key => $key_value ){

        $query_array[] = urlencode( $key ) . '=' . urlencode( $key_value );
    }
    return implode( '&', $query_array );
}