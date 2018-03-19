<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'name', 'label', 'price', 'description', 'long_description', 'image'];
    protected $table = 'payments';

    public function getPriceAttribute($value)
    {
    	if ($value == 0) {
    		return 'Zdarma';
    	} else {
    		return $value . ' Kč';
    	}
    	
    }
}
