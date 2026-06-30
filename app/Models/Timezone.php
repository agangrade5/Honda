<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    protected $table = 'timezone';

    protected $primaryKey = 'timeid';

    public $timestamps = false;

    protected $guarded = [];
}