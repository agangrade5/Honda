<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'languages';

    protected $primaryKey = 'languageid';

    public $timestamps = false;

    protected $guarded = [];
}