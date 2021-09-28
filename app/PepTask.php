<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PepTask extends Model
{
    protected $table = 'pep_task';

    protected $fillable = [
        'title',
        'state',
        'workflow',
        'completed',
    ];
}
