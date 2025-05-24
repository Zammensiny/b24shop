<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentParameters = [
    "PARAMETERS" => [
        "SEF_MODE" => [
            "NAME" => Loc::getMessage("IBS_STORE_SEF_MODE"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
            "REFRESH" => "Y",
        ],
        "SEF_FOLDER" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("IBS_STORE_SEF_FOLDER"),
            "TYPE" => "STRING",
            "DEFAULT" => "/store",
        ],
        "SEF_URL_TEMPLATES" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("IBS_STORE_SEF_URL_TEMPLATES"),
            "TYPE" => "CUSTOM",
            "DEFAULT" => [
                "index" => "index.php",
                "brand" => "#BRAND#/",
                "model" => "#BRAND#/#MODEL#/",
                "notebook" => "detail/#NOTEBOOK#",
            ],
        ],
    ],
];
