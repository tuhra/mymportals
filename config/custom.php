<?php

return [
    'URL' => [
        'STAGING' => 'http://matestcnt.mpt.com.mm/API/',
        'PRODUCTION' => 'http://macnt.mpt.com.mm/API/'
    ],

    'HE' => [
        'STAGING' => 'http://matestrpt.mpt.com.mm/SingleSiteHE/getHE',
        'PRODUCTION' => 'http://marpt.mpt.com.mm/SingleSiteHE/getHE'
    ],

    'SMARTKID' => [
    	'CpId' => 'CF',
    	'productID' => '9500', 
    	'pName' => 'Smart+Kids',
    	'pPrice' => 150,
    	'pVal' => 1,
    	'CpPwd' => 'cf@123',
    	'CpName' => 'CF',
    	'reqMode' => 'WAP',
    	'reqType' => 'SUBSCRIPTION',
    	'ismID' => 17,
    	'sRenewalPrice' => 150,
    	'sRenewalValidity' => 1,
    	'Wap_mdata' => NULL,
    	'request_locale' => 'my',
    	'serviceType' => 'T_CF_KIDS_SUB_D',
    	'planId' => 'T_CF_KIDS_SUB_D_150',
    	'opId' => 101,
        'cli' => '8733'
    ],

    'MMSPORT' => [
    	'CpId' => 'CF',
    	'productID' => '9510', 
    	'pName' => 'Myanmar+Sports',
    	'pPrice' => 150,
    	'pVal' => 1,
    	'CpPwd' => 'cf@123',
    	'CpName' => 'CF',
    	'reqMode' => 'WAP',
    	'reqType' => 'SUBSCRIPTION',
    	'ismID' => 17,
    	'sRenewalPrice' => 150,
    	'sRenewalValidity' => 1,
    	'Wap_mdata' => NULL,
    	'request_locale' => 'my',
    	'serviceType' => 'T_CF_SPORT_SUB_D',
    	'planId' => 'T_CF_SPORT_SUB_D_150',
    	'opId' => 101,
        'cli' => '8633'
    ],

    'GUESSIT' => [
        'CpId' => 'CF',
        'productID' => '9520', 
        'pName' => 'Guess+It',
        'pPrice' => 150,
        'pVal' => 1,
        'CpPwd' => 'cf@123',
        'CpName' => 'CF',
        'reqMode' => 'WAP',
        'reqType' => 'SUBSCRIPTION',
        'ismID' => 17,
        'sRenewalPrice' => 150,
        'sRenewalValidity' => 1,
        'Wap_mdata' => NULL,
        'request_locale' => 'my',
        'serviceType' => 'T_CF_GUESS_SUB_D',
        'planId' => 'T_CF_GUESS_SUB_D_150',
        'opId' => 101,
        'cli' => '8533'
    ],

    'GUESSITEVENT' => [
        'CpId' => 'CF',
        'productID' => '9530', 
        'pName' => 'Guess+It+Event',
        'pPrice' => 100,
        'pVal' => 1,
        'CpPwd' => 'cf@123',
        'CpName' => 'CF',
        'reqMode' => 'WAP',
        'reqType' => 'EVENT',
        'ismID' => 17,
        'sRenewalPrice' => 100,
        'sRenewalValidity' => 1,
        'Wap_mdata' => NULL,
        'request_locale' => 'my',
        'serviceType' => 'T_CF_GUESS_PUR',
        'planId' => 'T_CF_GUESS_PUR_E_100',
        'opId' => 101,
        'cli' => '8533'
    ],


    '9510' => [
        'logo' => 'web/images/sport_logo.png',
        'home' => 'http://myanmar-sport.co',
        'url' => 'http://myanmar-sport.co/news',
        'image' => 'web/images/sports-lp.png',
        'landing' => 'ပရီးမီးယားလိဒ်၊ မြန်မာနေရှင်နယ်လိဒ်နှင့် လိဒ်အစုံ၏ ရမှတ်၊ အဆင့်များကို အချိန်နှင့်တပြေးညီ သိရှိရမှာပါ။',
        'landing_price' => 'စာရင်းသွင်းခြင်းအတွက်နေ့စဉ်(၁၅၀)ကျပ်',
        'unsubscribed' => 'MM Sport ဝန်ဆောင်မှု ရပ်တန်ခြင်း အောင်မြင်ပါသည်',
        'regUser' => 'REGIS_MPT',
        'regPassword' => 'UkVHSVNfT1RQQDU0MzI'
    ],

    '9500' => [
        'logo' => 'web/images/kids_logo.png',
        'home' => 'http://my-smartkids.co',
        'url' => 'http://my-smartkids.co/categories',
        'image' => 'web/images/kids-lp.png',
        'landing' => 'ကလေးများအတွက် ပညာရေးဝန်ဆောင်မှု၊ <br/> သီချင်းများ၊ ရုပ်ပြများ၊ ဥာဏ်စမ်းပဟေဠိများ <br/> ပါဝင်ပါသည်။', 
        'landing_price' => 'စာရင်းသွင်းခြင်းအတွက်နေ့စဉ်(၁၅၀)ကျပ်',
        'unsubscribed' => 'Smart Kid ဝန်ဆောင်မှု ရပ်တန်ခြင်း အောင်မြင်ပါသည်',
        'regUser' => 'REGIS_MPT',
        'regPassword' => 'UkVHSVNfT1RQQDU0MzI'
    ],

    '9520' => [
        'logo' => 'web/images/guessit.JPG',
        'home' => 'https://guessitmm.com',
        'url' => 'https://guessitmm.com',
        'image' => 'web/images/guessit.JPG',
        'landing' => 'မြန်မာနိုင်ငံရဲ့ အကောင်းဆုံးဂိမ်းကို ကစားပြီး ဂိုး(သို့မဟုတ်)ဂိုးဝင်မဝင် ခန့်မှန်းရအောင်',
        'landing_price' => 'စာရင်းသွင်းခြင်းအတွက်နေ့စဉ်(၁၅၀)ကျပ်',
        'unsubscribed' => 'Guess It ဝန်ဆောင်မှု ရပ်တန်ခြင်း အောင်မြင်ပါသည်',
        'regUser' => 'REGIS_MPT',
        'regPassword' => 'UkVHSVNfT1RQQDU0MzI'
    ],

    '9530' => [
        'logo' => 'web/images/guessit.JPG',
        'home' => 'https://guessitmm.com',
        'url' => 'https://guessitmm.com/',
        'image' => 'web/images/guessit.JPG',
        'landing' => 'မြန်မာနိုင်ငံရဲ့ အကောင်းဆုံးဂိမ်းကို ကစားပြီး ဂိုး(သို့မဟုတ်)ဂိုးဝင်မဝင် ခန့်မှန်းရအောင်',
        'landing_price' => 'စာရင်းသွင်းခြင်းအတွက်နေ့စဉ်(၁၀၀)ကျပ်',
        'unsubscribed' => 'Guess It ဝန်ဆောင်မှု ရပ်တန်ခြင်း အောင်မြင်ပါသည်',
        'regUser' => 'REGIS_MPT',
        'regPassword' => 'UkVHSVNfT1RQQDU0MzI'
    ],
];





