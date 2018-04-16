<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    public $timestamps = false;
    protected $fillable = ['id','variant_id', 'product_id', 'name', 'price_with_vat_for_customer', 'stock_count'];
    protected $table = 'variants';

    public function getStockCountAttribute($value)
    {
    	return $value . ' ks';
    }
}
