<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('manage_users', function (User $user) {
            return $user->tenants()
                ->where('id', auth()->user()->current_tenant_id)
                ->wherePivot('is_owner', true)
                ->exists();
        });
    }
}
