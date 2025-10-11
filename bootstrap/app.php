<?php

use App\Exceptions\ApiException;
use App\Http\Middleware\Api\ForceJsonResponseMiddleware;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Services\InertiaNotification;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Request as RequestAlias;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/health',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'primary-color', 'neutral-color']);

        $middleware->trustProxies(
            at: '*',
            headers: RequestAlias::HEADER_X_FORWARDED_FOR
        );

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->api(prepend: [
            ForceJsonResponseMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->renderable(function (Throwable $throwable, Request $request) {
            if ($request->is('api/*') && $request->wantsJson()) {
                return app(ApiException::class)->renderApiException($throwable);
            }
        });

        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if (! $request->wantsJson()) {
                if (! app()->environment(['local', 'testing']) && in_array($response->getStatusCode(), [Response::HTTP_INTERNAL_SERVER_ERROR, Response::HTTP_SERVICE_UNAVAILABLE, Response::HTTP_FORBIDDEN, Response::HTTP_NOT_FOUND, Response::HTTP_METHOD_NOT_ALLOWED, 419], true)) {

                    if ($request->hasSession()) {
                        $request->session()->forget($request->session()->get('_flash.old', []));
                        $request->session()->forget($request->session()->get('_flash.new', []));
                    }

                    $redirect = $request->header('referer') && $request->header('referer') !== $request->url() ? $request->header('referer') : '/';

                    if ($response->getStatusCode() === 419) {
                        $redirect = route('login', absolute: false);
                    }

                    return Inertia::render('Exception', [
                        'redirect' => $redirect,
                        'error'    => [
                            'statusCode'    => $response->getStatusCode(),
                            'statusMessage' => Response::$statusTexts[$response->getStatusCode()],
                            'message'       => $exception->getMessage() ?? $exception->getPrevious()->getMessage(),
                        ],
                    ])
                        ->toResponse($request)
                        ->setStatusCode($response->getStatusCode());
                } else {

                    if ($response->getStatusCode() === Response::HTTP_TOO_MANY_REQUESTS) {
                        /** @var ThrottleRequestsException $exception */
                        InertiaNotification::make()
                            ->error()
                            ->title($exception->getMessage())
                            ->message("Too many request, please try again in {$exception->getHeaders()['Retry-After']} seconds.")
                            ->send();

                        return back();
                    }
                }

                return $response;
            }

            return $response;
        });
    })->create();
