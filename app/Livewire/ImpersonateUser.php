<?php

namespace App\Livewire;

use Livewire\Component;
use App\User;
use Illuminate\Support\Facades\Auth;

class ImpersonateUser extends Component
{
    public $search = '';
    public $users = [];

    public function render()
    {
        // Only show the impersonation feature to superadmins
        if (Auth::check() && Auth::user()->userType->title === 'super_admin') {
            // Search for users based on the search term
            if ($this->search) {
                $this->users = User::where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('username', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->limit(10)
                    ->get();
            } else {
                $this->users = collect(); // Ensure $users is always a collection
            }
        } else {
            $this->users = collect(); // Ensure $users is always a collection
        }

        return view('livewire.impersonate-user');
    }

    public function impersonate($userId)
    {
        $user = User::findOrFail($userId);

        // Store the original user ID in the session
        session()->put('impersonated_by', Auth::id());

        // Log in as the selected user
        Auth::login($user);

        // Redirect to the dashboard or any other page
        return redirect()->route('dashboard');
    }

    public function stopImpersonating()
    {
        // Get the original user ID from the session
        $originalUserId = session('impersonated_by');

        if ($originalUserId) {
            // Log back in as the original user
            Auth::loginUsingId($originalUserId);

            // Remove the impersonation session data
            session()->forget('impersonated_by');
        }

        // Redirect to the dashboard or any other page
        return redirect()->route('dashboard');
    }
}