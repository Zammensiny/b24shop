# Модуль ibs.shop для Битрикс

## Установка модуля

1. Поместите папку `ibs.shop` в `/local/modules/`
2. В админке перейдите:  
   **Маркетплейс → Установленные решения**  
3. Найдите модуль "ibs.shop" и нажмите "Установить"

## ORM-модели (таблицы)

Модуль работает с 5 основными сущностями:

```php
Ibs\Shop\Model\ManufacturerTable - производители
  (ID, NAME)

Ibs\Shop\Model\ModelTable - модели ноутбуков  
  (ID, NAME, MANUFACTURER_ID)

Ibs\Shop\Model\LaptopTable - ноутбуки  
  (ID, NAME, YEAR, PRICE, MODEL_ID)

Ibs\Shop\Model\OptionTable - характеристики  
  (ID, NAME)

Ibs\Shop\Model\LaptopOptionTable - связка ноутбуков и характеристик  
  (LAPTOP_ID, OPTION_ID)
```

## Публичная часть

Главный компонент доступен по адресу:
/store/

## Структура страниц

/store/ - Список брендов

/store/{brand}/ - Модели выбранного бренда

/store/{brand}/{model}/ - Ноутбуки модели

/store/{brand}/{model}/{notebook}/ - Детальная карточка
