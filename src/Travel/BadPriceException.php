<?php
/**
 * Исключение для Travel_Route выбрасывается в случае когда
 * массив с данными недостаточен для решения задачи, то есть пуст
 * или не полон. Так же выбрасывается в 
 * том случае когда записи в массиве не корректны.
 * @author "arbuzov <info@whitediver.com>"
 * @package Travel пакет полезный при планировании
 * путешествий
 */
class Travel_BadPriceException extends Exception
{
    /**
     * В данном классе сообщение обязательно
     * @param string $message
     * @param number $code
     * @param Exception $previous
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString()
    {
        return __CLASS__ .
            "(pricelist incorrect): [{$this->code}]:".
            " {$this->message}\n";
    }
}