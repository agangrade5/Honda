<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BtQueue extends Model
{
    protected $table = 'btqueue';

    protected $primaryKey = 'btq_id';

    public $timestamps = false;

    protected $guarded = [];
}