<?php
namespace App\Utility\Helpers;

use Iterator;

class BasicArrayIterator implements Iterator
{
    protected $_arrayToTraverse; 

    public function __construct($aArray)
    {
        if (is_array($aArray)) {
            $this->_arrayToTraverse = $aArray;
        }
    }

    public function rewind()
    {
        reset($this->_arrayToTraverse);
    }

    public function current() 
    {
        return current($this->_arrayToTraverse);
    }

    public function key()
    {
        return key($this->_arrayToTraverse);
    }

    public function next() 
    {
        return next($this->_arrayToTraverse);
    }

    public function valid()
    {
        $valueToTest =key($this->_arrayToTraverse);
        $isValid = ($valueToTest !== null && $valueToTest !== false);
        return $isValid;
    }

}
?>