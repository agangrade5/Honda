<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTransBk extends Model
{
    protected $table = 'customertrans_bk';

    protected $primaryKey = 'transid';

    public $timestamps = false;

    protected $guarded = [];
}