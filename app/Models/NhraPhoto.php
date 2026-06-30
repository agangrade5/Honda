<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhraPhoto extends Model
{
    protected $table = 'nhraphotos';

    protected $primaryKey = 'photoid';

    public $timestamps = false;

    protected $guarded = [];
}