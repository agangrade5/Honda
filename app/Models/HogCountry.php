<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HogCountry extends Model
{
    protected $table = 'hogcountries';

    protected $primaryKey = 'hogcountryid';

    public $timestamps = false;

    protected $guarded = [];
}