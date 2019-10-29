<?php
/**
 * @package catalogfill
 * @subpackage processors
 */

@ini_set("upload_max_filesize","30M");
@ini_set("post_max_size","30M");
@ini_set("max_execution_time","1200"); //20 min.
@ini_set("max_input_time","1200"); //20 min.
@ini_set('memory_limit', '256M');
@ini_set('auto_detect_line_endings',1);
@set_time_limit(0);
@date_default_timezone_set('Europe/Moscow');
@setlocale (LC_ALL, 'ru_RU.UTF-8');

ini_set('display_errors',1);
error_reporting(E_ALL);

$modx->getService('lexicon','modLexicon');
$modx->lexicon->load($modx->config['manager_language'].':stocks:default');

$data = json_decode($scriptProperties['data'], true);

require_once MODX_CORE_PATH."components/stocks/model/stocksimport.class.php";
$sto_imp = new StocksImporter($modx);


$sto_imp->filesList();

$imp_file = '';
$datetime = '';
foreach ($sto_imp->filesList() as $key => $value) { 
    if ($sto_imp->getExtension($value['name']) == 'csv' && $value['datetime'] > $datetime) {          
        $imp_file = $value['name'];
        $datetime = $value['datetime'];
    }
}

//импорт товаров
if (empty($imp_file)) return $modx->error->failure($modx->lexicon('stocks_mess_no_file'));

$skip = $data['skip'];
$is_first = isset($data['is_first']) ? $data['is_first'] : 0;
$out = array();
    
    
//Изменение значений полей перед импортом
if($is_first){
        //Очищаем лог ошибок
        $log_file = $modx->getOption(xPDO::OPT_CACHE_PATH).'logs/error.log';
        if (file_exists($log_file)) {
            $cacheManager= $modx->getCacheManager();
            $cacheManager->writeFile($log_file,' ');
        }

	$sto_imp->deleteAll();	
	$sto_imp->clearAutoIncrement();


        $start_string = !empty($data['current_string']) && is_numeric($data['current_string']) ? $data['current_string'] : 0;
        return $modx->error->success('',array('pos'=>$start_string,'lines_count'=>$start_string+1));
	exit;
}
    
$out = $sto_imp->csv_import($imp_file,$skip);

return $modx->error->success('',$out);
