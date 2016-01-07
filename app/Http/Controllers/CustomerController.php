<?php
namespace App\Http\Controllers;

class CustomerController extends Controller
{
    function __construct()
    {
        View::share('root', URL::to('/'));
    }

    public function info()
    {
        $phone = Input::get('phone');

        $customer = Customer::where('phone', $phone)->first();

        if(is_null($customer))
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'customer' => $customer));
    }

    public function create()
    {
        $phone = Input::get('phone');

        $customer = Customer::where('phone', $phone)->first();

        if(is_null($customer)) {

            $customer = new Customer;

            $customer->name = Input::get('name');
            $customer->phone = Input::get('phone');
            $customer->password = Input::get('password');
            $customer->photo = 'default';
            $customer->status = 'active';
            $customer->created_at = date('Y-m-d h:i:s');
            $customer->updated_at = date('Y-m-d h:i:s');

            $customer->save();

            return json_encode(array('message' => 'done'));
        }
        else
            return json_encode(array('message' => 'duplicate'));
    }
}