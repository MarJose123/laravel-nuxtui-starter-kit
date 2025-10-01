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
        $appearance = $request->user()?->appearance;

        View::share('theme', $appearance->mode ?? 'system');

        Inertia::share('theme', [
            'mode'    => $appearance->mode ?? 'system',
            'primary' => $appearance->primary_color ?? 'green',
            'neutral' => $appearance->secondary_color ?? 'slate',
        ]);

        return $next($request);
    }
}
