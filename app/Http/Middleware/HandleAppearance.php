<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $appearance = $request->user()?->appearances->mode ?? 'auto';

        // Share appearance with all Inertia responses
        Inertia::share('appearance', $appearance);

        // Also share with blade views
        view()->share('appearance', $appearance);

        return $next($request);
    }
}
