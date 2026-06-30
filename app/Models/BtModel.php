<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BtModel extends Model
{
    protected $table = 'btmodels';

    protected $primaryKey = 'bt_modelid';

    public $timestamps = false;

    protected $guarded = [];
}