<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\Invitation;
use App\Notifications\SendInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invitations = Invitation::where('tenant_id', auth()->user()->current_tenant_id)->latest()->get();
        return view('users.index', compact('invitations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $invitation = Invitation::create([
            'tenant_id' => auth()->user()->current_tenant_id,
            'email' => $request->email,
            'token' => Str::random(32),
        ]);

        Notification::route('mail', $request->email)->notify(new SendInvitationNotification($invitation));

        return redirect()->route('users.index')->with('success', __('Invitation sent to :email', ['email' => $request->email]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function acceptInvitation($token)
    {
        $invitation = Invitation::with('tenant')->where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        if (auth()->check()) {
            $invitation->update(['accepted_at' => now()]);

            auth()->user()->tenants()->attach($invitation->tenant_id);

            auth()->user()->update(['current_tenant_id' => $invitation->tenant_id]);

            return redirect()->route('dashboard');
        }
        return redirect()->route('register', ['token' => $invitation->token]);
    }
}
