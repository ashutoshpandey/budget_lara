<?php
namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function __construct()
    {
        //View::share('root', URL::to('/'));
    }

    public function all(Request $request)
    {
        $customer_id = $request->input('customer_id');

        $categories = Category::where(array('customer_id' => $customer_id))->get();

        if(!isset($categories) || count($categories)==0)
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'categories' => $categories->toArray()));
    }

    public function add(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $name = $request->input('name');

        $existingCategory = Category::where(array('customer_id' => $customer_id, 'name' => $name ))->first();

        if(isset($existingCategory))
            return json_encode(array('message' => 'duplicate'));
        else{
            $category = new Category;

            $category->customer_id = $customer_id;
            $category->name = $name;
            $category->status = 'active';
            $category->created_at = date('Y-m-d h:i:s');
            $category->updated_at = date('Y-m-d h:i:s');

            $category->save();

            return json_encode(array('message' => 'done'));
        }
    }

    public function remove(Request $request)
    {
        $id = $request->input('id');

        $category = Category::find(array('id' => $id));

        if(isset($category)) {

            $category->remove();

            return json_encode(array('message' => 'done'));
        }
        else
            return json_encode(array('message'=>'invalid'));
    }
}