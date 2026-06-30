<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoApp extends Model
{
    protected $table = 'photoapp';

    protected $primaryKey = 'photoid';

    public $timestamps = false;

    protected $guarded = [];
}