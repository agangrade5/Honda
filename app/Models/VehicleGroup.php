<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleGroup extends Model
{
    protected $table = 'vehiclegroups';

    protected $primaryKey = 'groupid';

    public $timestamps = false;

    protected $guarded = [];
}