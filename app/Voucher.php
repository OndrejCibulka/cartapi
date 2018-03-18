<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'code','discount_value'];
    protected $table = 'vouchers';
}
