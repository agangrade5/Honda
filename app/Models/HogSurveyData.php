<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HogSurveyData extends Model
{
    protected $table = 'hogsurveydata';

    protected $primaryKey = 'hogsurveydataid';

    public $timestamps = false;

    protected $guarded = [];
}