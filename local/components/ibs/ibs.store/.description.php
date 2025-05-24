<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => "Магазин ноутбуков IBS",
    "DESCRIPTION" => "Компонент для отображения каталога ноутбуков с брендами, моделями и деталями",
    "CACHE_PATH" => "Y",
    "PATH" => [
        "ID" => "ibs",
        "NAME" => "IBS Компоненты",
        "CHILD" => [
            "ID" => "ibs_store",
            "NAME" => "Магазин ноутбуков",
        ],
    ],
];