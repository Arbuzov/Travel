<?php
/**
 * Класс определяющий самый дешевый маршрут
 * по алгоритму Дейкстры
 * @author "arbuzov <info@whitediver.com>"
 *
 */
class Travel_Route
{
    
    /**
     * Оптимизированная для алгоритма Дейкстры
     * матрица поездок
     * @var array
     */
    private $_routes = array();
    
    /**
     * Прайс-лист на поезки
     * @var array
     */
    protected $_price = array();
    
    /**
     * Приоритетная очередь сильно упрощает
     * выборку узлов взвешенных по стоимости
     * @var SplPriorityQueue
     */
    private $_nodeQueue;
    
    /**
     * Массив сколько нам стоит прийти к узлу
     * @var array
     */
    private $_destinations = array();
    
    /**
     * Массив откуда мы приходим к узлу
     * @var array
     */
    private $_predecessors = array();
    
    /**
     * Конструктор получающий на вход прайс
     * @param array $price прайслист для поездок
     * @throws Travel_BadPriceException выбрасываем если данные не корректы
     */
    public function __construct($price)
    {
        $this->_price = $price;
        $this->_makeRoutes($this->_price);
    }
    
    /**
     * Добавляет запись в стоимость расписаний
     * @param string $from
     * @param string $to
     * @param double $price
     */
    public function addRoute($from, $to, $price)
    {
        if (!is_numeric($price)) {
            throw (new Travel_BadPriceException("не корректная цена поездки '$price'"));
        }
        $this->_price[] = array('from'=>$from, 'to'=>$to, 'price'=>$price);
        $this->_makeRoutes($this->_price);
    }
    
    /**
     * Поиск маршрута публичный метод
     * @param string $from
     * @param string $to
     * @return array
     * @throws Travel_BadPriceException выбрасываем если данных не хватает
     */
    public function search($from, $to)
    {
        $this->_fillQueue($from);
        $this->_shortestPath();
        $result = $this->_collectResult($from, $to);
        return $result;
    }
    
    /**
     * Метод заполняет матрицу стоимостей поездок
     * @param array $price
     */
    protected function _makeRoutes($price)
    {
        $result = array();
        foreach ($price as $row) {
            if (!isset($row['from'])||!isset($row['to'])||!isset($row['price'])||!is_numeric($row['price'])) {
                throw (new Travel_BadPriceException("не корректная строка с ценой"));
            }
            $result[$row['from']][$row['to']] = $row['price'];
            $result[$row['to']][$row['from']] = $row['price'];
        }
        $this->_routes = $result;
    }
    
    /**
     * Заполняет очередь узлов
     * по умолчанию непросчитанные значения 
     * устанавливаем в бесконечность
     * @param string $from
     */
    protected function _fillQueue($from)
    {
        $this->_destinations = array();
        
        $this->_predecessors = array();
        
        $this->_nodeQueue = new SplPriorityQueue();
        
        foreach ($this->_routes as $v => $adj) {
            $this->_destinations[$v] = INF;
            $pi[$v] = null;
            foreach ($adj as $w => $price) {
                $this->_nodeQueue->insert($w, $price);
            }
        }
        $this->_destinations[$from] = 0;
    }
    
    /**
     * Возвращаемся из конечной точки а начало
     * посчитав стоимость и путь
     * @param string $from
     * @param string $to
     * @throws Travel_BadPriceException выбрасываем не нашли маршрута
     */
    protected function _collectResult($from, $to)
    {
        $result = array(
            'price'=>0,
            'path'=>array()
        );
        $pathStack = new SplStack();
        $u = $to;
        $dist = 0;
        while (isset($this->_predecessors[$u]) && $this->_predecessors[$u]) {
            $pathStack->push($u);
            $result['price'] += $this->_routes[$u][$this->_predecessors[$u]];
            $u = $this->_predecessors[$u];
        }
        
        if ($pathStack->isEmpty()) {
            throw (new Travel_BadPriceException("Нет пути из $from в $to"));
        } else {
            $pathStack->push($from);
            foreach ($pathStack as $item) {
                $result['path'][] = $item;
            }
        }
        return $result;
    }
    
    /**
     * Поиск кратчайшего пути
     * @see https://www.youtube.com/watch?v=UA6aV1XJCGg
     */
    private function _shortestPath()
    {
        while (!$this->_nodeQueue->isEmpty()) {
            $u = $this->_nodeQueue->extract();
            if (!empty($this->_routes[$u])) {
                foreach ($this->_routes[$u] as $v => $cost) {
                    $newCost = $this->_destinations[$u] + $cost;
                    if ($newCost < $this->_destinations[$v]) {
                        $this->_destinations[$v] = $newCost;
                        $this->_predecessors[$v] = $u;
                    }
                }
            }
        }
    }
}