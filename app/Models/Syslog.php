<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syslog extends Model
{
    protected $table = 'syslog';

    protected $primaryKey = 'logid';

    public $timestamps = false;

    protected $guarded = [];
}