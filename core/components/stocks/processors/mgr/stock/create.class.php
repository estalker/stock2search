<?php
/**
 * @package stocks
 * @subpackage processors
 */
class StockCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'Stock';
    public $languageTopics = array('stocks:default');
    public $objectType = 'stocks.stock';

    public function beforeSave() {
        $name = $this->getProperty('name');

        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('stocks.stock_err_ns_name'));
        } else if ($this->doesAlreadyExist(array('name' => $name))) {
            $this->addFieldError('name',$this->modx->lexicon('stocks.stock_err_ae'));
        }
        return parent::beforeSave();
    }
}
return 'StockCreateProcessor';