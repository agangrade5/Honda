<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Legal extends Model
{
    protected $table = 'legal';

    protected $primaryKey = 'legalid';

    public $timestamps = false;

    protected $guarded = [];
}