<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Helpers\MptHelper;
use App\Helpers\MsisdnHelper;

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
        return view('frontend.msisdn');
    }

    public function postMsisdn(Request $request) {
        $data = $request->all();
        $msisdn = '959'. $data['msisdn'];
        $msisdnhelper = new MsisdnHelper;
        $url = $msisdnhelper->checkMsisdnStatus($msisdn);
        return redirect(url($url));
    }

    public function otp() {
        return view('frontend.otp');
    }

    public function postOtp(Request $request) {
        $data = $request->all();
        $otp = $data['otp'];
        $result = otpValidation($otp);
        return $result;
    }

    public function resentOtp() {
        $data = otpRegeneration();
        return Session::all();
        return redirect(url('otp'));
    }

}
