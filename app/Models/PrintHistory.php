<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintHistory extends Model
{
    protected $table = 'printhistory';

    protected $primaryKey = 'printid';

    public $timestamps = false;

    protected $guarded = [];
}