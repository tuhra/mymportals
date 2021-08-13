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

function otpsub_createion($customer_id, $service_id, $sub_type) {
    $valid_date = Carbon::now()->addDays(1);
    Subscriber::create([
        'customer_id' => $customer_id,
        'is_active' => 1,
        'is_subscribed' => 0,
        'valid_date' => $valid_date,
        'service_id' => $service_id,
        'sub_type' => $sub_type
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
            'is_active' => 1,
            'valid_date' => $valid_date,
            'is_new_user' => 0
        ]);
}

function otp_renewal ($subscriber_id, $sub_type)
{
    $valid_date = Carbon::now()->addDays(1);
    Subscriber::find($subscriber_id)
        ->update([
            'is_subscribed' => 1,
            'is_active' => 1,
            'valid_date' => $valid_date,
            'is_new_user' => 0,
            'sub_type' => $sub_type
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

function setReqType($req_type) {
    Session::put('req_type', $req_type);   
}

function getReqType() {
    return Session::get('req_type');
}

function curlRequest($url) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return ['req' => $url , 'res' => $response];
}

function otpSend() {
    $otp_tranid = getUUID();
    Session::put('otp_tranid', $otp_tranid);
    $service_type = getServiceType();
    $env = config('app')['env'];
    $url = config('custom')['URL'][$env]. 'GetOtp?';
    $params = 'mobile='.getMsisdn().'&regUser=REGIS_MPT&regPassword=UkVHSVNfT1RQQDU0MzI=&otpMsgLang=2&serviceName='.config('custom')[$service_type]['pName'].'&serviceDesc='.config('custom')[$service_type]['CpPwd'].'&CLI=8934&transId='. $otp_tranid .'&cpId='.config('custom')[$service_type]['CpId'].'&cpPassWord='.config('custom')[$service_type]['CpPwd'].'&email=&requestChannel=PIN';
    $result = curlRequest($url.$params);
    return $result;
}

function otpValidation($otp) {
    $env = config('app')['env'];
    $otp_tranid = Session::get('otp_tranid');
    $service_type = getServiceType();
    $url = config('custom')['URL'][$env]. 'VerifyOtp?';
    $params = 'mobile='.getMsisdn().'&regUser=REGIS_MPT&regPassword=UkVHSVNfT1RQQDU0MzI=&otpMsgLang=2&otp='.$otp.'&serviceName='.config('custom')[$service_type]['pName'].'&serviceDesc='.config('custom')[$service_type]['CpPwd'].'&CLI=8934&transId='. $otp_tranid .'&cpId='.config('custom')[$service_type]['CpId'].'&cpPassWord='.config('custom')[$service_type]['CpPwd'].'&email=&requestChannel=PIN';
    $result = curlRequest($url.$params);
    return $result;
}

function otpRegeneration() {
    $otp_tranid = Session::get('otp_tranid');
    $service_type = getServiceType();
    $env = config('app')['env'];
    $url = config('custom')['URL'][$env] . 'ResendOtp?';
    $params = 'mobile='.getMsisdn().'&regUser=REGIS_MPT&regPassword=UkVHSVNfT1RQQDU0MzI=&otpMsgLang=2&serviceName='.config('custom')[$service_type]['pName'].'&serviceDesc='.config('custom')[$service_type]['CpPwd'].'&CLI=8934&transId='. $otp_tranid .'&cpId='.config('custom')[$service_type]['CpId'].'&cpPassWord='.config('custom')[$service_type]['CpPwd'].'&email=&requestChannel=PIN';
    $result = curlRequest($url.$params);
    return $result;
}

function unsubscribe_process($msisdn, $service_type) {
    $append = '&MSISDN=' . $msisdn . '&transID=' . getUUID();
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

function loadxml($xmlfile) {
    $xml = simplexml_load_string($xmlfile);
    return $xml;
}

function xmlToArray($xml, $options = array()) {
    $defaults = array(
        'namespaceSeparator' => ':',//you may want this to be something other than a colon
        'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
        'alwaysArray' => array(),   //array of xml tag names which should always become arrays
        'autoArray' => true,        //only create arrays for tags which appear more than once
        'textContent' => '$',       //key used for the text content of elements
        'autoText' => true,         //skip textContent key if node has no attributes or child nodes
        'keySearch' => false,       //optional search and replace on tag and attribute names
        'keyReplace' => false       //replace values for above search values (as passed to str_replace())
    );
    $options = array_merge($defaults, $options);
    $namespaces = $xml->getDocNamespaces();
    $namespaces[''] = null; //add base (empty) namespace
 
    //get attributes from all namespaces
    $attributesArray = array();
    foreach ($namespaces as $prefix => $namespace) {
        foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
            //replace characters in attribute name
            if ($options['keySearch']) $attributeName =
                    str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
            $attributeKey = $options['attributePrefix']
                    . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                    . $attributeName;
            $attributesArray[$attributeKey] = (string)$attribute;
        }
    }

 
    //get child nodes from all namespaces
    $tagsArray = array();
    $array = array();
    foreach ($namespaces as $prefix => $namespace) {
        foreach ($xml->children($namespace) as $childXml) {
            //recurse into child nodes
            $childArray = xmlToArray($childXml, $options);
            list($childTagName, $childProperties) = thura($childArray);
 
            //replace characters in tag name
            if ($options['keySearch']) $childTagName =
                    str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
            //add namespace prefix, if any
            if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
 
            if (!isset($tagsArray[$childTagName])) {
                //only entry with this key
                //test if tags of this type should always be arrays, no matter the element count
                $tagsArray[$childTagName] =
                        in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
                        ? array($childProperties) : $childProperties;
            } elseif (
                is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
                === range(0, count($tagsArray[$childTagName]) - 1)
            ) {
                //key already exists and is integer indexed array
                $tagsArray[$childTagName][] = $childProperties;
            } else {
                //key exists so convert to integer indexed array with previous value in position 0
                $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
            }
        }
    }
    
    //get text content of node
    $textContentArray = array();
    $plainText = trim((string)$xml);
    if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
 
    //stick it all together
    $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
            ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
 
    //return node as array
    return array(
        $xml->getName() => $propertiesArray
    );
}

function thura(&$arr) {
    $key = key($arr);
    $result = ($key === null) ? false : [$key, current($arr), 'key' => $key, 'value' => current($arr)];
    next($arr);
    return $result;
}