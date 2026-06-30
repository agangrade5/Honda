<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $table = 'trucks';

    protected $primaryKey = 'truckid';

    public $timestamps = false;

    protected $guarded = [];
}