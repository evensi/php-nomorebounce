<?php
namespace Exception;

use \Exception;

class ResponseException extends Exception
{

    private $request;
    private $response;

    /**
     * Constructor
     *
     * @param string $message
     * @param int $code
     * @param mixed $request
     * @param Response $response
     * @param Exception $previus
     * @throws \Throwable
     */
    public function __construct(
    $message = NULL, $code = 0, $request = NULL, $response = NULL, Exception $previus = NULL)
    {

        parent::__construct($message, $code, $previus);

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Return the response (if any)
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Return the request
     *
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }
}
