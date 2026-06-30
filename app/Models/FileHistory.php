<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileHistory extends Model
{
    protected $table = 'filehistory';

    protected $primaryKey = 'historyid';

    public $timestamps = false;

    protected $guarded = [];
}