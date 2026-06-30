<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HogCard extends Model
{
    protected $table = 'hogcards';

    protected $primaryKey = 'hogid';

    public $timestamps = false;

    protected $guarded = [];
}