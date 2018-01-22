<?php

namespace Oauth\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class InvalidCredentialsException
 * @package Oauth\Exceptions
 */
class InvalidCredentialsException extends UnauthorizedHttpException
{
    /**
     * Message default
     */
    const MESSAGE = 'Not Authorized';
    
    /**
     * InvalidCredentialsException constructor.
     * @param Exception|null $previous
     * @param int $code
     */
    public function __construct(\Exception $previous = null, $code = 0)
    {
        parent::__construct('', self::MESSAGE, $previous, $code);
    }
}
