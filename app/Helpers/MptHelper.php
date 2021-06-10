<?php

namespace App\Helpers;

use App;

class MptHelper 
{
    public function mptHe() {
        $url = 'http://marpt.mpt.com.mm/SingleSiteHE/getHE';
        $CpId = 'CpId=TAP';
        $tranid = getUUID();
        $params = 'CpId=TAP&CpPwd=tap@123&CpName=TAP&transID='.$tranid.'&opcoId=1002&productID=10500&pName=Taptube';
        $key = "3Tob9yt5i9ajBPaw8GkTm8QekCG9sUnW9cn0ndZmulQ=";
        $d  = base64_decode($key);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        $enc = openssl_encrypt($params, "AES-256-CBC", $d , 0, $iv);
        $checksum = hash_hmac("sha256", utf8_encode($params), utf8_encode('TAP'), false); 
        $$checksum = hex2bin($checksum);
        $$checksum = urlencode(base64_encode($checksum));
        $checksum = urlencode(base64_encode(pack('H*',$checksum)));
        $request_url = $url . '?' .$CpId .'&requestParam=' . $enc . '&checksum='.$checksum.'&opcoId=1002';
        return $request_url;
    }
}