<?php

namespace App\Models;

use App\Http\Trait\FilterByUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // use FilterByUser;
    protected $fillable = ['name', 'project_id', 'user_id'];


    protected static function boot()
    {
        parent::boot();

        self::addGlobalScope(function (Builder $builder) {
            $builder->whereHas('project', function (Builder $query) {
                $query->where('user_id', auth()->id());
            });
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
