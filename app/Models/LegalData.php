<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalData extends Model
{
    protected $table = 'legaldata';

    protected $primaryKey = 'legaldataid';

    public $timestamps = false;

    protected $guarded = [];
}