<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReminderQueue extends Model
{
    protected $table = 'reminderqueue';

    protected $primaryKey = 'idreminderqueue';

    public $timestamps = false;

    protected $guarded = [];
}