<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var $arResult array
 */

$APPLICATION->SetTitle("Список моделей производителя - " . $arResult['VARS']['BRAND']);

$rollbackButton = new \Bitrix\UI\Buttons\Button([
    "color" => \Bitrix\UI\Buttons\Color::PRIMARY,
    "click" => new \Bitrix\UI\Buttons\JsHandler(
        "rollback"
    ),
    "text" => "Назад"
]);

\Bitrix\UI\Toolbar\Facade\Toolbar::addButton($rollbackButton);

?>

<?php
$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    array(
        "GRID_ID" => $arResult['GRID_ID'],
        "COLUMNS" => $arResult['COLUMNS'],
        "ROWS" => $arResult['ROWS'],
        "NAV_OBJECT" => $arResult['NAV'],
        "AJAX_MODE" => "Y",
        "PAGE_SIZES" => [
            ["NAME" => "3", "VALUE" => "3"],
            ["NAME" => "2", "VALUE" => "2"],
            ["NAME" => "1", "VALUE" => "1"],
        ],
        "TOTAL_ROWS_COUNT" => $arResult['ROWS_COUNT'],
        'AJAX_OPTION_JUMP' => 'N',
        'SHOW_CHECK_ALL_CHECKBOXES' => false,
        "SHOW_ROW_CHECKBOXES" => false,
        'SHOW_ROW_ACTIONS_MENU' => false,
        'SHOW_GRID_SETTINGS_MENU' => true,
        'SHOW_NAVIGATION_PANEL' => true,
        'SHOW_PAGINATION' => true,
        'SHOW_SELECTED_COUNTER' => false,
        'SHOW_TOTAL_COUNTER' => true,
        'SHOW_PAGESIZE' => true,
        'ALLOW_COLUMNS_SORT' => true,
        'ALLOW_COLUMNS_RESIZE' => true,
        'ALLOW_HORIZONTAL_SCROLL' => true,
        'ALLOW_SORT' => true,
        'ALLOW_PIN_HEADER' => true,
        'AJAX_OPTION_HISTORY' => 'N',
    ),
    false
);
?>
