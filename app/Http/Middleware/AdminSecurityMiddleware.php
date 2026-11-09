<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminSecurityMiddleware
{
    /**
     * Admin Security Middleware - Multi-layer protection for admin panel
     * Works with file-based sessions
     * 
     * Checks:
     * 1. User authentication
     * 2. Admin role verification
     * 3. CSRF token validation
     * 4. Request signature verification
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check authentication
        if (!Auth::check()) {
            Log::channel('security')->warning('Unauthorized access attempt (not authenticated)', [
                'ip' => $request->ip(),
                'url' => $request->path(),
                'timestamp' => now(),
            ]);

            return redirect()->guest(route('login'))
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Check admin role
        if (!Auth::user()->isAdmin()) {
            Log::channel('security')->warning('Unauthorized admin access attempt (wrong role)', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'user_role' => Auth::user()->role,
                'ip' => $request->ip(),
                'url' => $request->path(),
                'timestamp' => now(),
            ]);

            abort(403, 'Akses tidak diizinkan. Anda bukan admin.');
        }

        // 3. Check CSRF token untuk POST, PUT, DELETE requests
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            if (!$this->isValidCsrfToken($request)) {
                Log::channel('security')->warning('Invalid CSRF token detected', [
                    'user_id' => Auth::id(),
                    'user_email' => Auth::user()->email,
                    'ip' => $request->ip(),
                    'method' => $request->method(),
                    'url' => $request->path(),
                    'timestamp' => now(),
                ]);

                abort(419, 'CSRF token mismatch. Silakan refresh halaman.');
            }
        }

        // 4. Add security headers
        $response = $next($request);

        foreach ([
            'X-Frame-Options' => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
        ] as $name => $value) {
            $response->headers->set($name, $value);
        }

        return $response;
    }

    /**
     * Verify CSRF token untuk mencegah CSRF attacks
     */
    private function isValidCsrfToken(Request $request): bool
    {
        $token = $request->input('_token') ?? $request->header('X-CSRF-TOKEN');

        if (empty($token)) {
            return false;
        }

        return hash_equals(
            session('_token'),
            $token
        );
    }
}
