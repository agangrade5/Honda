<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostRideSurvey extends Model
{
    protected $table = 'postridesurvey';

    protected $primaryKey = 'prsurveyid';

    public $timestamps = false;

    protected $guarded = [];
}