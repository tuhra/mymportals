<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Helpers\MptHelper;
use App\Helpers\MsisdnHelper;
use App\Model\Customer;
use App\Model\Subscriber;
use Illuminate\Support\Facades\Crypt;
use Redirect;

class WebController extends Controller
{
    public function index(Request $request) {
    	return redirect(url('msisdn'));
        // $data = $request->all();
        // $aes = new MptHelper;
        // $url = $aes->mptHe();
        // return redirect(url($url));
    }

    public function singleHE(Request $request) {
    	$sshe = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        \Log::info($sshe);
        $data = $request->all();
        switch ($data['Reason']) {
        	case 'WIFI':
        		return redirect(url('msisdn'));
        		break;
        	
        	default:
        		// Decrypt MSISDN 
        		// $encrypted_msisdn = $data['MSISDN'];
        		// $msisdn = exec('cd /var/www/decrypt && java JavaDecryptCaller '.$encrypted_msisdn);
                $msisdnhelper = new MsisdnHelper;
                $url = $msisdnhelper->checkMsisdnStatus($msisdn);
                return redirect(url($url));
        		break;
        }
    }

    public function msisdn() {
        $config = config('custom')[getServiceId()];
        return view('frontend.msisdn', compact('config'));
    }

    public function postMsisdn(Request $request) {
        $data = $request->all();
        $msisdn = '959'. $data['msisdn'];
        $msisdnhelper = new MsisdnHelper;
        $url = $msisdnhelper->checkMsisdnStatus($msisdn);
        \Log::info($url);
        return redirect(url($url));
    }

    public function otp() {
        $config = config('custom')[getServiceId()];
        return view('frontend.otp', compact('config'));
    }

    public function postOtp(Request $request) {
        $data = $request->all();
        $otp = $data['otp'];
        $result = otpValidation($otp);
        $xml = $result['res'];
        $xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        if(0 == $array['error_code']) {
            return redirect(url('success'));
        } 

        return redirect(url('otp'));
    }

    public function resentOtp() {
        $data = otpRegeneration();
        return redirect(url('otp'));
    }

    public function continue() {
        $msisdn = getMsisdn();
        $service_id = getServiceId();
        $customer = Customer::where('msisdn', $msisdn)->where('service_id', $service_id)->first();
        $subscriber = Subscriber::where('customer_id', $customer->id)->first();
        if($subscriber->is_active && $subscriber->is_subscribed) {
            $array = [
                'user_id' => $customer->id
            ];

            if("9520" == $service_id || "9530" == $service_id) {
                $array = [
                    'user_id' => $customer->id,
                    'msisdn' => $customer->msisdn,
                    'valid_date' => $subscriber->valid_date,
                    'eligible' => checkEligible($subscriber->is_active),
                ];
            }
            $json = json_encode($array);
            $signature = Crypt::encryptString($json);
            return Crypt::decryptString($signature);
            $url = config('custom')[$service_id]['url'] . "?signature=".$signature;
            return Redirect::away($url);
        }
    }

    public function success() {
        $config = config('custom')[getServiceId()];
        return view('frontend.success', compact('config'));
    }

    public function error() {
        $config = config('custom')[getServiceId()];
        return view('frontend.error', compact('config'));   
    }

    public function invalid() {
        $config = config('custom')[getServiceId()];
        return view('frontend.invalid', compact('config'));   
    }

    public function invalidService() {
        return view('frontend.invalidservice');   
    }

    public function unsubscribe(Request $request) {
        $data = $request->all();
        $customer = Customer::find($data['customer_id']);
        if(empty($customer)) {
            return redirect(url('/'));
        }

        $subscriber = Subscriber::where('customer_id', $customer->id)->first();
        $result = unsubscribe_process($customer->msisdn, $data['service_type']);
        \Log::info($result);
        $xml = $result['res'];
        $xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        $response = [];
        if("0" == $array['error_code']) {
            unsubscribe($subscriber->id);
            $config = config('custom')[$data['service_id']];
            return view('frontend.unsubscribed', compact('config'));
        }

        return redirect(url('/'));
        // return redirect(url('unsub_success'));

        // return $array['error_code'];
    }

}
