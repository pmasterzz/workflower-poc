<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'name',
        'task_list_id',
    ];

    public function taskList(): BelongsTo
    {
        return $this->belongsTo(TaskList::class, 'task_list_id');
    }
}
