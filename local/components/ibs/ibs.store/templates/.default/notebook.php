<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->SetTitle("Детальная карточка ноутбука");

\Bitrix\Main\UI\Extension::load("ui.bootstrap4");

?>

<div class="container mt-4">
    <div class="card" style="max-width: 600px; margin: auto;">
        <div class="card-body">
            <h3 class="card-title"><?= htmlspecialchars($arResult['NAME']) ?></h3>
            <ul class="list-group list-group-flush mt-3">
                <li class="list-group-item">
                    <strong>Год выпуска:</strong> <?= htmlspecialchars($arResult['YEAR'] ?? 'не указан') ?>
                </li>
                <li class="list-group-item">
                    <strong>Цена:</strong> <?= htmlspecialchars($arResult['PRICE'] ?? 'не указана') ?> ₽
                </li>
            </ul>
        </div>
    </div>
</div>
