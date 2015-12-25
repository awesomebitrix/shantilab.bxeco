<?php
namespace Shantilab\BxEcho\Exceptions;

/**
 * Class FunctionNotFoundException
 * @package Shantilab\BxEcho\Exceptions
 */
class FunctionNotFoundException extends \RuntimeException
{
    /**
     * FunctionNotFoundException constructor.
     * @param string $functionName
     */
    public function __construct($functionName)
    {
        parent::__construct(sprintf(
            'Function "%s" does not exist',
            $functionName
        ));
    }
}