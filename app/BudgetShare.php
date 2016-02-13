<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetShare extends Model
{
    public function fromCustomer(){
        return $this->belongsTo('App\Customer', 'from_customer_id');
    }

    public function toCustomer(){
        return $this->belongsTo('App\Customer', 'to_customer_id');
    }

    public function budget(){
        return $this->belongsTo('App\Budget', 'budget_id');
    }
}
