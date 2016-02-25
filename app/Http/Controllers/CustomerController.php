<?php
namespace App\Http\Controllers;

use App\Customer;
use App\Category;
use App\PaymentMode;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    function __construct()
    {
        //View::share('root', URL::to('/'));
    }

    public function info(Request $request)
    {
        $id = $request->input('id');

        $customer = Customer::where(array('id' => $id))->first();

        $categoryCount = Category::where(array('customer_id' => $id, 'status' => 'active'))->count();
        $paymentModeCount = PaymentMode::where(array('customer_id' => $id, 'status' => 'active'))->count();

        if(isset($customer))
            return json_encode(
                array(
                    'message'=>'found',
                    'customer' => $customer,
                    'category_count' => $categoryCount,
                    'payment_mode_count' => $paymentModeCount
                )
            );
        else
            return json_encode(array('message'=>'empty'));
    }

    public function create(Request $request)
    {
        $deviceId = $request->input('device_id');

        $customer = Customer::where('device_id', $deviceId)->first();

        if(isset($customer))
            return json_encode(array('message' => 'existing', 'customer' => $customer));
        else {
            $phone = $request->input('phone');

            $customer = Customer::where('phone', $phone)->first();

            if (!isset($customer)) {

                $customer = new Customer;

                $customer->name = $request->input('name');
                $customer->phone = $request->input('phone');
                $customer->device_id = $request->input('device_id');
                $customer->password = '';//$request->input('password');
                $customer->photo = 'default';
                $customer->status = 'active';
                $customer->created_at = date('Y-m-d h:i:s');
                $customer->updated_at = date('Y-m-d h:i:s');

                $customer->save();

                return json_encode(array('message' => 'done', 'customer' => $customer));
            } else
                return json_encode(array('message' => 'duplicate'));
        }
    }

    public function update(Request $request)
    {
        $customerId = $request->input('customerId');

        $customer = Customer::where('id', $customerId)->first();

        if(isset($customer)) {

            $customer->name = $request->input('name');
            $customer->phone = $request->input('phone');

            $customer->save();

            return json_encode(array('message' => 'done', 'customer' => $customer));
        }
        else
            return json_encode(array('message' => 'invalid'));
    }
}