<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HondaModel extends Model
{
    protected $table = 'models';

    protected $primaryKey = 'modelid';

    public $timestamps = false;

    protected $guarded = [];
}