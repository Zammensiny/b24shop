<?php

use Ibs\Shop\Model\ManufacturerTable;
use Ibs\Shop\Model\ModelTable;
use Ibs\Shop\Model\LaptopTable;
use Ibs\Shop\Model\OptionTable;
use Ibs\Shop\Model\LaptopOptionTable;

use Bitrix\Main\Localization\Loc;

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
        $this->MODULE_NAME = Loc::getMessage('IBS_SHOP_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::GetMessage('IBS_SHOP_MODULE_DESC');
        $this->PARTNER_NAME = Loc::GetMessage('IBS_SHOP_PARTNER_NAME');
        $this->PARTNER_URI = Loc::GetMessage('IBS_SHOP_PARTNER_URI');
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

        ManufacturerTable::createTable();
        ModelTable::createTable();
        LaptopTable::createTable();
        OptionTable::createTable();
        LaptopOptionTable::createTable();

        include_once __DIR__ . '/../tools/seed.php';

        return true;
    }

    public function UnInstallDatabase()
    {
        \Bitrix\Main\Loader::includeModule($this->MODULE_ID);

        ManufacturerTable::dropTable();
        ModelTable::dropTable();
        LaptopTable::dropTable();
        OptionTable::dropTable();
        LaptopOptionTable::dropTable();
        return true;
    }

    public function DoInstall()
    {
        global $APPLICATION;

        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        /*-- Step 1 --*/

        if ($request->getPost('step') !== '2') {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('IBS_SHOP_MODULE_NAME') . ' - ' . Loc::getMessage('IBS_SHOP_STEP_TITLE'),
                __DIR__ . '/step.php'
            );
            return;
        }

        /*-- Step 2 --*/

        RegisterModule($this->MODULE_ID);

        if ($request->getPost('delete_tables') === 'Y') {
            $this->UnInstallDatabase();
        }

        $this->InstallDatabase();
        $this->InstallFiles();
    }

    public function DoUninstall()
    {
        \Bitrix\Main\Loader::includeModule($this->MODULE_ID);

        global $APPLICATION;

        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        /*-- Step 1 --*/

        if ($request->getPost('step') !== '2') {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('IBS_SHOP_MODULE_NAME') . ' - ' . Loc::getMessage('IBS_SHOP_UNSTEP_TITLE'),
                __DIR__ . '/unstep.php'
            );
            return;
        }

        /*-- Step 2 --*/

        if ($request->getPost('delete_tables') === 'Y') {
            $this->UnInstallDatabase();
        }

        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
    }
}

?>
