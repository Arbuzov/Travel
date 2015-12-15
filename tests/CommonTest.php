<?php
class CommonTest extends PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $from = 'A';
        $to = 'C';
        $priceList = array(
            array('from'=>'A', 'to'=>'B', 'price'=>3),
            array('from'=>'A', 'to'=>'D', 'price'=>3),
            array('from'=>'A', 'to'=>'F', 'price'=>6),
            array('from'=>'B', 'to'=>'C', 'price'=>80),
            array('from'=>'B', 'to'=>'D', 'price'=>80),
            array('from'=>'B', 'to'=>'E', 'price'=>80),
            array('from'=>'C', 'to'=>'H', 'price'=>80)
        );
        $result = Travel_TaskWrapper::findRoute($from, $to, $priceList);
        $this->assertEquals(83, $result['price']);
        $this->assertEquals(array('A','B','C'), $result['path']);
    }
    
    /**
     * @expectedException Travel_BadPriceException
     */
    public function testNoWay()
    {
        $from = 'A';
        $to = 'C';
        $priceList = array(
            array('from'=>'A', 'to'=>'B', 'price'=>3),
            array('from'=>'A', 'to'=>'D', 'price'=>3),
            array('from'=>'A', 'to'=>'F', 'price'=>6),
            array('from'=>'B', 'to'=>'D', 'price'=>80),
            array('from'=>'B', 'to'=>'E', 'price'=>80),
            array('from'=>'C', 'to'=>'H', 'price'=>80)
        );
        Travel_TaskWrapper::findRoute($from, $to, $priceList);
    }
    
    public function testExtendWay()
    {
        $from = 'A';
        $to = 'C';
        $priceList = array(
            array('from'=>'A', 'to'=>'B', 'price'=>3),
            array('from'=>'A', 'to'=>'D', 'price'=>3),
            array('from'=>'A', 'to'=>'F', 'price'=>6),
            array('from'=>'B', 'to'=>'D', 'price'=>80),
            array('from'=>'B', 'to'=>'E', 'price'=>80),
            array('from'=>'C', 'to'=>'H', 'price'=>80)
        );
        $routeSearch = new Travel_Route($priceList);
        $routeSearch->addRoute('B', 'C', 80);
        $result = $routeSearch->search($from, $to);
        $this->assertEquals(83, $result['price']);
    }
    
    /**
     * @expectedException Travel_BadPriceException
     */
    public function testBadInput()
    {
        $from = 'A';
        $to = 'C';
        $priceList = array(
            array('from'=>'A', 'to'=>'B', 'price'=>3),
            array('from'=>'A', 'to'=>'D', 'price'=>3),
            array('from'=>'A', 'price'=>6),
            array('from'=>'B', 'to'=>'C', 'price'=>80),
            array('from'=>'B', 'to'=>'D', 'price'=>80),
            array('from'=>'B', 'to'=>'E', 'price'=>80),
            array('from'=>'C', 'to'=>'H', 'price'=>80)
        );
        $result = Travel_TaskWrapper::findRoute($from, $to, $priceList);
        $this->assertEquals(83, $result['price']);
    }
    
    /**
     * @expectedException Travel_BadPriceException
     */
    public function testExtendBadPrice()
    {
        $from = 'A';
        $to = 'C';
        $priceList = array(
            array('from'=>'A', 'to'=>'B', 'price'=>3),
            array('from'=>'A', 'to'=>'D', 'price'=>3),
            array('from'=>'A', 'to'=>'F', 'price'=>6),
            array('from'=>'B', 'to'=>'D', 'price'=>80),
            array('from'=>'B', 'to'=>'E', 'price'=>80),
            array('from'=>'C', 'to'=>'H', 'price'=>80)
        );
        $routeSearch = new Travel_Route($priceList);
        $routeSearch->addRoute('B', 'C', 'даром');
        $result = $routeSearch->search($from, $to);
        $this->assertEquals(83, $result['price']);
    }
    
    public function testException()
    {
        try {
            $from = 'A';
            $to = 'C';
            $priceList = array(
                array('from'=>'A', 'to'=>'B', 'price'=>3),
                array('from'=>'A', 'to'=>'D', 'price'=>3),
                array('from'=>'A', 'price'=>6),
                array('from'=>'B', 'to'=>'C', 'price'=>80),
                array('from'=>'B', 'to'=>'D', 'price'=>80),
                array('from'=>'B', 'to'=>'E', 'price'=>80),
                array('from'=>'C', 'to'=>'H', 'price'=>80)
            );
            $result = Travel_TaskWrapper::findRoute($from, $to, $priceList);
            $this->assertEquals(83, $result['price']);
        } catch (Travel_BadPriceException $e) {
            $this->assertRegexp('/pricelist incorrect/', $e->__toString());
        }

    }
    
}