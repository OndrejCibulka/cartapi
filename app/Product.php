<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'product_id','name','url','code','image','producer_name','producer_home_page_url','description_summary','amount_step','amount_unit'];
    protected $table = 'products';

}



