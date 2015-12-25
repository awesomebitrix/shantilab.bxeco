<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class shantilab_bxecho extends CModule
{
    private $eventManager;
    public function __construct()
    {
        $this->eventManager = \Bitrix\Main\EventManager::getInstance();

        $arModuleVersion = array();
        
        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        
        $this->MODULE_ID = 'shantilab.bxecho';
        $this->MODULE_NAME = Loc::getMessage('SHANTILAB_BXECHO_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('SHANTILAB_BXECHO_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('SHANTILAB_BXECHO_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'http://shantilab.ru';
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->eventManager->registerEventHandlerCompatible('main', 'OnProlog', 'shantilab.bxecho', '\Shantilab\BxEcho\Events', 'autoload');
        $this->installDB();
    }

    public function doUninstall()
    {
        $this->eventManager->unRegisterEventHandler('main', 'OnProlog', 'shantilab.bxecho', '\Shantilab\BxEcho\Events', 'autoload');
        $this->uninstallDB();
        ModuleManager::unregisterModule($this->MODULE_ID);
    }

    public function installDB()
    {

    }

    public function uninstallDB()
    {

    }
}
