<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'label', 'name', 'image', 'description', 'full_description', 'price'];
    protected $table = 'carriers';

    public function getPriceAttribute($value)
    {
    	if ($value == 0) {
    		return 'Zdarma';
    	} else {
    		return $value . ' Kč';
    	}
    }
}
