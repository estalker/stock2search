<?php
/**
 * @package stock
 * @subpackage processors
 */
class StockRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'Stock';
    public $languageTopics = array('stocks:default');
    public $objectType = 'stocks.stock';
}
return 'StockRemoveProcessor';