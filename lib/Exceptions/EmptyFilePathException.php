<?php
namespace Shantilab\BxEcho\Exceptions;

/**
 * Class EmptyFilePathException
 * @package Shantilab\BxEcho\Exceptions
 */
class EmptyFilePathException extends \RuntimeException
{
    /**
     * EmptyFilePathException constructor.
     */
    public function __construct()
    {
        parent::__construct('Empty file path');
    }
}