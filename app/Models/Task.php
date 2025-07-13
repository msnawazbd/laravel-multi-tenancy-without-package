<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'project_id'];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // Automatically set the user_id to the authenticated user's ID
            $model->user_id = auth()->id();
        });

        self::addGlobalScope(function (Builder $builder){
            // Apply a global scope to only include projects for the authenticated user
            $builder->where('user_id', auth()->id());
        });
    }

    /**
     * Get the project that owns the task.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
