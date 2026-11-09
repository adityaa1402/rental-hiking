<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Role Middleware - Verify user has required roles
     * Enhanced with better error handling
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Check authentication MANDATORY
        if (!auth()->check()) {
            Log::channel('security')->warning('Unauthorized access attempt (not authenticated)', [
                'requested_url' => $request->path(),
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ]);

            return redirect()->guest(route('login'))
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Check user has required role
        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            Log::channel('security')->warning('Unauthorized access attempt (insufficient role)', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'user_role' => $userRole,
                'required_roles' => implode(', ', $roles),
                'requested_url' => $request->path(),
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ]);

            abort(403, 'Akses tidak diizinkan. Role Anda: ' . $userRole);
        }

        // 3. Log successful authorization
        Log::channel('security')->info('Authorized access to protected resource', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'user_role' => $userRole,
            'resource' => $request->path(),
            'timestamp' => now(),
        ]);

        return $next($request);
    }
}
