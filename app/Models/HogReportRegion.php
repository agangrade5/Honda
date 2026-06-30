<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HogReportRegion extends Model
{
    protected $table = 'hogreportregions';

    protected $primaryKey = 'hogregionid';

    public $timestamps = false;

    protected $guarded = [];
}