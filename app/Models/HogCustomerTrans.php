<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HogCustomerTrans extends Model
{
    protected $table = 'hogcustomertrans';

    protected $primaryKey = 'hogtransid';

    public $timestamps = false;

    protected $guarded = [];
}