<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * Override default 'login' route → pakai 'admin.login'
     */
    protected function redirectTo(Request $request): ?string
    {
        // Hanya redirect untuk request HTML (bukan API/AJAX)
        if ($request->expectsJson()) {
            return null;
        }

        return route('admin.login');
    }
}