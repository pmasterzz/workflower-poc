<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskList extends Model
{
    protected $fillable = [
        'name',
        'serialized_workflow',
        'state',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
