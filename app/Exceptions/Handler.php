<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Intervention\Image\Exception\NotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            if (request()->wantsJson() || request()->ajax()) {  // if the request is AJAX or JSON
                return response()->json(['message' => 'The url you are looking for might have been removed had its changed or is temporarily unavailable.'], 404);
            }
            if (!auth()->check()) {
                return response()->view('errors.404-frontend');
            }

            if (auth()->guard('student')->check() && request()->is('candidate/*')) {
                return response()->view('errors.404-student');
            } elseif (request()->is('portal/*')) {
                return response()->view('errors.404-admin');
            }
            return response()->view('errors.404-frontend');
        }
        return parent::render($request, $exception);
    }
}
