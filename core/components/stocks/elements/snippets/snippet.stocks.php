<?php
/**
 * @package stocks
 */
$dood = $modx->getService('stocks','Stocks',$modx->getOption('stocks.core_path',null,$modx->getOption('core_path').'components/stocks/').'model/stocks/',$scriptProperties);
if (!($dood instanceof Stocks)) return '';

/* setup default properties */
$tpl = $modx->getOption('tpl',$scriptProperties,'rowTpl');
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* build query */
$c = $modx->newQuery('Stock');
$c->sortby($sort,$dir);
$stocks = $modx->getCollection('Stock',$c);

/* iterate */
$output = '<table class="table table-bordered table-fixed table-striped">';
foreach ($stocks as $stock) {
    $stockArray = $stock->toArray();
    $output .= $dood->getChunk($tpl,$stockArray);
}
$output .= '</table>';

return $output;