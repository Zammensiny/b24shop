<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Магазин ноутбуков");

$APPLICATION->IncludeComponent(
    "ibs:ibs.store",
    "",
    [
        "SEF_MODE" => "Y",
        "SEF_FOLDER" => "/store",
        "SEF_URL_TEMPLATES" => [
            'index' => 'index.php',
            "brand" => "#BRAND#/",
            "model" => "#BRAND#/#MODEL#/",
            "notebook" => "detail/#NOTEBOOK#",
        ]
    ]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");