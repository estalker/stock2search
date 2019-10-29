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

/* parse search string (analog simple search) */
$searchIndex = 'search';
$searchString = $dood->parseSearchString($_REQUEST[$searchIndex]);

/* build query */
$c = $modx->newQuery('Stock');
$c->sortby($sort,$dir);
$c->where(array('number' => $searchString));
$stocks = $modx->getCollection('Stock',$c);
/* iterate */
$output = '';
if(!empty($stocks))
{
	$output = '<table class="table table-bordered table-fixed table-striped">'.
	'<tr><th>Марка</th><th>Номер</th><th>Цена</th><th>Кол-во</th><th>Наименование</th></tr>';
	foreach ($stocks as $stock) {
	    $stockArray = $stock->toArray();
	    $output .= $dood->getChunk($tpl,$stockArray);
	}
	$output .= '</table>';
}
return $output;