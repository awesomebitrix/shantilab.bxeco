<?php
namespace Shantilab\BxEcho\Printer;

/**
 * Class DisplayPrinter
 * @package Shantilab\BxEcho\Printer
 */
class DisplayPrinter extends BasePrinter implements PrinterInterface
{
    /**
     *
     */
    public function fire(){
        if (!function_exists($this->options['printFunction']))
            throw new \Shantilab\BxEcho\Exceptions\FunctionNotFoundException($this->options['printFunction']);

        $this->options['printFunction']($this->getData());
    }
}