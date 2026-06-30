<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestrictedRider extends Model
{
    protected $table = 'restrictedriders';

    protected $primaryKey = 'restrictid';

    public $timestamps = false;

    protected $guarded = [];
}