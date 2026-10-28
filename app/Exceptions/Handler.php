<?php

namespace App\Exceptions;

use App\Support\Traits\Api\ApiResponseTrait;

use BadMethodCallException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingScopeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Exception;


class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $guard = $exception->guards()[0] ?? null;

        if ($guard === 'educator' && !$request->expectsJson()) {
            return redirect()->guest(route('educator:login'));
        }

        return parent::unauthenticated($request, $exception);
    }

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $exception): void
    {

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $response = $this->handleException($request, $exception);
        return $response;
    }

    /**
     * @throws Throwable
     */
    public function handleException($request, Throwable $exception): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if (config('app.debug')) {

            return parent::render($request, $exception);
        }

        if (($exception instanceof MissingScopeException || $exception instanceof AuthenticationException) && $request->wantsJson()) {

            return $this->errorResponse(401, __('admin::home.Unauthenticated'));
        }

        //NotFoundHttpException
        if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
            return $this->errorResponse(404, 'The specified URL cannot be found');
        }

        //MethodNotAllowedHttpException
        if ($exception instanceof MethodNotAllowedHttpException && $request->wantsJson()) {

            return $this->errorResponse(405, 'The specified method for the request is invalid');
        }

        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return $this->errorResponse(404, 'The specified URL cannot be found');
        }

        if ($exception instanceof BadMethodCallException && $request->wantsJson()) {
            return $this->errorResponse(500, 'Unexpected Exception. Try later' . $exception->getMessage());
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getStatusCode(), $exception->getMessage());
        }

        if ($exception instanceof ValidationException && $request->wantsJson()) {

            return $this->returnValidationError($exception->validator, $exception);

        }
        if ($exception instanceof RelationNotFoundException && $request->wantsJson()) {

            return $this->errorResponse(500, 'Unexpected Exception. Try later' . $exception->getMessage());

        }

        if ($exception  && $request->wantsJson()) {

            return $this->errorResponse(500, 'Unexpected Exception. Try later' . $exception->getMessage());

        }

        return $this->errorResponse(500, 'Unexpected Exception. Try later' . $exception->getMessage());

    }

    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {

        return response()->json([
            'code'    => $exception->status,
            'message' => $exception->getMessage(),
            'errors'  => $this->transformErrors($exception),
        ], $exception->status);
    }

    // transform the error messages,
    private function transformErrors(ValidationException $exception): array
    {
        $errors = [];

        foreach ($exception->errors() as $field => $message) {
            $errors[$field] = [
                'field' => $field,
                'message' => $message[0],
            ];
        }
        return $errors;
    }
}
