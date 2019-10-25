<?php
/**
 * Stocks Connector
 *
 * @package stocks
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('stocks.core_path',null,$modx->getOption('core_path').'components/stocks/');
require_once $corePath.'model/stocks/stocks.class.php';
$modx->stocks = new Stocks($modx);

$modx->lexicon->load('stocks:default');

/* handle request */
$path = $modx->getOption('processorsPath',$modx->stocks->config,$corePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));