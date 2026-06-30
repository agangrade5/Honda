<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ethnicity extends Model
{
    protected $table = 'ethnicity';

    protected $primaryKey = 'ethnicityid';

    public $timestamps = false;

    protected $guarded = [];
}