<?php
IncludeModuleLangFile(__FILE__);

class ibs_shop extends \CModule
{
    public $MODULE_ID = 'ibs.shop';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    public function __construct()
    {
        $arModuleVersion = [];
        include(dirname(__FILE__) . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = GetMessage('IBS_SHOP_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('IBS_SHOP_MODULE_DESC');
        $this->PARTNER_NAME = GetMessage('IBS_SHOP_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('IBS_SHOP_PARTNER_URI');
    }

    private function getInstallationFolderName()
    {
        // local | bitrix
        return end(explode('/', dirname(__DIR__, 3)));
    }

    public function InstallFiles($arParams = [])
    {
        $folder = $this->getInstallationFolderName();
        CopyDirFiles($_SERVER['DOCUMENT_ROOT']."/$folder/modules/".$this->MODULE_ID.'/files', $_SERVER['DOCUMENT_ROOT']."/local", true, true);
        return true;
    }

    public function UnInstallFiles()
    {
        return true;
    }


    public function InstallDatabase()
    {
        \Bitrix\Main\Loader::includeModule($this->MODULE_ID);
        return true;
    }

    public function UnInstallDatabase()
    {
        \Bitrix\Main\Loader::includeModule($this->MODULE_ID);
        return true;
    }

    public function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        $this->InstallDatabase();
        $this->InstallFiles();
    }

    public function DoUninstall()
    {
        \Bitrix\Main\Loader::includeModule($this->MODULE_ID);
        $this->UnInstallDatabase();
        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
    }
}

?>
