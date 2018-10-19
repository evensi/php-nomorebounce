<?php
namespace brickheadz\NoMoreBounce\Exception;

use \Exception;

class ParamException extends Exception
{

    /**
     * Constructor
     * 
     * @param string $message
     * @param int $code
     * @param Exception $previus
     * @throws \Throwable
     */
    public function __construct(string $message = NULL, int $code = 0, Exception $previus = NULL)
    {
        parent::__construct($message, $code, $previus);
    }
}
