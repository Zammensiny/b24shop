<?php
use Bitrix\Main\Loader;
use Ibs\Shop\Model\ManufacturerTable;
use Ibs\Shop\Model\ModelTable;
use Ibs\Shop\Model\LaptopTable;
use Ibs\Shop\Model\OptionTable;
use Ibs\Shop\Model\LaptopOptionTable;

Loader::includeModule('ibs.shop');

LaptopOptionTable::getEntity()->getConnection()->truncateTable(LaptopOptionTable::getTableName());
OptionTable::getEntity()->getConnection()->truncateTable(OptionTable::getTableName());
LaptopTable::getEntity()->getConnection()->truncateTable(LaptopTable::getTableName());
ModelTable::getEntity()->getConnection()->truncateTable(ModelTable::getTableName());
ManufacturerTable::getEntity()->getConnection()->truncateTable(ManufacturerTable::getTableName());

$manufacturers = [
    ['name' => 'Apple'],
    ['name' => 'Lenovo'],
    ['name' => 'Dell'],
];

$models = [
    ['name' => 'MacBook Pro 14', 'manufacturer' => 'Apple'],
    ['name' => 'MacBook Air M2', 'manufacturer' => 'Apple'],
    ['name' => 'ThinkPad X1 Carbon', 'manufacturer' => 'Lenovo'],
    ['name' => 'Legion 5 Pro', 'manufacturer' => 'Lenovo'],
    ['name' => 'XPS 13', 'manufacturer' => 'Dell'],
    ['name' => 'Inspiron 15', 'manufacturer' => 'Dell'],
];

$laptops = [
    ['name' => 'MacBook Pro 14 (2023)', 'year' => 2023, 'price' => 2499.99, 'model' => 'MacBook Pro 14'],
    ['name' => 'MacBook Air M2 (2022)', 'year' => 2022, 'price' => 1299.00, 'model' => 'MacBook Air M2'],
    ['name' => 'ThinkPad X1 Carbon Gen 11', 'year' => 2023, 'price' => 1800.00, 'model' => 'ThinkPad X1 Carbon'],
    ['name' => 'Legion 5 Pro RTX 4070', 'year' => 2023, 'price' => 2200.00, 'model' => 'Legion 5 Pro'],
    ['name' => 'Dell XPS 13 OLED', 'year' => 2023, 'price' => 2100.00, 'model' => 'XPS 13'],
    ['name' => 'Inspiron 15 Touch', 'year' => 2022, 'price' => 850.00, 'model' => 'Inspiron 15'],
];

$options = [
    'Touchscreen',
    'Backlit Keyboard',
    'Fingerprint Sensor',
    'Face Recognition',
    'Wi-Fi 6',
    'Bluetooth 5.2',
];

$laptopOptionMap = [
    'MacBook Pro 14 (2023)' => ['Backlit Keyboard', 'Fingerprint Sensor', 'Wi-Fi 6'],
    'MacBook Air M2 (2022)' => ['Backlit Keyboard', 'Wi-Fi 6'],
    'ThinkPad X1 Carbon Gen 11' => ['Backlit Keyboard', 'Fingerprint Sensor', 'Bluetooth 5.2'],
    'Legion 5 Pro RTX 4070' => ['Backlit Keyboard', 'Wi-Fi 6', 'Bluetooth 5.2'],
    'Dell XPS 13 OLED' => ['Touchscreen', 'Face Recognition', 'Wi-Fi 6'],
    'Inspiron 15 Touch' => ['Touchscreen', 'Wi-Fi 6'],
];

$manufacturerIds = [];
foreach ($manufacturers as $item) {
    $res = ManufacturerTable::add(['NAME' => $item['name']]);
    $manufacturerIds[$item['name']] = $res->getId();
}

$modelIds = [];
foreach ($models as $item) {
    $res = ModelTable::add([
        'NAME' => $item['name'],
        'MANUFACTURER_ID' => $manufacturerIds[$item['manufacturer']],
    ]);
    $modelIds[$item['name']] = $res->getId();
}

$laptopIds = [];
foreach ($laptops as $item) {
    $res = LaptopTable::add([
        'NAME' => $item['name'],
        'YEAR' => $item['year'],
        'PRICE' => $item['price'],
        'MODEL_ID' => $modelIds[$item['model']],
    ]);
    $laptopIds[$item['name']] = $res->getId();
}

$optionIds = [];
foreach ($options as $optName) {
    $res = OptionTable::add(['NAME' => $optName]);
    $optionIds[$optName] = $res->getId();
}

foreach ($laptopOptionMap as $laptopName => $opts) {
    foreach ($opts as $optName) {
        LaptopOptionTable::add([
            'LAPTOP_ID' => $laptopIds[$laptopName],
            'OPTION_ID' => $optionIds[$optName],
        ]);
    }
}
