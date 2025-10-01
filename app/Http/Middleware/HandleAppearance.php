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

        $mode = $appearance->mode ?? 'system';
        $primaryColor = $appearance->primary_color ?? 'green';
        $secondaryColor = $appearance->secondary_color ?? 'slate';

        View::share('appearance', $mode);
        Inertia::share('appearance', $mode);
        Inertia::share('ui', [
            'primary' => $primaryColor,
            'neutral' => $secondaryColor,
        ]);

        return $next($request);
    }
}
