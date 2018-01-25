<?php

namespace Hellovoid\Gdax\Exception;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpException extends \Exception
{
    private $errors;
    private $request;
    private $response;

    /**
     * Wraps an API exception in the appropriate domain exception.
     *
     * @param RequestException $e The API exception
     *
     * @return HttpException
     */
    public static function wrap(RequestException $e)
    {
        $response = $e->getResponse();

        $class = self::exceptionClass($response);
        $message = self::getErrorMessage($response);

        return new $class($message, [], $e->getRequest(), $response, $e);
    }

    public function __construct($message, array $errors, RequestInterface $request, ResponseInterface $response, \Exception $previous)
    {
        parent::__construct($message, 0, $previous);

        $this->errors = $errors;
        $this->request = $request;
        $this->response = $response;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    private static function getErrorMessage(ResponseInterface $response)
    {
        $data = $response ? json_decode($response->getBody(), true) : null;

        if (isset($data['message'])) {
            return $data['message'];
        }

        return null;
    }

    private static function exceptionClass(ResponseInterface $response)
    {
        switch ($response->getStatusCode()) {
            case 400:
                return BadRequestException::class;
            case 401:
                return UnauthorizedException::class;
            case 403:
                return InvalidScopeException::class;
            case 404:
                return NotFoundException::class;
            case 422:
                return ValidationException::class;
            case 429:
                return RateLimitException::class;
            case 500:
                return InternalServerException::class;
            default:
                return HttpException::class;
        }
    }
}
