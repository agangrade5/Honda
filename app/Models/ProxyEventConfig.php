<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProxyEventConfig extends Model
{
    protected $table = 'proxyeventconfig';

    protected $primaryKey = 'configid';
    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];
}