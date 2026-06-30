<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DragRaceScore extends Model
{
    protected $table = 'dragracescores';

    protected $primaryKey = 'scoreid';

    public $timestamps = false;

    protected $guarded = [];
}