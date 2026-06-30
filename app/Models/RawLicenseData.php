<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawLicenseData extends Model
{
    protected $table = 'rawlicensedata';

    protected $primaryKey = 'rawlicid';

    public $timestamps = false;

    protected $guarded = [];
}