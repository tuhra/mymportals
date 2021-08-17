<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Customer;
use App\Model\Subscriber;
use App\Model\SubscriberLog;
use App\Model\MptCallbackLog;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;

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
        $response = [];
        // $response['status'] = 200;
        // return $response;

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
                        // Event Charge

                        case 'ES':
                            $subscriber = Subscriber::where('customer_id', $customer->id)
                                        ->where('service_id', $this->serviceId)->first();
                            if (empty($subscriber)) {   
                                $subscriber = otpsub_createion($customer->id, $this->serviceId, "ONETIMEPURCHASE");
                                subscriber_log($customer->id, 'ONETIMEPURCHASE', $this->serviceId);
                                $this->callbacklog($customer->id, 'ONETIMEPURCHASE', $this->serviceId);
                            } else {
                                otp_renewal($subscriber->id, "ONETIMEPURCHASE");
                                subscriber_log($customer->id, 'ONETIMEPURCHASE', $this->serviceId);
                                $this->callbacklog($customer->id, 'ONETIMEPURCHASE', $this->serviceId);
                            }
                            $response['status'] = 200;
                            return $response;
                            break;

						// New Subscriber
						case 'SN':
                        case 'SAA':
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
                    if (empty($subscriber)) {   
                        $subscriber = subscriber_creation($customer->id, $this->serviceId);
                        subscriber_log($customer->id, 'SUBSCRIBED', $this->serviceId);
                        $this->callbacklog($customer->id, 'SUBSCRIBED', $this->serviceId);
                    } else {
                        renewal($subscriber->id);
                        subscriber_log($customer->id, 'RENEWAL', $this->serviceId);
                        $this->callbacklog($customer->id, 'RENEWAL', $this->serviceId);
                    }

					// $this->callbacklog($customer->id, 'ALREADY_SUBSCRIBED', $this->serviceId);
					$response['status'] = 200;
					return $response;
					break;

				// Insufficient balance
				case '2032':
					switch ($operationId) {
						// Unsubscribe case (Remove 'YS')
                        case 'YS':
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
        if(empty($customer)) {
            $response['status'] = FALSE;
            $response['url'] = url('error');
            return json_encode($response);
        }
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

    public function checkMsisdn(Request $request) {
        $data = $request->all();
        $msisdn = $data['msisdn'];
        $serviceId = $data['service_id'];
        $servicetype = $data['service_type'];
        $response = [];
        if(array_key_exists('msisdn', $data)) {
            $customer = Customer::where('msisdn', $msisdn)->where('service_id', $serviceId)->first();
            if(empty($customer)) {
                $response['status'] = FALSE;
                $response['message'] = "msisdn does not exist";
                $response['url'] = "http://mymportals.com/?service_type=". $servicetype."&service_id=" . $serviceId;
                return response()->json($response, 404);
            }

            $subscriber = Subscriber::where('customer_id', $customer->id)->first();
            if(empty($subscriber)) {
                $response['status'] = FALSE;
                $response['message'] = "msisdn is not subscribed!";
                $response['url'] = "http://mymportals.com/?service_type=". $servicetype."&service_id=" . $serviceId;
                return response()->json($response, 404);
            }

            switch ($subscriber->sub_type) {
                case 'ONETIMEPURCHASE':
                    $response = [
                        'status' => TRUE,
                        'msisdn' => $customer->msisdn,
                        'valid_date' => $subscriber->valid_date,
                        'subscribe_type' => "ONETIMEPURCHASE",
                        'eligible' => checkEligible($subscriber->is_active),
                    ];
                    return response()->json($response, 200);
                    break;
                
                default:
                    $response = [
                        'status' => TRUE,
                        'msisdn' => $customer->msisdn,
                        'valid_date' => $subscriber->valid_date,
                        'subscribe_type' => "SUBSCRIPTION",
                        'eligible' => checkEligible($subscriber->is_active),
                    ];
                    return response()->json($response, 200);
                    break;
            }
        }

        $response['status'] = FALSE;
        $response['message'] = "Something went wrong!";
        $response['url'] = "";
        return response()->json($response, 404);
    }

    public function unsubscribe(Request $request) {
        $data = json_decode($request->getContent(), TRUE);
        $validator = $this->validator($data);
        $errors = [];
        $errors['validation'] = [];
        if ($validator->fails()) {
            $validators = $validator->errors()->getMessages();
            foreach ($validators as $key => $error) {
                $errors['validation'][] = [
                    'attribute' => $key,
                    'errors' => $error
                ];
            }
            return response()->json($errors); 
        }

        $customer = Customer::find($data['user_id']);
        if(empty($customer)) {
            $e = new \stdClass();
            $e->key = "invalid";
            $e->errors = "User Id not found";
            $errors['validation'][] = [
                'attribute' => 'user_id',
                'errors' => [
                    $e
                ],
            ];
            return response()->json($errors);
        }
        $subscriber = Subscriber::where('customer_id', $customer->id)->first();
        if(empty($subscriber)) {
            $e = new \stdClass();
            $e->key = "invalid";
            $e->errors = "User Id not subscribed";
            $errors['validation'][] = [
                'attribute' => 'user_id',
                'errors' => [
                    $e
                ],
            ];
            return response()->json($errors);
        }
        $result = unsubscribe_process($customer->msisdn, $data['service_type']);
        $xml = $result['res'];
        $xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        $response = [];
        if("0" == $array['error_code']) {
            unsubscribe($subscriber->id);
            $response = [
                'status' => TRUE,
                'msisdn' => $customer->msisdn,
                'valid_date' => $subscriber->valid_date,
                'subscribe_type' => $array['errorDesc'],
                'eligible' => checkEligible($subscriber->is_active)
            ];
            return response()->json($response, 200);
        }

        $response = [
            'status' => FALSE,
            'msisdn' => $customer->msisdn,
            'valid_date' => $subscriber->valid_date,
            'subscribe_type' => $array['errorDesc'],
            'eligible' => checkEligible($subscriber->is_active)
        ];
        return response()->json($response, 200);
    }

    public function validator($data) {
        $rules = [
            'user_id' => 'required|integer',
            'service_id' => 'required|integer',
            'service_type' => 'required|in:GUESSIT',
        ];

        $messages = [
            'user_id.required' => [
                'key' => 'required',
                'message' => 'The :attribute field is required.'
            ],
            'user_id.integer' => [
                'key' => 'integer',
                'message' => 'The :attribute must be integer.'
            ],
            'service_id.required' => [
                'key' => 'integer',
                'message' => 'The :attribute field is required.'
            ],
            'service_id.integer' => [
                'key' => 'required',
                'message' => 'The :attribute must be integer.'
            ],
            'service_type.required' => [
                'key' => 'required',
                'message' => 'The :attribute field is required.'
            ],
            'service_type.in' => [
                'key' => 'invalid',
                'message' => 'The :attribute value must be GUESSIT.'
            ],
        ];
        return Validator::make($data, $rules, $messages);
    }



}








