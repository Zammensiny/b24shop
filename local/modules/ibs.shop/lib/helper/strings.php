<?php

namespace Ibs\Shop\Helper;

class Strings
{
    public static function prepareHtml($path, $name)
    {
        if ($name) {
            $path = rtrim($path, '/');
            return '<a href="' . $path . '/' . $name . '/">' . htmlspecialcharsbx($name) . '</a>';
        } else {
            return 'Нет данных';
        }
    }

}