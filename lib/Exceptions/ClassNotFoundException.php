<?php
namespace Shantilab\BxEcho\Exceptions;

/**
 * Class ClassNotFoundException
 * @package Shantilab\BxEcho\Exceptions
 */
class ClassNotFoundException extends \RuntimeException
{
    /**
     * ClassNotFoundException constructor.
     * @param string $className
     */
    public function __construct($className)
    {
        parent::__construct(sprintf(
            'Class "%s" does not exist',
            $className
        ));
    }
}