<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class ApiException extends Exception
{
    /**
     * @param Throwable $exception
     *
     * @return JsonResponse
     */
    public function renderApiException(Throwable $exception): JsonResponse
    {

        $responseData = $this->prepareApiExceptionData($exception);
        $payload = Arr::except($responseData, ['statusCode', 'type']);
        $statusCode = $responseData['statusCode'];

        return new JsonResponse($payload, $statusCode);
    }

    /**
     * Prepare the API exception data.
     *
     * @param Throwable $exception
     *
     * @return array
     */
    private function prepareApiExceptionData(Throwable $exception): array
    {
        $responseData['success'] = false;
        $message = $exception->getMessage();

        if ($exception instanceof QueryException) {
            $responseData['message'] = $message;
            $responseData['statusCode'] = 405;
            $responseData['code'] = 405;
            $responseData['type'] = 'QueryException';
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $responseData['message'] = $message;
            $responseData['statusCode'] = 405;
            $responseData['code'] = 405;
            $responseData['type'] = 'MethodNotAllowedHttpException';
        } elseif ($exception instanceof NotFoundHttpException && $exception->getPrevious() instanceof ModelNotFoundException) {
            $responseData['message'] = sprintf('Unable to locate the %s you requested.', $this->modelNotFoundMessage($exception->getPrevious()));
            $responseData['statusCode'] = 404;
            $responseData['code'] = 404;
            $responseData['type'] = 'ModelNotFoundException';
        } elseif ($exception instanceof AuthenticationException) {
            $responseData['message'] = 'Unauthenticated';
            $responseData['statusCode'] = 401;
            $responseData['code'] = 401;
            $responseData['type'] = 'AuthenticationException';
        } elseif ($exception instanceof ValidationException) {
            $responseData['message'] = $message;
            $responseData['errors'] = $exception->validator->errors();
            $responseData['statusCode'] = 422;
            $responseData['code'] = 422;
            $responseData['type'] = 'ValidationException';
        } elseif ($exception instanceof ThrottleRequestsException) {
            $responseData['message'] = $exception->getMessage();
            $responseData['statusCode'] = 429;
            $responseData['code'] = 429;
            $responseData['type'] = 'ThrottleRequestsException';
        } elseif ($exception instanceof HttpResponseException) {
            $responseData['message'] = $exception->getResponse()->getContent();
            $responseData['statusCode'] = $exception->getResponse()->getStatusCode() ?? null;
            $responseData['code'] = $exception->getResponse()->getStatusCode();
            $responseData['type'] = $exception->getResponse()->getStatusCode() === 429 ? 'ThrottleRequestsException' : 'HttpResponseException';
        } elseif ($exception instanceof TooManyRequestsHttpException) {
            $responseData['message'] = $exception->getMessage();
            $responseData['statusCode'] = 429;
            $responseData['code'] = 429;
            $responseData['type'] = 'ThrottleRequestsException';
        } elseif ($exception instanceof InvalidSignatureException) {
            $responseData['message'] = 'The invitation link is invalid or has expired.';
            $responseData['statusCode'] = 403;
            $responseData['code'] = 403;
            $responseData['type'] = 'InvalidSignatureException';
        } elseif ($exception instanceof NotFoundHttpException) {
            $responseData['message'] = blank($message) ? 'Resource not found' : $message;
            $responseData['statusCode'] = 404;
            $responseData['code'] = 404;
            $responseData['type'] = 'NotFoundHttpException';
        } else {
            $responseData['message'] = $this->prepareExceptionMessage($exception);
            $responseData['statusCode'] = ($exception instanceof HttpExceptionInterface) ? $exception->getStatusCode() : 500;
            $responseData['code'] = ($exception instanceof HttpExceptionInterface) ? $exception->getStatusCode() : 500;
            $responseData['type'] = 'serverErrorException';
            if ($debug = $this->extractExceptionData($exception)) {
                $responseData['debug'] = $debug;
            }
        }

        return $responseData;
    }

    /**
     * @param Throwable $exception
     *
     * @return string
     */
    private function prepareExceptionMessage(Throwable $exception): string
    {
        $exceptionMessage = $exception->getMessage();

        if (Str::contains($exceptionMessage, 'syntax error')) {
            $exceptionMessage = 'Server error';
        }

        return $exceptionMessage;
    }

    /**
     * @param ModelNotFoundException $exception
     *
     * @return string
     */
    private function modelNotFoundMessage(ModelNotFoundException $exception): string
    {
        if (! is_null($exception->getModel())) {
            return Str::lower(ltrim((string) preg_replace('/[A-Z]/', ' $0', class_basename($exception->getModel()))));
        }

        return 'resource';
    }

    /**
     * @param Throwable $exception
     *
     * @return array
     */
    private function extractExceptionData(Throwable $exception): array
    {
        if (config('app.debug') && ! ($exception instanceof HttpExceptionInterface)) {
            $data = [
                'message'   => $exception->getMessage(),
                'exception' => $exception::class,
                'file'      => $exception->getFile(),
                'line'      => $exception->getLine(),
                'trace'     => collect($exception->getTrace())->map(fn ($trace) => Arr::except($trace, ['args']))->all(),
            ];
        } else {
            $data = [];
        }

        return $data;
    }
}
