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

        $this->arResult['GRID_ID'] = $this->gridId;

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
        $pageSize = $navParams['nPageSize'] ?? 3;
        $nav = new PageNavigation($this->gridId);
        $nav->allowAllRecords(true)
            ->setPageSize($pageSize)
            ->initFromUri();
        $this->resultNav = $nav;
    }

    /*-- Колонки --*/

    public function getColumns(): void
    {
        $columns = $this->columnsMap[$this->page] ?? [];
        $this->arResult['COLUMNS'] = $columns;
    }

    /*-- Данные --*/

    public function getData(): void
    {
        $fetchLib = new Ibs\Shop\Common\Fetch;

        switch ($this->page) {
            case 'index':

                $this->resultData = $fetchLib->fetchAll(\Ibs\Shop\Model\ManufacturerTable::class, [
                    'select' => ['ID', 'NAME'],
                    'order' => $this->resultSort,
                ]);
                break;

            case 'brand':

                if (empty($this->httpVars['BRAND'])) {
                    $this->resultData = [];
                    break;
                }
                $manufacturer = $fetchLib->fetchOne(\Ibs\Shop\Model\ManufacturerTable::class, [
                    'filter' => ['=NAME' => $this->httpVars['BRAND']],
                    'select' => ['ID'],
                ]);

                if (!$manufacturer) {
                    $this->resultData = [];
                    break;
                }

                $this->resultData = $fetchLib->fetchAll(\Ibs\Shop\Model\ModelTable::class, [
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

                $manufacturer = $fetchLib->fetchOne(\Ibs\Shop\Model\ManufacturerTable::class, [
                    'filter' => ['=NAME' => $this->httpVars['BRAND']],
                    'select' => ['ID'],
                ]);

                if (!$manufacturer) {
                    $this->resultData = [];
                    break;
                }

                $model = $fetchLib->fetchOne(\Ibs\Shop\Model\ModelTable::class, [
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

                $this->resultData = $fetchLib->fetchAll(\Ibs\Shop\Model\LaptopTable::class, [
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

                $laptop = $fetchLib->fetchOne(\Ibs\Shop\Model\LaptopTable::class, [
                    'filter' => ['=NAME' => $notebookName],
                    'select' => ['ID', 'NAME', 'YEAR', 'PRICE', 'MODEL_ID'],
                ]);

                if (!$laptop) {
                    $this->resultData = [];
                    break;
                }

                $laptopOptions = $fetchLib->fetchAll(\Ibs\Shop\Model\LaptopOptionTable::class, [
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

                $this->arResult['DATA'] = $this->resultData;

                break;

            default:
                $this->resultData = [];
                break;
        }

        if(!$this->resultData) {
            $this->page = '404';
        }

    }

    /*-- Строки --*/

    public function prepareData(): void
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

            $rowData = [
                'ID' => $item['ID'],
                'NAME' => Strings::prepareHtml($this->path, $item['NAME']),
            ];

            if (isset($item['YEAR'])) {
                $rowData['YEAR'] = (int)$item['YEAR'];
            }
            if (isset($item['PRICE'])) {
                $rowData['PRICE'] = number_format((float)$item['PRICE'], 2, '.', '');
            }

            $rows[] = ['data' => $rowData];
        }

        $this->arResult['ROWS'] = $rows;
    }

    /*-- Роутинг --*/

    public function setRouting(): void
    {
        $this->router = new Router();

        $this->page = $this->router->resolve(
            $this,
            $this->arParams['SEF_FOLDER'],
            $this->arParams['SEF_URL_TEMPLATES'],
            $this->arParams['VARIABLE_ALIASES'],
            $this->httpVars
        );

        $this->path = $GLOBALS['APPLICATION']->GetCurDir();
        $this->arResult['VARS'] = $this->httpVars;

    }

    /*-- Вызов компонента --*/

    public function executeComponent(): void
    {
        $this->setRouting();

        if ($this->page === 'notebook') {
            $this->getData();
        } else {
            $this->setGridId();
            $this->getColumns();
            $this->prepareSort();
            $this->getData();
            $this->prepareNav();
            $this->prepareData();
        }

        $this->includeComponentTemplate($this->page);
    }
}
