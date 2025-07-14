<?php

namespace App\Http\Trait;
use Illuminate\Database\Eloquent\Builder;

trait FilterByUser
{
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
}
