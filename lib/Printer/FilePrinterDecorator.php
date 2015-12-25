<?php
namespace Shantilab\BxEcho\Printer;

use Shantilab\BxEcho\Config;

/**
 * Class FilePrinterDecorator
 * @package Shantilab\BxEcho\Printer
 */
class FilePrinterDecorator implements PrinterDecoratorInterface
{
    /**
     * @var array
     */
    protected $options = [];
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var string
     */
    public $data;
    /**
     * FilePrinterDecorator constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->config = new Config();
        $this->setDefaultParams();

        $this->data = $data;

        ob_start();

        $this->prettyFormat();

        $this->data = ob_get_clean();
    }

    /**
     * @param $printData
     */
    protected function prettyFormat()
    {
        foreach($this->data as $key => $var){
            echo $this->getTop($key);
            var_dump($var);
            echo $this->getBottom();
        }
    }

    /**
     * @return string|void
     */
    protected function getBottom()
    {
        if (!$this->options['deviderSymb']) return;

        return str_repeat($this->options['deviderSymb'], intval($this->options['deviderCnt']));
    }

    /**
     * @param $key
     * @return string
     */
    protected function getTop($key)
    {
        return "[" . date('Y-m-d H:i:s') . "]" . "\r\n" . $key . ':';
    }

    /**
     *
     */
    protected function setDefaultParams(){
        $this->options = $this->config->getConfig('Printer\File\Add');
    }
}