<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Customer;
use App\Model\Subscriber;
use App\Model\SubscriberLog;
use App\Model\MptCallbackLog;
use DB;
use Session;

class MaCallbackController extends Controller
{
    private $reqBody;
	private $status_code;
	private $message;
	private $tranid;
	private $vDay;
    private $serviceId;

    public function callback(Request $request) {
    	$callback = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        \Log::info($callback);
        
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

        if(array_key_exists('serviceId', $data)) {
            $this->serviceId = $data['serviceId'];
        }

		$customer = Customer::where('msisdn',$msisdn)->where('service_id', $this->serviceId)->first();
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
							$subscriber = Subscriber::where('customer_id', $customer->id)
                                        ->where('service_id', $this->serviceId)->first();
							if (empty($subscriber)) {	
								$subscriber = subscriber_creation($customer->id, $this->serviceId);
                                subscriber_log($customer->id, 'SUBSCRIBED', $this->serviceId);
                                $this->callbacklog($customer->id, 'SUBSCRIBED', $this->serviceId);
							} else {
								renewal($subscriber->id);
                                subscriber_log($customer->id, 'RENEWAL', $this->serviceId);
                                $this->callbacklog($customer->id, 'RENEWAL', $this->serviceId);
							}
							$response['status'] = 200;
							return $response;
							break;
						// End new subscriber

						// Returning Subscriber
                        case 'PN':
                            $subscriber = Subscriber::where('customer_id', $customer->id)
                            ->where('service_id', $this->serviceId)->first();
                            if (empty($subscriber)) {   
                                subscriber_creation($customer->id, $this->serviceId);
                                subscriber_log($customer->id, 'SUBSCRIBED', $this->serviceId);
                                $this->callbacklog($customer->id, 'SUBSCRIBED', $this->serviceId);
                            } else {
                                renewal($subscriber->id);
                                subscriber_log($customer->id, 'RENEWAL', $this->serviceId);
                                $this->callbacklog($customer->id, 'RENEWAL', $this->serviceId);
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
                        $subscriber = Subscriber::where('customer_id', $customer->id)
                        ->where('service_id', $this->serviceId)->first();
                            unsubscribe($subscriber->id);
                            subscriber_log($customer->id, 'UNSUBSCRIBED', $this->serviceId);
                            $this->callbacklog($customer->id, 'UNSUBSCRIBED', $this->serviceId);
                            $response['status'] = 200;
                            return $response;
                            break;
                        //End unsubscribe case

                        // Renewal success
                        case 'YR':
                        case 'YF':
                        case 'RR':
                        case 'RF':
                            
                            $subscriber = Subscriber::where('customer_id', $customer->id)
                            ->where('service_id', $this->serviceId)->first();
                            renewal($subscriber->id, $this->serviceId);
                            subscriber_log($customer->id, 'RENEWAL', $this->serviceId);
                            $this->callbacklog($customer->id, $subscriber->id, 'RENEWAL', $this->serviceId);
                            $response['status'] = 200;
                            return $response;
                            break;
                        // End Renewal success

					}
					break;

                // User have already subscribe
				case '2084':
                    $subscriber = Subscriber::where('customer_id', $customer->id)
                    ->where('service_id', $this->serviceId)->first();
					$this->callbacklog($customer->id, 'ALREADY_SUBSCRIBED', $this->serviceId);
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
                            $subscriber = Subscriber::where('customer_id', $customer->id)
                            ->where('service_id', $this->serviceId)->first();
                            unsubscribe($subscriber->id);
                            subscriber_log($customer->id, 'UNSUBSCRIBED', $this->serviceId);
                            $this->callbacklog($customer->id, 'UNSUBSCRIBED', $this->serviceId);
                            Session::forget('msisdn');
                            Session::forget('customer_id');
                            Session::forget('error_code');
                            Session::forget('operationId');
                            $response['status'] = 200;
                            return $response;
                            break;
                        //End unsubscribe case
                    }

                    $subscriber = Subscriber::where('customer_id', $customer->id)
                    ->where('service_id', $this->serviceId)->first();
                    if (!$subscriber) {
                        subscriber_creation($customer->id, $this->serviceId);
                        subscriber_log($customer->id, 'SUBSCRIBED', $this->serviceId);
                    } else {
                        renewal($subscriber->id, $this->serviceId);
                        renewal($subscriber->id);
                        subscriber_log($customer->id, 'RENEWAL', $this->serviceId);
                    }
                    $this->callbacklog($customer->id, 'INSUFFICIENT BALANCE', $this->serviceId);
                    $response['status'] = 200;
                    return $response;
					break;

				// Msisdn is in black list
				case '4105':
                    $this->callbacklog($customer->id, 'BLACK LIST', $this->serviceId);
                    $response['status'] = 200;
                    return $response;
					break;
				
				default:
					# code...
					break;
			}
		}
    }

    private function callbacklog($player_id, $action, $serviceId) {
        $date = $date = date('Y-m-d H:i:s');
        MptCallbackLog::create([
            'customer_id' => $player_id,
            'reqBody' => $this->reqBody,
            'resBody' => '200',
            'status_code' => $this->status_code,
            'tranid' => $this->tranid,
            'message' => $this->message,
            'action' => $action,
            'service_id' => $serviceId,
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }

    public function notify(Request $request){
   		$notify = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        \Log::info($notify);
   		$data = $request->all();
   		return view('frontend.loading', compact('data'));
   	}

   	public function checkStatus(Request $request){
        $data = $request->all();
        $serviceId = getServiceId();
        $msisdn = '95'.$data['msisdn'];
        $customer = Customer::where('msisdn', $msisdn)->where('service_id', $serviceId)->first();
        $subscriber = Subscriber::where('customer_id', $customer->id)->first();
        $result = check_callback($customer->id, $data['tranid']);
        $response = [];
        if ($result) {
            $response['status'] = TRUE;
            $response['url'] = url('success');
        } else {
            $response['status'] = FALSE;
            $response['url'] = url('error');
        }
        return json_encode($response);
    }

}
