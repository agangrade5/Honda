<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTrans extends Model
{
    protected $table = 'customertrans';

    protected $primaryKey = 'transid';

    public $timestamps = false;

    protected $guarded = [];
}