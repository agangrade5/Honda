<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiBatch extends Model
{
    protected $table = 'apibatch';

    protected $primaryKey = 'batchid';

    public $timestamps = false;

    protected $guarded = [];
}