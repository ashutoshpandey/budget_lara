<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    public function budget()
    {
        return $this->belongsTo('App\Budget', 'budget_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
}
