<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportRegion extends Model
{
    protected $table = 'reportregions';

    protected $primaryKey = 'regionid';

    public $timestamps = false;

    protected $guarded = [];
}