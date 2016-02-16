<?php
namespace App\Http\Controllers;

use App\PaymentMode;
use Illuminate\Http\Request;

class PaymentModeController extends Controller
{
    function __construct()
    {
        //View::share('root', URL::to('/'));
    }

    public function all(Request $request)
    {
        $customer_id = $request->input('customer_id');

        $paymentModes = PaymentMode::where(array('customer_id' => $customer_id, 'status' => 'active'))->get();

        if(!isset($paymentModes) || count($paymentModes)==0)
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'paymentModes' => $paymentModes->toArray()));
    }

    public function add(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $name = $request->input('name');

        $name = strtolower($name);

        $existingPaymentMode = PaymentMode::where(array('customer_id' => $customer_id, 'name' => $name ))->first();

        if(isset($existingPaymentMode))
            return json_encode(array('message' => 'duplicate'));
        else{
            $paymentMode = new PaymentMode;

            $paymentMode->customer_id = $customer_id;
            $paymentMode->name = $name;
            $paymentMode->status = 'active';
            $paymentMode->created_at = date('Y-m-d h:i:s');
            $paymentMode->updated_at = date('Y-m-d h:i:s');

            $paymentMode->save();

            return json_encode(array('message' => 'done'));
        }
    }

    public function remove(Request $request)
    {
        $id = $request->input('id');

        $paymentMode = PaymentMode::where(array('id' => $id))->first();

        if(isset($paymentMode)) {

            $paymentMode->delete();

            return json_encode(array('message' => 'done'));
        }
        else
            return json_encode(array('message'=>'invalid'));
    }
}