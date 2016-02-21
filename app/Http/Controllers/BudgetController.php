<?php
namespace App\Http\Controllers;

use App\Budget;
use App\BudgetShare;
use App\BudgetItem;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BudgetController extends Controller
{
    function __construct()
    {
        //View::share('root', URL::to('/'));
    }

    public function all(Request $request)
    {
        $customer_id = $request->input('customer_id');

        $budgets = Budget::where(array('customer_id' => $customer_id))->get();

        if(!isset($budgets) || count($budgets)==0)
            return json_encode(array('message'=>'empty'));
        else
            return json_encode(array('message'=>'found', 'budgets' => $budgets->toArray()));
    }

    public function shares(Request $request)
    {
        $customer_id = $request->input('customer_id');

        $budgetShares1 = BudgetShare::where(array('from_customer_id' => $customer_id))->with('budget')->with('toCustomer')->get();
        $budgetShares2 = BudgetShare::where(array('to_customer_id' => $customer_id))->with('budget')->with('fromCustomer')->get();

        if(isset($budgetShares1) && isset($budgetShares2))
            $budgetShares = array_merge($budgetShares1->toArray(), $budgetShares2->toArray());
        else if(isset($budgetShares1))
            $budgetShares = $budgetShares1->toArray();
        else if(isset($budgetShares2))
            $budgetShares = $budgetShares2->toArray();

        if(isset($budgetShares) && count($budgetShares)>0)
            return json_encode(array('message'=>'found', 'budgetShares' => $budgetShares));
        else
            return json_encode(array('message'=>'empty'));
    }

    public function items(Request $request)
    {
        $budget_id = $request->input('budget_id');

        $budgetItems = BudgetItem::where(array('budget_id' => $budget_id, 'status' => 'active'))->orderBy('entry_date','desc')->with('Category')->with('Customer')->get();

        if(isset($budgetItems) && count($budgetItems)>0)
            return json_encode(array('message'=>'found', 'budgetItems' => $budgetItems));
        else
            return json_encode(array('message'=>'empty'));
    }

    public function itemsFiltered(Request $request)
    {
        $budget_id = $request->input('budget_id');
        $yearMonth = $request->input('yearMonth');

        $arYearMonth = explode(",", $yearMonth);
        $year = $arYearMonth[0];
        $month = intval($arYearMonth[1])+1;

/*        DB::listen(function($sql) {
            print_r($sql);
        });*/

        $budgetItems = BudgetItem::where('budget_id' , $budget_id)
                                ->where(DB::raw('year(entry_date)'), '=', $year)
                                ->where(DB::raw('month(entry_date)'), '=' , $month)
                                ->where('status' , 'active')
                                ->orderBy('entry_date','desc')
                                ->with('Category')
                                ->with('Customer')->get();


        if(isset($budgetItems) && count($budgetItems)>0)
            return json_encode(array('message'=>'found', 'budgetItems' => $budgetItems));
        else
            return json_encode(array('message'=>'empty'));
    }

    public function addItem(Request $request)
    {
        $categoryId = $request->input('category_id');

        $budgetItem = new BudgetItem;

        $budgetItem->customer_id = $request->input('customer_id');
        $budgetItem->budget_id = $request->input('budget_id');

        if($categoryId>0)
            $budgetItem->category_id = $categoryId;

        $budgetItem->name = $request->input('name');
        $budgetItem->price = $request->input('price');
        $budgetItem->payment_mode = $request->input('payment_mode');
        $budgetItem->remarks = '';//$request->input('remarks');
        $budgetItem->status = 'active';
        $budgetItem->entry_date = date('Y-m-d h:i:s', strtotime($request->input('date')));
        $budgetItem->created_at = date('Y-m-d h:i:s');
        $budgetItem->updated_at = date('Y-m-d h:i:s');

        $budgetItem->save();

        return json_encode(array('message'=>'done'));
    }

    public function removeItem(Request $request)
    {
        $id = $request->input('item_id');

        $budgetItem = BudgetItem::where('id' , $id)->first();

        if(isset($budgetItem)) {

            $budgetItem->status = 'removed';

            $budgetItem->save();

            return json_encode(array('message' => 'removed'));
        }
        else
            return json_encode(array('message'=>'notfound', 'id' => $id));
    }

    public function share(Request $request)
    {
        $to_customer_id = $request->input('to_customer_id');
        $from_customer_id = $request->input('from_customer_id');
        $budget_id = $request->input('budget_id');

        $customer = Customer::where('id', $to_customer_id)->first();

        if(isset($customer)) {
            $existingBudgetShare = BudgetShare::where(
                array(
                    'from_customer_id' => $from_customer_id,
                    'to_customer_id' => $to_customer_id,
                    'budget_id' => $budget_id
                )
            )->first();

            if (isset($existingBudgetShare))
                return json_encode(array('message' => 'duplicate'));
            else {
                $budgetShare = new BudgetShare;

                $budgetShare->from_customer_id = $from_customer_id;
                $budgetShare->to_customer_id = $to_customer_id;
                $budgetShare->budget_id = $budget_id;
                $budgetShare->status = 'active';
                $budgetShare->created_at = date('Y-m-d h:i:s');
                $budgetShare->updated_at = date('Y-m-d h:i:s');

                $budgetShare->save();

                return json_encode(array('message' => 'done'));
            }
        }
        else
            return json_encode(array('message' => 'invalid'));
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
        $maxAmount = $request->input('max_amount');

        $budget = new Budget;

        $existingBudget = Budget::where(array('customer_id' => $customerId, 'name' => $name))->first();

        if(isset($existingBudget))
            return json_encode(array('message' => 'duplicate'));
        else {
            $budget->customer_id = $customerId;
            $budget->name = $name;
            $budget->budget_type = $budgetType;
            $budget->max_amount = $maxAmount;
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

    public function update(Request $request)
    {
        $budgetId = $request->input('budget_id');
        $budgetType = $request->input('budget_type');
        $name = $request->input('name');
        $maxAmount = $request->input('max_amount');

        $budget = Budget::where(array('id' => $budgetId))->first();

        if(isset($budget)){
            $budget->name = $name;
            $budget->budget_type = $budgetType;
            $budget->max_amount = $maxAmount;
            $budget->remarks = '';// $request->input('remarks');

            if ($budgetType == "date range") {
                $budget->start_date = date('Y-m-d h:i:s', $request->input('start_date'));
                $budget->end_date = date('Y-m-d h:i:s', $request->input('end_date'));
            }

            $budget->updated_at = date('Y-m-d h:i:s');

            $budget->save();

            return json_encode(array('message' => 'done'));
        }
        else
            return json_encode(array('message' => 'invalid'));
    }
}