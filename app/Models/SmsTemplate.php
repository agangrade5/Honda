<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $table = 'smstemplates';

    protected $primaryKey = 'templateid';

    public $timestamps = false;

    protected $guarded = [];
}