<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpCredential extends Model
{
    protected $table = 'smtpcredentials';

    protected $primaryKey = 'credentialid';

    public $timestamps = false;

    protected $guarded = [];
}