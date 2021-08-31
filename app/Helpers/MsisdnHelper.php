<?php

namespace App\Helpers;
use App;
use App\Model\Customer;
use App\Model\Subscriber;
use Session;

class MsisdnHelper 
{
	public function checkMsisdnStatus($msisdn) {
		$operator = checkoperator("+".$msisdn);
		switch ($operator) {
			case 'MPT':
				$msisdn = getMsisdn(setMsisdn($msisdn));
				$transid = getUUID();
				$service_id = getServiceId();
				$customer = Customer::where('msisdn', $msisdn)->where('service_id', $service_id)->first();
				if(empty($customer)) {
					$customer = new Customer;
					$customer->msisdn = $msisdn;
					$customer->service_id = $service_id;
					$customer->save();
					return $this->cgRequestURL($msisdn, $transid);
				}

				$subscriber = Subscriber::where('customer_id', $customer->id)->first();
				if($subscriber) {
					if(FALSE == $subscriber->is_active && FALSE == $subscriber->is_subscribed) {
						return $this->cgRequestURL($msisdn, $transid);
					}	
				} else {
					return $this->cgRequestURL($msisdn, $transid);
				}

				if("9530" == $service_id) {
					return $this->cgRequestURL($msisdn, $transid);
				}

				// OTP Process for Active User
				$data = otpSend(); 
				\Log::info('OTP Send Req Res');
				\Log::info($data);
				$url = 'otp';
				return $url;

				break;
			case NULL:
			case 'Telenor':
			case 'Ooredoo':
				$url = 'invalid';
				return $url;
				break;
		}
	}

	private function cgRequestURL($msisdn, $transid) {
		$service_type = getServiceType();
		$append = '&MSISDN=' . $msisdn . '&transID=' . $transid;
		$env = config('app')['env'];
		$endpoint = config('custom')['URL'][$env];
		switch ($service_type) {
			case 'MMSPORT':
				$url = $endpoint . 'CGRequest?CpId=CF&MSISDN='.$msisdn.'&productID=9510&pName=Myanmar+Sports&pPrice=150&pVal=1&CpPwd=cf%40123&CpName=CF&reqMode=WAP&reqType=Subscription&ismID=17&transID='.$transid.'&sRenewalPrice=150&sRenewalValidity=1&Wap_mdata=&request_locale=my&serviceType=T_CF_SPORT_SUB_D&planId=T_CF_SPORT_SUB_D_150';
				return $url;
				break;
			
			case 'SMARTKID':
				$url = $endpoint . 'CGRequest?CpId=CF&MSISDN='.$msisdn.'&productID=9500&pName=Smart%2BKids&pPrice=150&pVal=1&CpPwd=cf%40123&CpName=CF&reqMode=WAP&reqType=Subscription&ismID=17&transID='.$transid.'&sRenewalPrice=150&sRenewalValidity=1&Wap_mdata=&request_locale=my&serviceType=T_CF_KIDS_SUB_D&planId=T_CF_KIDS_SUB_D_150';
				return $url;
				break;
			case 'GUESSIT':
				$url = $endpoint . 'CGRequest?CpId=CF&MSISDN='.$msisdn.'&productID=9520&pName=Guess%2BIt&pPrice=150&pVal=1&CpPwd=cf%40123&CpName=CF&reqMode=WAP&reqType=Subscription&ismID=17&transID='.$transid.'&sRenewalPrice=150&sRenewalValidity=1&Wap_mdata=&request_locale=my&serviceType=T_CF_GUESS_SUB_D&planId=T_CF_GUESS_SUB_D_150';
				return $url;
				break;
			case 'GUESSITEVENT':
				$url = $endpoint . 'CGRequest?CpId=CF&MSISDN='.$msisdn.'&productID=9530&pName=Guess%2BIt%2BEvent&pPrice=100&pVal=1&CpPwd=cf%40123&CpName=CF&reqMode=WAP&reqType=Event&ismID=17&transID='.$transid.'&sRenewalPrice=100&sRenewalValidity=1&Wap_mdata=&request_locale=my&serviceType=T_CF_GUESS_PUR&planId=T_CF_GUESS_PUR_E_100';
				return $url;
				break;
		}
	}
}


