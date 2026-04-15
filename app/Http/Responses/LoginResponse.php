<?php
namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as ResponsesLoginResponse;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements ResponsesLoginResponse
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = auth()->user();

        // Logic Redirect berdasarkan Role
        if ($user->role === 'admin') {
            return redirect()->intended('/admin');
        }

        // Jika user biasa atau role lain, arahkan ke dashboard utama atau path lain
        return redirect()->intended('/');
    }
}
