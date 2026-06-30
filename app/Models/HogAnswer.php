<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HogAnswer extends Model
{
    protected $table = 'hoganswers';

    protected $primaryKey = 'hoganswerid';

    public $timestamps = false;

    protected $guarded = [];
}