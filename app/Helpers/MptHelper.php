<?php

namespace App\Helpers;

use App;

class MptHelper 
{
    public function mptHe() {
        $service_type = getServiceType();
        $env = config('app')['env'];
        $url = config('custom')['HE'][$env];
        $CpId = 'CpId='.config('custom')[$service_type]['CpId'];
        $tranid = getUUID();
        $params = 'CpId='.config('custom')[$service_type]['CpId'].'&CpPwd='.config('custom')[$service_type]['CpPwd'].'CpName='.config('custom')[$service_type]['CpName'].'&transID='.$tranid.'&opcoId=1002&productID='.config('custom')[$service_type]['productID'].'&pName='.config('custom')[$service_type]['pName'];
        $key = "3Tob9yt5i9ajBPaw8GkTm8QekCG9sUnW9cn0ndZmulQ=";
        $d  = base64_decode($key);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        $enc = openssl_encrypt($params, "AES-256-CBC", $d , 0, $iv);
        $checksum = hash_hmac("sha256", utf8_encode($params), utf8_encode('TAP'), false); 
        $$checksum = hex2bin($checksum);
        $$checksum = urlencode(base64_encode($checksum));
        $checksum = urlencode(base64_encode(pack('H*',$checksum)));
        $request_url = $url . '?' .$CpId .'&requestParam=' . $enc . '&checksum='.$checksum.'&opcoId=1002';
        \Log::info($request_url);
        return $request_url;
    }
}