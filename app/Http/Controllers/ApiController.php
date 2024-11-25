<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ApiController extends Controller
{
    /**
     * @var int
     */
    private $statusCode = SymfonyResponse::HTTP_OK;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param string $message
     * @param array $data
     * @return mixed
     */
    public function respondCreated(string $message = 'Item successfully created.', $data = [])
    {
        return $this->setStatusCode(SymfonyResponse::HTTP_CREATED)
                    ->respond(['message' => $message, 'data' => $data]);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondNotFound(string $message = 'Item not found.')
    {
        return $this->setStatusCode(SymfonyResponse::HTTP_NOT_FOUND)
                    ->respondWithError($message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondInternalError(string $message = 'Internal Error.')
    {
        return $this->setStatusCode(SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR)
                    ->respondWithError($message);
    }

    public function respondValidationFailed(
        string $message = 'Validation failed.',
        string $component = '',
        $index = null)
    {
        return $this->setStatusCode(SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY)
                    ->respondWithError($message, $component, $index);
    }

    /**
     * @param string $message
     * @param string $component
     * @param int $index
     * @return mixed
     */
    public function respondWithError(string $message, string $component = '', $index = null)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode(),
                'component' => $component,
                'index' => $index,
            ]
        ]);
    }

    /**
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function respond(array $data, array $headers = [])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }
}