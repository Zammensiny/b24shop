<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Extension;

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var $arResult array
 */

$APPLICATION->SetTitle("Детальная карточка ноутбука - " . htmlspecialchars($arResult['VARS']['NOTEBOOK']));

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

<div class="container mt-4">
    <div class="card mx-auto">
        <div class="card-body">
            <h3 class="card-title"><?= $arResult['DATA']['laptop']['NAME'] ?? 'Без названия' ?></h3>

            <ul class="list-group list-group-flush mt-3">
                <li class="list-group-item">
                    <strong>Год
                        выпуска:</strong> <?= $arResult['DATA']['laptop']['YEAR'] ?? 'не указан' ?>
                </li>
                <li class="list-group-item">
                    <strong>Цена:</strong> <?= number_format($arResult['DATA']['laptop']['PRICE'] ?? 0, 2, ',', ' ') ?>
                    ₽
                </li>
                <li class="list-group-item">
                    <strong>Характеристики:</strong>
                    <?php if (!empty($arResult['DATA']['options'])) { ?>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($arResult['DATA']['options'] as $option) { ?>
                                <li><?= $option['OPTION_NAME_VAL'] ?></li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <em>Отсутствуют</em>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</div>
