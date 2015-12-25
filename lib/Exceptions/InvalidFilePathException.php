<?php
namespace Shantilab\BxEcho\Exceptions;

/**
 * Class InvalidFilePathException
 * @package Shantilab\BxEcho\Exceptions
 */
class InvalidFilePathException extends \RuntimeException
{
    /**
     * InvalidFilePathException constructor.
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        parent::__construct(sprintf(
            'Invalid file path: "%s"',
            $filePath
        ));
    }
}