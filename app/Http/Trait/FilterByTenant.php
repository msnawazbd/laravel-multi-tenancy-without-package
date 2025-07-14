<?php

namespace App\Http\Trait;

use Illuminate\Database\Eloquent\Builder;

trait  FilterByTenant
{
    protected static function boot()
    {
        parent::boot();

        // $currentTenantId = auth()->user()->tenants()->wherePivot('is_active', true)->first()->id;
        $currentTenantId = auth()->user()->current_tenant_id;

        self::creating(function ($model) use ($currentTenantId) {
            $model->tenant_id = $currentTenantId;
            $model->user_id = auth()->id();
        });

        self::addGlobalScope(function (Builder $builder) use ($currentTenantId) {
            $builder->where('tenant_id', $currentTenantId);
        });
    }
}
