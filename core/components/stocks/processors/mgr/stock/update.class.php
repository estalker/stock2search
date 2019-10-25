<?php
/**
 * @package stock
 * @subpackage processors
 */
class StockUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'Stock';
    public $languageTopics = array('stocks:default');
    public $objectType = 'stocks.stock';
}
return 'StockUpdateProcessor';