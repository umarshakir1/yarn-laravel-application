<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSectionPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('Admin')) {
            return $next($request);
        }

        if ($user->hasPermissionTo($permission)) {
            return $next($request);
        }

        abort(403, 'You do not have access to this section.');
    }
}
