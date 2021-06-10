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
				$transid = getTranid(setTranid(getUUID()));
				$user = Customer::where('msisdn', $msisdn)->first();
				if(empty($user)) {
					$user = new Customer;
					$user->msisdn = $msisdn;
					$user->save();
					return $this->cgRequestURL($msisdn, $transid);
				}

				$subscriber = Subscriber::where('customer_id', $user->id)->first();
				if($subscriber) {
					if(FALSE == $subscriber->is_active && FALSE == $subscriber->is_subscribed) {
						return $this->cgRequestURL($msisdn, $transid);
					}	
				} else {
					return $this->cgRequestURL($msisdn, $transid);
				}

				// OTP Process for Active User
				$data = otpSend(); 
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
		$query = build_http_query(config('custom')[$service_type]) . $append;
		$url = $endpoint . 'CGRequest?' . $query;
		return $url;
	}
}




