<?php
namespace Shantilab\BxEcho\Printer;

use Shantilab\BxEcho\Config;

/**
 * Class BasePrinter
 * @package Shantilab\BxEcho\Printer
 */
abstract class BasePrinter{
    /**
     * @var array
     */
    public $options = [];
    /**
     * @var null
     */
    protected $data;
    /**
     * @var Config
     */
    protected $config;

    /**
     * BasePrinter constructor.
     * @param null $data
     * @param array $options
     */
    public function __construct($data = null, array $options = [])
    {
        $this->data = $data;
        $this->config = new Config();
        $this->setDefaultOptions();
        $this->setOptions($options);

        return $this->data;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options){
        $this->options = $options + $this->options;
    }

    /**
     * @return null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *
     */
    public function setDefaultOptions(){
        $type = str_replace('Printer', '', array_pop(explode('\\', get_called_class())));
        $params = $this->config->getConfig('Printer' . '\\' . $type);
        unset($params['Add']);
        $this->options = $params + $this->options;
    }
}
