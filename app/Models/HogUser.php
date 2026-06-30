<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HogUser extends Model
{
    protected $table = 'hogusers';

    protected $primaryKey = 'hoguserid';

    public $timestamps = false;

    protected $guarded = [];
}