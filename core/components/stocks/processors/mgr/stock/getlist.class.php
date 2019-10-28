<?php
/**
 * Get a list of Stocks
 *
 * @package stocks
 * @subpackage processors
 */
class StockGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'Stock';
    public $languageTopics = array('stocks:default');
    public $defaultSortField = 'name';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'stocks.stock';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'name:LIKE' => '%'.$query.'%',
                'OR:vendor:LIKE' => '%'.$query.'%',
                'OR:number:LIKE' => '%'.$query.'%',
            ));
        }
        return $c;
    }
}
return 'StockGetListProcessor';