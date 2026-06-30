<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyslogArchive extends Model
{
    protected $table = 'syslogarchive';

    protected $primaryKey = 'logid';

    public $timestamps = false;

    protected $guarded = [];
}