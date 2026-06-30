<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'cards';

    protected $primaryKey = 'cardid';

    public $timestamps = false;

    protected $guarded = [];
}