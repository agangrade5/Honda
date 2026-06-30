<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyData extends Model
{
    protected $table = 'surveydata';

    protected $primaryKey = 'surveydataid';

    public $timestamps = false;

    protected $guarded = [];
}