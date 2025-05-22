<?php
declare(strict_types=1);

namespace Ibs\Shop\Model;

use Bitrix\Main\Application;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;

class LaptopOptionTable extends DataManager
{
    /**
     * @return string название таблицы
     */
    public static function getTableName(): string
    {
        return 'ibs_laptopshop_laptop_option';
    }
    /**
     * @return array массив полей таблицы
     */
    public static function getMap(): array
    {
        return [
            new Fields\IntegerField('LAPTOP_ID', [
                'primary' => true,
            ]),
            new Fields\IntegerField('OPTION_ID', [
                'primary' => true,
            ]),
            new Fields\Relations\Reference(
                'LAPTOP',
                LaptopTable::class,
                ['=this.LAPTOP_ID' => 'ref.ID']
            ),
            new Fields\Relations\Reference(
                'OPTION',
                OptionTable::class,
                ['=this.OPTION_ID' => 'ref.ID']
            ),
        ];
    }
    /**
     * Удалить все строки в таблице
     * @return bool
     */
    public static function deleteAll(): bool
    {
        try {
            $connection = self::getDbConnection();
            $connection->truncateTable(static::getTableName());
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Создать таблицу с названием из метода getTableName
     * @return bool
     */
    public static function createTable(): bool
    {
        try {
            $connection = self::getDbConnection();
            if ($connection->isTableExists(static::getTableName())) {
                return false;
            }
            static::getEntity()->createDbTable();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return Connection класс соединения с БД
     */
    public static function getDbConnection(): Connection
    {
        return Application::getInstance()->getConnection();
    }


    /**
     * Удалить таблицу целиком
     * @return bool
     */
    public static function dropTable(): bool
    {
        try {
            $connection = static::getDbConnection();
            if (!$connection->isTableExists(static::getTableName())) {
                return false;
            }
            $connection->dropTable(static::getTableName());
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}