<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreregistrationCustomerParent extends Model
{
    protected $table = 'preregistrationcustomerparents';

    protected $primaryKey = 'parentid';

    public $timestamps = false;

    protected $guarded = [];
}