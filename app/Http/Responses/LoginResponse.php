<?php
namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as ResponsesLoginResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements ResponsesLoginResponse
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = Auth::user();

        // Logic Redirect berdasarkan Role
        if ($user->role === 'admin') {
            return redirect()->intended('/admin');
        }


        return redirect()->intended('/');
    }
}
