<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(User $user): View
    {
        return view('profile.partials.update-profile-information-form', compact('user'));
    }
    public function myAccount (Request $request)
    { 
        $user=$request->user();
        return redirect()->route('profile.edit', $user);

    }
    
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, User $user): RedirectResponse
{
    $user = User::find($user->id);
    $user->fill($request->validated());

    if ($request->role==="on") {
        $user->role = 'superviseur';
    } 

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    return Redirect::route('agents')->with('status', 'profile-updated');
}


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);


        $user->delete();


        return Redirect::route('agents');
    }
}
