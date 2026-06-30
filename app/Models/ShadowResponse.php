<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShadowResponse extends Model
{
    protected $table = 'shadowresponse';

    protected $primaryKey = 'respid';

    public $timestamps = false;

    protected $guarded = [];
}