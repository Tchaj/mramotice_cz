<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password_old',
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

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, ExceptionHandler|Throwable $exception): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($request->is('api*')) {
            $request->headers->set('Accept', 'application/json');
        }
        return $this->handleException($request, $exception);
    }

    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        return $this->handleFailed('unauthenticated', 401);
    }

    /**
     * @throws Throwable
     */
    private function handleException(\Illuminate\Http\Request $request, Throwable|ExceptionHandler $exception): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if($exception instanceof MethodNotAllowedException){
            return $this->handleFailed('The specified method for the request in invalid!', 405);
        }

        if($exception instanceof NotFoundHttpException){
            return $this->handleFailed('The specified URL cannot be found!', 404);
        }

        if($exception instanceof HttpException){
            return $this->handleFailed($exception->getMessage(), $exception->getStatusCode());
        }



        if(config('app.debug')){
            return parent::render($request, $exception);
        }

        return $this->handleFailed('Unexcepted Exception', 500);
    }
}
