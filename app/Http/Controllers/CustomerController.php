<?php
namespace App\Http\Controllers;

use App\Customer;
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

        if(isset($customer))
            return json_encode(array('message'=>'found', 'customer' => $customer));
        else
            return json_encode(array('message'=>'empty'));
    }

    public function create(Request $request)
    {
        $phone = $request->input('phone');

        $customer = Customer::where('phone', $phone)->first();

        if(!isset($customer)) {

            $customer = new Customer;

            $customer->name = $request->input('name');
            $customer->phone = $request->input('phone');
            $customer->password = '';//$request->input('password');
            $customer->photo = 'default';
            $customer->status = 'active';
            $customer->created_at = date('Y-m-d h:i:s');
            $customer->updated_at = date('Y-m-d h:i:s');

            $customer->save();

            return json_encode(array('message' => 'done', 'customer' => $customer));
        }
        else
            return json_encode(array('message' => 'duplicate'));
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