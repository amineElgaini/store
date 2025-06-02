<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

    
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
    
        $request->session()->regenerate();
    
        $user = Auth::user();
    
        // Check if user was trying to access the checkout before login
        $intended = session()->pull('url.intended'); // full URL like "http://127.0.0.1:8000/cart"
        $cartUrl = route('cart.index'); // full URL with domain

        if ($intended === $cartUrl) {
            return redirect()->route('cart.index')->with('restore_checkout', true);
        }
        
    
        // Redirect based on admin or regular user
        if ($user->is_admin) {
            return redirect()->intended('admin/dashboard');
        }
    
        return redirect()->intended('/products');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/products');
    }
}
