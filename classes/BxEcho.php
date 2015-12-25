<?php

use Shantilab\BxEcho\Checker\CheckerInterface;
use Shantilab\BxEcho\Config;

/**
 * Class BxEcho
 */
class BxEcho
{
    /**
     * @var
     */
    protected $container;
    /**
     * @var
     */
    protected $checker;
    /**
     * @var array
     */
    protected $options = [];
    /**
     * @var Config
     */
    protected $config;

    /**
     * BxEcho constructor.
     * @param null $var
     * @param null $name
     * @param array $options
     * @param CheckerInterface|null $checker
     */
    public function __construct($var = null, $name = null, $options = [], CheckerInterface $checker = null)
    {
        $this->config = new Config();

        if ($var)
            $this->add($var, $name);

        if ($options)
            $this->options = $options;

        if ($checker)
            $this->checker = $checker;
        else{
            $checkerClass = $this->getBindClasses('Checker');

            if (!class_exists($checkerClass)){
                throw new \Shantilab\BxEcho\Exceptions\ClassNotFoundException($checkerClass);
            }

            $this->checker = new $checkerClass();
        }
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options = []){
        $this->options = $this->options + $options;

        return $this;
    }

    /**
     * @param $var
     * @param null $name
     * @return $this
     */
    public function add($var, $name = null)
    {
        if ($name)
            $this->container[$name] = $var;
        else
            $this->container[] = $var;

        return $this;
    }

    /**
     * @param null $var
     * @param null $name
     * @param string $type
     * @param array $options
     */
    public function show($var = null, $name = null, $type = 'screen', $options = [])
    {
        $this->{'to' . ucfirst($type)}($var, $name, $options);
    }

    /**
     * @param null $var
     * @param null $name
     * @param array $options
     * @return $this
     */
    public function toScreen($var = null, $name = null, $options = []){
        if (!$this->check()) return $this;

        $displayClass = $this->getBindClasses('DisplayPrinter');

        if (!class_exists($displayClass)){
            throw new \Shantilab\BxEcho\Exceptions\ClassNotFoundException($displayClass);
        }

        $params = $this->getParamsToPrint($var, $name, $options);
        $printer = new $displayClass($params['data'], $params['options']);

        if ($printer->options['debugOnly']){
            global $APPLICATION;
            $APPLICATION->RestartBuffer();
        }

        if ($printer->options['prettyFormat']){
            $displayClassDecorator = $this->getBindClasses('DisplayPrinterDecorator');

            if (!class_exists($displayClassDecorator)){
                throw new \Shantilab\BxEcho\Exceptions\ClassNotFoundException($displayClassDecorator);
            }

            (new $displayClassDecorator($printer->getData()));
        }
        else
            $printer->fire();

        if ($printer->options['debugOnly'])
            $this->stop();

        return $this;
    }

    /**
     * @param null $var
     * @param null $name
     * @param array $options
     * @return $this
     */
    public function toConsole($var = null, $name = null, $options = []){
        if (!$this->check()) return $this;

        $consoleClass = $this->getBindClasses('ConsolePrinter');

        if (!class_exists($consoleClass)){
            throw new \Shantilab\BxEcho\Exceptions\ClassNotFoundException($consoleClass);
        }

        $params = $this->getParamsToPrint($var, $name, $options);
        $printer = new $consoleClass($params['data'], $params['options']);

        $printer->fire();

        return $this;
    }

    /**
     * @param null $var
     * @param null $name
     * @param array $options
     * @return $this
     */
    public function toFile($var = null, $name = null, $options = []){
        if (!$this->check()) return $this;

        $fileClass = $this->getBindClasses('FilePrinter');

        if (!class_exists($fileClass)){
            throw new \Shantilab\BxEcho\Exceptions\ClassNotFoundException($fileClass);
        }

        $params = $this->getParamsToPrint($var, $name, $options);
        $printer = new $fileClass($params['data'], $params['options']);

        if ($printer->options['prettyFormat']){
            $fileClassDecorator = $this->getBindClasses('FilePrinterDecorator');

            if (!class_exists($fileClassDecorator)){
                throw new \Shantilab\BxEcho\Exceptions\ClassNotFoundException($fileClassDecorator);
            }

            $data = (new $fileClassDecorator($printer->getData()))->data;

            $printer->fire($data);
        }
        else
            $printer->fire();

        return $this;
    }

    /**
     * @return $this
     */
    public function stop(){
        if (!$this->check()) return $this;

        die();
    }

    /**
     * @return bool
     */
    protected function check(){
        if ($this->checker->check())
            return true;

        return false;
    }

    /**
     * @param $var
     * @param $name
     * @param $options
     * @return array
     */
    protected function getParamsToPrint($var, $name, $options){
        if (!isset($var))
            $var = $this->container;
        else{
            if (isset($name, $var))
                $var = [$name => $var];
            else
                $var = [$var];
        }

        return [
            'data'    => $var,
            'options' => $this->options + $options
        ];
    }

    /**
     * @param null $class
     * @return array
     */
    protected function getBindClasses($class = null){
        $params =  $this->config->getConfig('classBindings');
        if ($class)
            $params = $params[$class];

        return $params;
    }
}
