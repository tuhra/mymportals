<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use App\Model\Customer;
use App\Model\Subscriber;
use App\Model\SubscriberLog;
use Carbon\Carbon;

class ImportController extends Controller
{
    public function import() {
        return view('frontend.import');
    }

    public function postImport(Request $request) {
        $data = $request->all();
        if ($request->hasFile('excel'))  {
            $file = $request->file('excel');
            $filename = time().$file->getClientOriginalName();
            $file->move('excel', $filename);
            $rows = Excel::load(public_path('excel') . '/' . $filename)->get();
            $file_path = public_path('excel') . '/' . $filename;
            $insert = [];
            foreach ($rows as $key => $row) {
                $customer = new Customer;
                $customer->msisdn = $row->mdn;
                $customer->service_id = $data['service_id'];
                $customer->save();

                $subscriber = new Subscriber;
                $subscriber->customer_id = $customer->id;
                $subscriber->is_active = TRUE;
                $subscriber->is_subscribed = TRUE;                
                $subscriber->valid_date = Carbon::now()->addDays(1);
                $subscriber->service_id = $data['service_id'];
                $subscriber->sub_type = "SUBSCRIBED";
                $subscriber->save();

                $log = new SubscriberLog;
                $log->customer_id = $customer->id;
                $log->event = "SUBSCRIBED";
                $log->service_id = $data['service_id'];
                $log->save();
            }

            return 'success';
        }
    }

}
