<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueEntry extends Model
{
    protected $table = 'queue';

    protected $primaryKey = 'queueid';

    public $timestamps = false;

    protected $guarded = [];
}