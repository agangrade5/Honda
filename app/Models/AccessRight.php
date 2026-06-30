<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessRight extends Model
{
    protected $table = 'accessrights';

    protected $primaryKey = 'rightsid';

    public $timestamps = false;

    protected $guarded = [];
}