<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Support\Facades\Request;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        //return parent::render($request, $exception);
        $error_msg = '服务器错误！';
        if ($exception instanceof HttpException) {
            if ($exception->getStatusCode() == '404') {
                $StatusCode = $exception->getStatusCode();
                $errors = $exception->getMessage();
            } else {
                $StatusCode = 500;
                $errors = $exception->getMessage();
                logError($error_msg, [$errors]);
            }
        } else if ($exception instanceof AuthenticationException) {
            $StatusCode = 403;
            $error_msg = '权限不足！';
            $errors = $exception->getMessage();
        } else {
            $errors = $exception->getMessage();
            $StatusCode = 500;
            logError($error_msg, [$errors]);
        }
        if (Request::ajax()) {
            return json_fail($error_msg, env('APP_DEBUG') ? $errors : null, $StatusCode, $StatusCode);
        } else {
            return parent::render($request, $exception);
        }
    }
}
