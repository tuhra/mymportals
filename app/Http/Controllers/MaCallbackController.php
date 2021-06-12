<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Customer;
use App\Model\Subscriber;
use App\Model\SubscriberLog;
use App\Model\MptCallbackLog;
use DB;

class MaCallbackController extends Controller
{
    private $reqBody;
	private $status_code;
	private $message;
	private $tranid;
	private $vDay;
	private $link;

    public function callback() {
    	$callback = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        \Log::info($callback);
        /*
		$this->reqBody = $callback;
		$data = $request->all();
		$msisdn = $data['callingParty'];

		if (array_key_exists('result', $data)) {
			$this->message = $data['result'];
		}

		if (array_key_exists('operationId', $data)) {
			$operationId = $data['operationId'];
		}

		if (array_key_exists('sequenceNo', $data)) {
			$this->tranid = $data['sequenceNo'];
		}

		if (array_key_exists('chargeAmount', $data)) {
			$chargeAmount = $data['chargeAmount'];
		}

		if (array_key_exists('validityDays', $data)) {
			$this->vDay = $data['validityDays'];
		}

		$customer = Customer::where('msisdn',$msisdn)->first();


		if (array_key_exists('resultCode', $data)) {
			$this->status_code = $data['resultCode'];
			// check respose code
			switch ($this->status_code) {
				// response success
				case '0':
					// check subscribe status
					switch ($operationId) {
						// New Subscriber
						case 'SN':
							$subscriber = Subscriber::where('customer_id', $customer->id)->first();
							if (empty($subscriber)) {	
								subscriber_creation($customer->id, $this->tranid, 0);
                                subscriber_log($customer->id, 'SUBSCRIBED', $channel_id);
                                $this->callbacklog($customer->id, 'SUBSCRIBED');
							} else {
								renewal($subscriber->id, $this->tranid, 0);
                                subscriber_log($customer->id, 'RENEWAL', $channel_id);
                                $this->callbacklog($customer->id, 'RENEWAL');
							}
							$response['status'] = 200;
							return $response;
							break;
						// End new subscriber

						// Returning Subscriber
                        case 'PN':
                            $subscriber = Subscriber::where('customer_id', $customer->id)->first();
                            if (empty($subscriber)) {   
                                subscriber_creation($customer->id, $this->tranid, 0);
                                subscriber_log($customer->id, 'SUBSCRIBED', $channel_id);
                                $this->callbacklog($customer->id, 'SUBSCRIBED');
                            } else {
                                renewal($subscriber->id, $this->tranid, 0);
                                subscriber_log($customer->id, 'RENEWAL', $channel_id);
                                $this->callbacklog($customer->id, 'RENEWAL');
                            }
                            $response['status'] = 200;
                            return $response;
                            break;
                        // End returning subscriber

                       	// Unsubscribe case
                        case 'ACI':
                        case 'PCI':
                        case 'SCI':
                        case 'YS':
                        case 'PD':
                        case 'RD':
                        // case 'SP':
                            $subscriber = Subscriber::where('customer_id', $customer->id)->first();

                            unsubscribe($subscriber->id);
                            subscriber_log($customer->id, 'UNSUBSCRIBED', $channel_id);
                            $this->callbacklog($customer->id, 'UNSUBSCRIBED');
                            $response['status'] = 200;
                            return $response;
                            break;
                        //End unsubscribe case

                        // Renewal success
                        case 'YR':
                        case 'YF':
                        case 'RR':
                        case 'RF':
                            $this->link = 'http://taptubemm.com/welcome';
                            $subscriber = Subscriber::where('customer_id', $customer->id)->first();
                            renewal($subscriber->id, $this->tranid, 0);
                            subscriber_log($customer->id, 'RENEWAL', $channel_id);
                            $this->callbacklog($customer->id, 'RENEWAL');
                            $response['status'] = 200;
                            return $response;
                            break;
                        // End Renewal success

					}
					break;

                // User have already subscribe
				case '2084':
                    $subscriber = Subscriber::where('customer_id', $customer->id)->first();
					$this->callbacklog($customer->id, 'ALREADY_SUBSCRIBED');
					$response['status'] = 200;
					return $response;
					break;

				// Insufficient balance
				case '2032':
					switch ($operationId) {
						// Unsubscribe case (Remove 'YS')
                        case 'ACI':
                        case 'PCI':
                        case 'SCI':
                        case 'PD':
                        case 'RD':
                            $subscriber = Subscriber::where('customer_id', $customer->id)->first();
                            unsubscribe($subscriber->id);
                            subscriber_log($customer->id, 'UNSUBSCRIBED', $channel_id);
                            $this->callbacklog($customer->id, 'UNSUBSCRIBED');
                            Session::forget('msisdn');
                            Session::forget('customer_id');
                            Session::forget('error_code');
                            Session::forget('operationId');
                            $response['status'] = 200;
                            return $response;
                            break;
                        //End unsubscribe case
                    }

                    $subscriber = Subscriber::where('customer_id', $customer->id)->first();
                    if (!$subscriber) {
                        subscriber_creation($customer->id, $this->tranid, 1);
                        subscriber_log($customer->id, 'SUBSCRIBED', $channel_id);
                    } else {
                        renewal($subscriber->id, $this->tranid, 1);
                        subscriber_log($customer->id, 'RENEWAL', $channel_id);
                    }
                    $this->callbacklog($customer->id, 'INSUFFICIENT BALANCE');
                    $response['status'] = 200;
                    return $response;
					break;

				// Msisdn is in black list
				case '4105':
                    $this->callbacklog($customer->id, 'BLACK LIST');
                    $response['status'] = 200;
                    return $response;
					break;
				
				default:
					# code...
					break;
			}
		}
        */
    }

    private function callbacklog($player_id, $action) {
        $date = $date = date('Y-m-d H:i:s');
        MptCallbackLog::create([
            'customer_id' => $player_id,
            'reqBody' => $this->reqBody,
            'resBody' => '200',
            'status_code' => $this->status_code,
            'tranid' => $this->tranid,
            'message' => $this->message,
            'action' => $action,
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }

    public function notify(Request $request){
   		$notify = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        \Log::info($notify);
   		// $data = $request->all();
   		// return view('frontend.landing.loading', compact('data'));
   	}

   	public function checkStatus(Request $request){
        $data = $request->all();
        $msisdn = '95'.$data['msisdn'];
        $customer = Customer::where('msisdn', $msisdn)->first();
        $subscriber = Subscriber::where('customer_id', $customer->id)->first();
        if($subscriber != null) {
            if (TRUE == $subscriber->is_not_enough) {
                Session::put('insufficient', TRUE);
                $response['url'] = url('/landing');
            }
        }
        $result = check_callback($customer->id, $data['tranid']);
        if ($result) {
            $response['url'] = $result->link;
        } else {
            $response['url'] = url('/');
        }
        return json_encode($response);
    }

}
