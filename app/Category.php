<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function customer(){
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function budgetItems(){
        return $this->belongsTo('App\BudgetItem', 'category_id');
    }
}
