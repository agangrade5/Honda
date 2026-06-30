<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticLog extends Model
{
    protected $table = 'diagnosticlogs';

    protected $primaryKey = 'diagnosticlogid';

    public $timestamps = false;

    protected $guarded = [];
}