<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegPhoto extends Model
{
    protected $table = 'regphotos';

    protected $primaryKey = 'photoid';

    public $timestamps = false;

    protected $guarded = [];
}