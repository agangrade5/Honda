<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BtSet extends Model
{
    protected $table = 'btsets';

    protected $primaryKey = 'btset_id';

    public $timestamps = false;

    protected $guarded = [];
}