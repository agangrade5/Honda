<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $table = 'socialmedia';

    protected $primaryKey = 'socialid';

    public $timestamps = false;

    protected $guarded = [];
}