<?php

namespace Fpaipl\Authy\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->account || $user->account->status !== 'approved') {
            return response()->json(['status' => 'error', 'message' => 'Account not approved'], 403);
        }

        return $next($request);
    }

}
