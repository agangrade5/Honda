<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HogQuestion extends Model
{
    protected $table = 'hogquestions';

    protected $primaryKey = 'hogquestionid';

    public $timestamps = false;

    protected $guarded = [];
}