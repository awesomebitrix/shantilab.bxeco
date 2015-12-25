<?php
namespace Shantilab\BxEcho;

use \Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 * @package Shantilab\BxEcho
 */
class Config
{
    /**
     * @var string
     */
    protected $file = '';

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->file = __DIR__ . '/../settings.yaml';
    }

    /**
     * @param null $type
     * @return array
     */
    public function getConfig($type = null)
    {
        $params = Yaml::parse(file_get_contents($this->file));

        if ($type){
            $ar = explode('\\', $type);
            foreach($ar as $val){
                $params = $params[$val];
            }
        }

        return $params;
    }
}