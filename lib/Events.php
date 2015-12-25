<?php
namespace Shantilab\BxEcho;

/**
 * Class Events
 * @package Shantilab\BxEcho
 */
class Events{
    /**
     * @throws \Bitrix\Main\LoaderException
     */
    public function autoload(){
        \Bitrix\Main\Loader::IncludeModule('shantilab.bxecho');
    }
}