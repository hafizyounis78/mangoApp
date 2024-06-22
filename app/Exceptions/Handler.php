<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // This will replace our 404 response with
        // a JSON response.


        if ($request->is('api/*')) {
            if ($exception instanceof ModelNotFoundException &&  $request->wantsJson()) {
                return $this->responseJson(false , 'Resource not found.' , null);

            }else if($exception instanceof  MethodNotAllowedHttpException && $request->wantsJson()) {
                return $this->responseJson(false , 'Method not allowed.' , null);

            }
        }


        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {


        if ($request->expectsJson()) {
            return $this->responseJson(false , 'Unauthenticated.' , null);
        }

        return redirect()->guest(route('login'));
    }


    public function responseJson($status, $message, $data, $var = null)
    {
        $arr = [];
        $arr['status'] = $status;
        $arr['message'] = $message;
        $arr['data'] = $data;

        return response()->json($arr);
    }
}
