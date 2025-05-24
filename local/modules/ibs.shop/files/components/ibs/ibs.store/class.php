<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Grid\Options;
use Bitrix\Main\UI\PageNavigation;
use Ibs\Shop\Common\Router;
use Ibs\Shop\Helper\Strings;

\CModule::includeModule('ibs.shop');

class IbsStoreComponent extends \CBitrixComponent
{
    protected array $httpVars = [];
    protected string $page = '';
    protected string $path = '';

    protected array $resultData = [];
    protected array $resultSort = [];
    protected object $resultNav;
    public string $gridId = '';

    protected array $columnsMap = [
        'index' => [
            ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true],
            ['id' => 'NAME', 'name' => 'Название', 'sort' => 'NAME', 'default' => true],
        ],
        'brand' => [
            ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true],
            ['id' => 'NAME', 'name' => 'Название', 'sort' => 'NAME', 'default' => true],
        ],
        'model' => [
            ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true],
            ['id' => 'NAME', 'name' => 'Название', 'sort' => 'NAME', 'default' => true],
            ['id' => 'YEAR', 'name' => 'Год выпуска', 'sort' => 'YEAR', 'default' => true],
            ['id' => 'PRICE', 'name' => 'Цена', 'sort' => 'PRICE', 'default' => true],
        ],
    ];

    /*-- Установить id грида --*/

    public function setGridId(): void
    {
        $this->gridId = match ($this->page) {
            'brand' => 'ibs_grid_store_brand',
            'model' => 'ibs_grid_store_model',
            default => 'ibs_grid_store',
        };
    }

    /*-- Сортировка --*/

    public function prepareSort(): void
    {
        $grid = new Options($this->gridId);
        $sortOptions = $grid->GetSorting();
        $this->resultSort = $sortOptions["sort"] ?? ['ID' => 'desc'];
    }

    /*-- Навигация --*/

    public function prepareNav(): void
    {
        $gridOptions = new Options($this->gridId);
        $navParams = $gridOptions->GetNavParams();
        $pageSize = $navParams['nPageSize'] ?? 20;

        $nav = new PageNavigation($this->gridId);
        $nav->allowAllRecords(true)
            ->setPageSize($pageSize)
            ->initFromUri();

        $this->resultNav = $nav;
    }

    /*-- Колонки --*/

    public function getColumns(): array
    {
        return $this->columnsMap[$this->page] ?? [];
    }

    /*-- Данные --*/

    protected function fetchAll(string $tableClass, array $params): array
    {
        $result = $tableClass::getList($params);
        $data = [];
        while ($item = $result->fetch()) {
            $data[] = $item;
        }
        return $data;
    }

    protected function fetchOne(string $tableClass, array $params): ?array
    {
        return $tableClass::getList($params)->fetch() ?: null;
    }

    public function getData(): void
    {
        switch ($this->page) {
            case 'index':

                $this->resultData = $this->fetchAll(\Ibs\Shop\Model\ManufacturerTable::class, [
                    'select' => ['ID', 'NAME'],
                    'order' => $this->resultSort,
                ]);
                break;

            case 'brand':

                if (empty($this->httpVars['BRAND'])) {
                    $this->resultData = [];
                    break;
                }
                $manufacturer = $this->fetchOne(\Ibs\Shop\Model\ManufacturerTable::class, [
                    'filter' => ['=NAME' => $this->httpVars['BRAND']],
                    'select' => ['ID'],
                ]);

                if (!$manufacturer) {
                    $this->resultData = [];
                    break;
                }

                $this->resultData = $this->fetchAll(\Ibs\Shop\Model\ModelTable::class, [
                    'filter' => ['=MANUFACTURER_ID' => $manufacturer['ID']],
                    'select' => ['ID', 'NAME'],
                    'order' => $this->resultSort,
                ]);
                break;

            case 'model':

                if (empty($this->httpVars['BRAND']) || empty($this->httpVars['MODEL'])) {
                    $this->resultData = [];
                    break;
                }

                $manufacturer = $this->fetchOne(\Ibs\Shop\Model\ManufacturerTable::class, [
                    'filter' => ['=NAME' => $this->httpVars['BRAND']],
                    'select' => ['ID'],
                ]);

                if (!$manufacturer) {
                    $this->resultData = [];
                    break;
                }

                $model = $this->fetchOne(\Ibs\Shop\Model\ModelTable::class, [
                    'filter' => [
                        '=NAME' => $this->httpVars['MODEL'],
                        '=MANUFACTURER_ID' => $manufacturer['ID'],
                    ],
                    'select' => ['ID'],
                ]);

                if (!$model) {
                    $this->resultData = [];
                    break;
                }

                $this->resultData = $this->fetchAll(\Ibs\Shop\Model\LaptopTable::class, [
                    'filter' => ['=MODEL_ID' => $model['ID']],
                    'select' => ['ID', 'NAME', 'YEAR', 'PRICE'],
                    'order' => $this->resultSort,
                ]);
                break;

            case 'notebook':

                if (empty($this->httpVars['NOTEBOOK'])) {
                    $this->resultData = [];
                    break;
                }

                $notebookName = $this->httpVars['NOTEBOOK'];

                $laptop = $this->fetchOne(\Ibs\Shop\Model\LaptopTable::class, [
                    'filter' => ['=NAME' => $notebookName],
                    'select' => ['ID', 'NAME', 'YEAR', 'PRICE', 'MODEL_ID'],
                ]);

                if (!$laptop) {
                    $this->resultData = [];
                    break;
                }

                $laptopOptions = $this->fetchAll(\Ibs\Shop\Model\LaptopOptionTable::class, [
                    'filter' => ['=LAPTOP_ID' => $laptop['ID']],
                    'select' => ['OPTION_ID_VAL' => 'OPTION.ID', 'OPTION_NAME_VAL' => 'OPTION.NAME'],
                    'runtime' => [
                        new \Bitrix\Main\ORM\Fields\Relations\Reference(
                            'OPTION',
                            \Ibs\Shop\Model\OptionTable::class,
                            ['=this.OPTION_ID' => 'ref.ID'],
                            ['join_type' => 'INNER']
                        ),
                    ],
                ]);

                $this->resultData = [
                    'laptop' => $laptop,
                    'options' => $laptopOptions,
                ];
                break;

            default:
                $this->resultData = [];
                break;
        }
    }

    /*-- Строки --*/

    public function prepareData(): array
    {
        $rsDirContent = new CDBResult;
        $rsDirContent->InitFromArray($this->resultData);

        $pageSize = $this->resultNav->getLimit() > 0 ? $this->resultNav->getLimit() : $rsDirContent->nSelectedCount;
        $rsDirContent->NavStart($pageSize, true, $this->resultNav->getCurrentPage());

        $this->resultNav->setRecordCount($rsDirContent->selectedRowsCount());

        $this->arResult['NAV'] = $this->resultNav;
        $this->arResult['ROWS_COUNT'] = $this->resultNav->getRecordCount();

        $rows = [];
        while ($item = $rsDirContent->Fetch()) {

            $url = null;
            if ($this->page === 'model' && isset($item['ID'])) {

                $sefFolder = rtrim($this->arParams['SEF_FOLDER'], '/');
                $notebookTemplate = $this->arParams['SEF_URL_TEMPLATES']['notebook'] ?? 'detail/#NOTEBOOK#';
                $notebookCode = rawurlencode($item['NAME']);
                $url = $sefFolder . '/' . str_replace('#NOTEBOOK#', $notebookCode, $notebookTemplate);
            }

            $rowData = [
                'ID' => $item['ID'],
                'NAME' => Strings::prepareHtml($this->path, $item['NAME'], $url),
            ];

            if (isset($item['YEAR'])) {
                $rowData['YEAR'] = (int)$item['YEAR'];
            }
            if (isset($item['PRICE'])) {
                $rowData['PRICE'] = number_format((float)$item['PRICE'], 2, '.', '');
            }

            $rows[] = ['data' => $rowData];
        }
        return $rows;
    }

    /*-- Роутинг --*/

    public function setRouting(): void
    {
        $router = new Router();

        $this->page = $router->resolve(
            $this,
            $this->arParams['SEF_FOLDER'],
            $this->arParams['SEF_URL_TEMPLATES'],
            $this->arParams['VARIABLE_ALIASES'],
            $this->httpVars
        );

        $this->path = $GLOBALS['APPLICATION']->GetCurDir();

    }

    /*-- Вызов компонента --*/

    public function executeComponent(): void
    {
        $this->setRouting();
        $this->arResult['VARS'] = $this->httpVars;

        if ($this->page === 'notebook') {
            $this->getData();
            $this->arResult['DATA'] = $this->resultData;
        } else {
            $this->setGridId();
            $this->arResult['GRID_ID'] = $this->gridId;
            $this->arResult['COLUMNS'] = $this->getColumns();
            $this->prepareSort();
            $this->getData();
            $this->prepareNav();
            $this->arResult['ROWS'] = $this->prepareData();
        }

        $this->includeComponentTemplate($this->page);
    }
}
