<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HogEvent extends Model
{
    protected $table = 'hogevents';

    protected $primaryKey = 'hogeventid';

    public $timestamps = false;

    protected $guarded = [];
}