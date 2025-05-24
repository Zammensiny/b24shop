<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\UI\Extension;

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var $arResult array
 */
$APPLICATION->SetTitle("404");

Extension::load("ui.bootstrap4");

$rollbackButton = new \Bitrix\UI\Buttons\Button([
    "color" => \Bitrix\UI\Buttons\Color::PRIMARY,
    "click" => new \Bitrix\UI\Buttons\JsHandler(
        "rollback"
    ),
    "text" => "Назад"
]);

\Bitrix\UI\Toolbar\Facade\Toolbar::addButton($rollbackButton);
?>

<div style="color: red">Товар или раздел не найден</div>


