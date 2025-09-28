<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class HandleAppearance
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request):Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->user()?->refresh();
        $appearance = $request->user()?->appearances->mode ?? 'system';

        View::share('appearance', $appearance);
        Inertia::share('appearance', $appearance);

        return $next($request);
    }
}
