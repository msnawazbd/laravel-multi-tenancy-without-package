<?php

namespace App\Models;

use App\Http\Trait\FilterByUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use FilterByUser;
    protected $fillable = ['name', 'user_id'];

    /**
     * Get the tasks for the project.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
