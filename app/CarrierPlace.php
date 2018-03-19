<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarrierPlace extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'carrier_id', 'place_id', 'label'];
    protected $table = 'carrier_places';
}
