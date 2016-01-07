<?php
namespace App\Http\Controllers;

class BudgetController extends Controller
{
    function __construct()
    {
        View::share('root', URL::to('/'));
    }

    public function all()
    {
        $customer_id = Input::get('customer_id');

        $budgets = Budget::where('customer_id', $customer_id)->all();

        if(is_null($budgets))
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'budgets' => $budgets->toArray()));
    }

    public function shares()
    {
        $customer_id = Input::get('customer_id');

        $budgetShares = BudgetShare::where('customer_id', '=', $customer_id)->all();

        if(is_null($budgetShares))
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'budgetShares' => $budgetShares->toArray()));
    }

    public function items()
    {
        $budget_id = Input::get('budget_id');

        $budgetItems = BudgetItem::where('budget_id', $budget_id)->where('status', 'active')->all();

        if(is_null($budgetItems))
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'budgetItems' => $budgetItems->toArray()));
    }

    public function addItem()
    {
        $budgetItem = new BudgetItem;

        $budgetItem->customer_id = Input::get('customer_id');
        $budgetItem->budget_id = Input::get('budget_id');
        $budgetItem->name = Input::get('name');
        $budgetItem->price = Input::get('price');
        $budgetItem->remarks = Input::get('remarks');
        $budgetItem->status = 'active';
        $budgetItem->created_at = date('Y-m-d h:i:s');
        $budgetItem->updated_at = date('Y-m-d h:i:s');

        $budgetItem->save();

        return json_encode(array('message'=>'done'));
    }

    public function removeItem()
    {
        $id = Input::get('id');

        $budgetItem = BudgetItem::where('id', $id)->first();

        if(is_null($budgetItem)) {

            $budgetItem->status = 'removed';

            $budgetItem->save();

            return json_encode(array('message' => 'empty'));
        }
        else
            return json_encode(array('message'=>'notfound'));
    }

    public function find()
    {
        $budget_id = Input::get('budget_id');

        $budget = Budget::where('budget_id', $budget_id)->first();

        if(is_null($budget))
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'budget' => $budget));
    }

    public function create()
    {
        $budget = new Budget;

        $budget->customer_id = Input::get('customer_id');
        $budget->budget_type = Input::get('budget_type');
        $budget->remarks = Input::get('remarks');
        $budget->status = 'active';
        $budget->start_date = date('Y-m-d h:i:s', Input::get('start_date'));
        $budget->end_date = date('Y-m-d h:i:s', Input::get('end_date'));
        $budget->created_at = date('Y-m-d h:i:s');
        $budget->updated_at = date('Y-m-d h:i:s');

        $budget->save();

        return json_encode(array('message'=>'done'));
    }
}