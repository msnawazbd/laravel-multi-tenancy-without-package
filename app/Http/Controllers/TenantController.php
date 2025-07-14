<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function changeTenant($tenantId)
    {
        // Check tenant
        /*if (auth()->user()->tenants()->where('id', $tenantId)->doesntExist()) {
            abort(403, 'Unauthorized action.');
        }*/

        $tenant = auth()->user()->tenants()->findOrFail($tenantId);

        // Change tenant
        auth()->user()->update(['current_tenant_id' => $tenantId]);

        // Redirect to dashboard
        return redirect()->route('dashboard')->with('success', 'Tenant changed successfully.');
    }
}
