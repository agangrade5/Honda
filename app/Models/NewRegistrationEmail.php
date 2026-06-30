<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewRegistrationEmail extends Model
{
    protected $table = 'newregistrationemail';

    public $timestamps = false;

    protected $guarded = [];
}