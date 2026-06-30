<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProxyShadow extends Model
{
    protected $table = 'proxyshadow';

    protected $primaryKey = 'logid';

    public $timestamps = false;

    protected $guarded = [];
}