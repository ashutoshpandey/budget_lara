<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    public function customer(){
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function budget(){
        return $this->belongsTo('App\Budget', 'budget_id');
    }

    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }
}