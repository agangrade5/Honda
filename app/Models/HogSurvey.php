<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HogSurvey extends Model
{
    protected $table = 'hogsurveys';

    protected $primaryKey = 'hogsurveyid';

    public $timestamps = false;

    protected $guarded = [];
}