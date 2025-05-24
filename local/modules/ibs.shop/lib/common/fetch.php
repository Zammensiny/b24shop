<?php

namespace Ibs\Shop\Common;

class Fetch
{
    public function fetchAll(string $tableClass, array $params): array
    {
        $result = $tableClass::getList($params);
        $data = [];
        while ($item = $result->fetch()) {
            $data[] = $item;
        }
        return $data;
    }

    public function fetchOne(string $tableClass, array $params): ?array
    {
        return $tableClass::getList($params)->fetch() ?: null;
    }
}