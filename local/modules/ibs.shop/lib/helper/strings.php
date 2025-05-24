<?php

namespace Ibs\Shop\Helper;

class Strings
{
    public static function prepareHtml($path, $name, $url = null)
    {
        if ($name) {
            $link = $url ?? rtrim($path, '/') . '/' . rawurlencode($name) . '/';
            return '<a href="' . htmlspecialcharsbx($link) . '">' . htmlspecialcharsbx($name) . '</a>';
        } else {
            return 'Нет данных';
        }
    }

}