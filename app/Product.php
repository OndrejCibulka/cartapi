<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $fillable = ['id','product_id','variant_id','complete_name','code','image','producer_name','producer_home_page_url','description_summary','amount_step','amount_unit','price_with_vat_for_customer','sale_sticker','new_sticker'];
    protected $table = 'products';
}



