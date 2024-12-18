<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Larowka\PreventDuplicateRequests\Middleware\PreventDuplicateRequests;
use Shared\Helpers\ResponseHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: ''
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            //\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \App\Http\Middleware\ForceJsonResponse::class,
            \App\Http\Middleware\CacheHeadersMiddleware::class,
            \App\Http\Middleware\LogContext::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'check.permissions' => \App\Http\Middleware\CheckPermissions::class,
        ]);
        $middleware->append(PreventDuplicateRequests::class);
        //$middleware->append(\Spatie\Permission\Middleware\RoleMiddleware::class);
        //$middleware->append(\Spatie\Permission\Middleware\PermissionMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // customize response for 404 error for route and resource
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if (($previous = $e->getPrevious()) instanceof ModelNotFoundException && $request->expectsJson()) {
                return ResponseHelper::error(class_basename($previous->getModel()) . ' Not Found.', 404);
            }

            return ResponseHelper::error('Route not found', 404);
        });

        $exceptions->renderable(function (TooManyRequestsHttpException $e) {
            return ResponseHelper::error('Duplicate requests', 429);
        });
    })->create();
