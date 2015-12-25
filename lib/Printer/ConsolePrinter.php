<?php
namespace Shantilab\BxEcho\Printer;

/**
 * Class ConsolePrinter
 * @package Shantilab\BxEcho\Printer
 */
class ConsolePrinter extends BasePrinter implements PrinterInterface
{
    /**
     *
     */
    public function fire()
    {
        echo '<script>console.log(' . json_encode($this->data) . ');</script>';
    }
}