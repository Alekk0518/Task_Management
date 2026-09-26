<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Import the SoftDeletes trait

class Task extends Model
{
    use SoftDeletes; // 2. Enable the trait inside your model

    protected $fillable = [
        'task_name',
        'description',
        'status',
        'due_date',
    ];
}