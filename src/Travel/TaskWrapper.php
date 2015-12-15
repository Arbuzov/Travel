<?php
class Travel_TaskWrapper
{
    /**
     * Согласно заданию все нужно было сделать в одной
     * функции, пришлось делать враппер
     * @param string $from отправление
     * @param string $to назначение
     * @param array $price массив стоимостей
     * @return array возвращает стоимость и маршрут в формате
     *  array(
     *       'price'=>0,
     *      'path'=>array()
     *  );
     * @throws Travel_BadPriceException в том случае когда прай-слист пустой
     *  содержит не корректные данные или не достаточен для решения
     */
    public static function findRoute($from, $to, $price)
    {
        $routeSearch = new Travel_Route($price);
        return $routeSearch->search($from, $to);
    }
}