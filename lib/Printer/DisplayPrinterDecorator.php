<?php
namespace Shantilab\BxEcho\Printer;

use Shantilab\BxEcho\Config;

/**
 * Class DisplayPrinterDecorator
 * @package Shantilab\BxEcho\Printer
 */
class DisplayPrinterDecorator implements PrinterDecoratorInterface
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var
     */
    public $data;
    /**
     * @var Config
     */
    protected $config;

    /**
     * DisplayPrinterDecorator constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->config = new Config();
        $this->setDefaultParams();

        $this->data = $data;

        $tag = $this->options['htmlTag'];

        ob_start();

        if ($tag)
            echo "<$tag>";

        $this->format();

        if ($tag)
            echo "</$tag>";

        echo ob_get_clean();
    }

    /**
     * @param $key
     * @return string
     */
    protected function getHtmlForKey($key)
    {
        return '<b style="color: green;font-weight: bold;">' . $key . '</b>:<br/>';
    }

    /**
     * @return bool|string
     */
    protected function getHtmlForBottomItem()
    {
        if (!$this->options['deviderSymb']) return false;

        return '<br/>' . str_repeat($this->options['deviderSymb'], intval($this->options['deviderCnt']));
    }

    /**
     *
     */
    public function format()
    {
        foreach($this->data as $key => $var){
            echo $this->getHtmlForKey($key);

            if (!function_exists($this->options['printFunction']))
                throw new \Shantilab\BxEcho\Exceptions\FunctionNotFoundException($this->options['printFunction']);

            $this->options['printFunction']($var);
            echo $this->getHtmlForBottomItem();
            echo '<br/>';
        }
    }

    /**
     *
     */
    protected function setDefaultParams(){
        $this->options = $this->config->getConfig('Printer\Display\Add');
    }
}