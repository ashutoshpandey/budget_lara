<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function customer(){
        return $this->belongsTo('Customer', 'customer_id');
    }
}
