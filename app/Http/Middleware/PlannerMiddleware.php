<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PlannerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        if (!Auth::user()->isPlanner()) {
            abort(403, 'Unauthorized. Planner access required.');
        }

        return $next($request);
    }
}