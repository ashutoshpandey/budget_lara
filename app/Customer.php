<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function budgets()
    {
        return $this->hasMany('App\Budget', 'customer_id');
    }

    public function budgetShares()
    {
        return $this->hasMany('App\BudgetShare', 'customer_id');
    }

    public function budgetItems()
    {
        return $this->hasMany('App\BudgetItem', 'customer_id');
    }
}
