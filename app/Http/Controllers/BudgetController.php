<?php
namespace App\Http\Controllers;

use App\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    function __construct()
    {
        //View::share('root', URL::to('/'));
    }

    public function all(Request $request)
    {
        $customer_id = $request->input('customer_id');

        $budgets = Budget::where(array('customer_id' => $customer_id))->all();

        if(is_null($budgets))
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'budgets' => $budgets->toArray()));
    }

    public function shares(Request $request)
    {
        $customer_id = $request->input('customer_id');

        $budgetShares = BudgetShare::where(array('customer_id' => $customer_id))->all();

        if(is_null($budgetShares))
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'budgetShares' => $budgetShares->toArray()));
    }

    public function items(Request $request)
    {
        $budget_id = $request->input('budget_id');

        $budgetItems = BudgetItem::where(array('budget_id' => $budget_id, 'status' => 'active'))->all();

        if(is_null($budgetItems))
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'budgetItems' => $budgetItems->toArray()));
    }

    public function addItem(Request $request)
    {
        $budgetItem = new BudgetItem;

        $budgetItem->customer_id = $request->input('customer_id');
        $budgetItem->budget_id = $request->input('budget_id');
        $budgetItem->name = $request->input('name');
        $budgetItem->price = $request->input('price');
        $budgetItem->remarks = $request->input('remarks');
        $budgetItem->status = 'active';
        $budgetItem->created_at = date('Y-m-d h:i:s');
        $budgetItem->updated_at = date('Y-m-d h:i:s');

        $budgetItem->save();

        return json_encode(array('message'=>'done'));
    }

    public function removeItem(Request $request)
    {
        $id = $request->input('id');

        $budgetItem = BudgetItem::find(array('id' => $id));

        if(is_null($budgetItem)) {

            $budgetItem->status = 'removed';

            $budgetItem->save();

            return json_encode(array('message' => 'empty'));
        }
        else
            return json_encode(array('message'=>'notfound'));
    }

    public function find(Request $request)
    {
        $budget_id = $request->input('budget_id');

        $budget = Budget::find(array('budget_id' => $budget_id));

        if(is_null($budget))
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'budget' => $budget));
    }

    public function create(Request $request)
    {
        $customerId = $request->input('customer_id');
        $budgetType = $request->input('budget_type');
        $name = $request->input('name');

        $budget = new Budget;

        $existingBudget = Budget::where(array('customer_id' => $customerId, 'name' => $name))->first();

        if(isset($existingBudget))
            return json_encode(array('message' => 'duplicate'));
        else {
            $budget->customer_id = $customerId;
            $budget->name = $name;
            $budget->budget_type = $budgetType;
            $budget->remarks = '';// $request->input('remarks');
            $budget->status = 'active';

            if ($budgetType == "date range") {
                $budget->start_date = date('Y-m-d h:i:s', $request->input('start_date'));
                $budget->end_date = date('Y-m-d h:i:s', $request->input('end_date'));
            }

            $budget->created_at = date('Y-m-d h:i:s');
            $budget->updated_at = date('Y-m-d h:i:s');

            $budget->save();

            return json_encode(array('message' => 'done'));
        }
    }
}