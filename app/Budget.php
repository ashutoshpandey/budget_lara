<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function budgetItems()
    {
        return $this->hasMany('App\BudgetItem', 'budget_id');
    }

    public function budgetShares()
    {
        return $this->hasMany('App\BudgetShare', 'budget_id');
    }
}
