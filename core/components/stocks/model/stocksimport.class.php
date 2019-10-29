<?php

/**
 * Catalogfill
 *
 * Catalogfill cart class
 *
 * @author Andchir <andchir@gmail.com>
 * @package catalogfill
 * @version 2.4.10+
 */

class StocksImporter {
    
    public $message = '';
    public $categories = array();
    public $articuls = array();
    public $keys = array(array(),array());
    public $values = array();
    public $table_fields = array('id');
    public $config = array();
    
    /**
     *
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX &$modx){
        
        $this->modx =& $modx;
        
        $this->config = array_merge(array(
            "corePath" => $this->modx->getOption('core_path').'components/stocks/',
            "assetsUrl" => $this->modx->getOption('assets_url') . 'components/stocks/',
            "coreUrl" => str_replace($this->modx->getOption('base_path'), '', $this->modx->getOption('core_path')) . 'components/stocks/',
            "controllersPath" => $this->modx->getOption('core_path') . 'components/stocks/controllers/',
            "connectorUrl" => $this->modx->getOption('assets_url') . 'components/stocks/connector.php',
            "templatesPath" => $this->modx->getOption('core_path') . 'components/stocks/templates/',
            "impFilesPath" => 'assets/components/stocks/files/',
            "table_name" => $this->modx->config['table_prefix']."stocks",
            "table_prefix" => $this->modx->config['table_prefix'],
	    "table_columns" => array('vendor','number','price','count','name','filedate'),
            "files_import_dir" => $this->modx->getOption('assets_path')."components/catalogfill/files/",
            "include_captions" => true,
            "batch_import" => 5000,
        ));

        
        $this->modx->addPackage('stocks', $this->config['corePath'] . 'model/');

        $modelpath = $this->modx->getOption('core_path') . 'components/' . $this->config['packageName'] . '/model/';
        $loaded = $this->modx->loadClass($this->config['className'], $modelpath.$this->config['packageName'].'/');
        $added = $this->modx->addPackage($this->config['packageName'], $modelpath);
        if($added){
        	$mapFile = $modelpath . $this->config['packageName'] .'/'. $this->modx->config['dbtype'] . '/' .strtolower($this->config['className']). '.map.inc.php';
                if(file_exists($mapFile)){
                    include $mapFile;
                    $metaMap = $xpdo_meta_map[ucfirst($this->config['className'])];
                    $this->config['table_name'] = $this->modx->config['table_prefix'].$metaMap['table'];
                    $this->table_fields = array_merge($this->table_fields,array_keys($metaMap['fields']));

                    if(!isset($this->modx->map[$this->config['className']])){
                        $this->modx->map[$this->config['className']] = array();
                        $this->modx->map[$this->config['className']]['table'] = $metaMap['table'];
                    }

                }
	}


  
    }


    
    /**
    *
    * @param string $str
    * @return boolean
    */
    public function isUTF8($str){
        if (!function_exists('mb_convert_encoding')) {
            return false;
        }
        if($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32"))
          return true;
        else
          return false;
    }

    /**
     * Сбрасываем auto_increment ID
     * 
     */
    public function clearAutoIncrement(){
        
        $stmt = $this->modx->query("SELECT MAX(`id`) FROM `".$this->config['table_name']."`");
        $id_content_max = (integer) $stmt->fetch(PDO::FETCH_COLUMN);
        $stmt->closeCursor();
        if(!$id_content_max) $id_content_max = 0;
        $this->modx->query("ALTER TABLE `".$this->config['table_name']."` AUTO_INCREMENT = ".($id_content_max+1));
        
    }


public function deleteAll()
{
       $stmt = $this->modx->query("DELETE FROM `".$this->config['table_name']."`");
        $stmt->closeCursor();
}
	
    
    /**
     * Возвращает время работы PHP в секундах
     *
     */
    public function microtime_float(){
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    
    
    /**
    *
    * @param float $number
    * @return float
    */
    public function numberFormat($number){
        $output = str_repeat(array(' ',','),array('','.'),$number);
        if($this->config['excepDigitGroup']==true){
            $output = number_format($number,(floor($number) == $number ? 0 : 2),'.',' ');
        }
        return $output;
    }
    
    


    /**
     * Импорт данных из CSV файла
     *
     * @param string $file_name
     * @return array
     */
    public function csv_import($file_name, $skip=0){
        
        $out = array('pos'=>0,'lines_count'=>0);
        
    
        $csv_file = $this->config['files_import_dir'].$file_name;
        $lines_count = count(file($csv_file));
        
        $out['lines_count'] =  $lines_count;
        if($this->config['include_captions']) $out['lines_count']--;
        
        if(file_exists($csv_file)){

	    $filetime = filemtime($csv_file);
            $fileHandler = fopen($csv_file, "r");

            $count = 0;
            
            while ($line = fgetcsv($fileHandler, filesize($csv_file), ";")){
                
                $count++;
                if($this->config['include_captions'] && $count == 1){ continue; }
                
                if($out['pos'] < $skip){ $out['pos']++; continue; }

		array_push($line,date("Y-m-d H:i:s", $filetime));

		foreach($line as $cls => $vls)
		{
		  $line[$cls] = $this->isUTF8($vls) ? trim($vls) : trim(iconv('cp1251','UTF-8',$vls));
		}                
		                
		$insertArr = array_combine($this->config['table_columns'],$line);
                
                $insert = '';

                $insert = $this->insertToDB($insertArr);
                
                $out['pos']++;
                
                //Если есть ограничение, выходим из цикла
                if($this->config['batch_import'] && ($out['pos'] == $skip + $this->config['batch_import'])){
                    break;
                }
                
            }
            fclose($fileHandler);
    
        }
        
        return $out;
        
    }

    
    /**
     * Записываем данные в таблицы БД
     * 
     * @param array $insertArr
     * @return boolean
     */
    public function insertToDB($insertArr){
        
        $output = false;
        $upd_id = 0;
        $is_update = false;
        
        
            
            $sql = "
            INSERT INTO `".$this->config['table_name']."`
            (`".implode("`, `",array_keys($insertArr))."`)
            VALUES (".implode(",",array_fill(0,count($insertArr),'?')).")
            ";
            
            $stmt = $this->modx->prepare($sql);
            
            if($stmt && $stmt->execute(array_values($insertArr))){

                $stmt->closeCursor();

                $output = true;

            }else{
		print_r($stmt->errorInfo());		
                $this->modx->log(modX::LOG_LEVEL_ERROR, "stocksimport: ".implode(', ',$stmt->errorInfo()));
                $stmt->closeCursor();
                $output = false;

            }
        
        
        return $output;
        
    }

    
    /**
     * Составляем список файлов для импорта
     * 
     * @return array
     */
    public function filesList(){
        $output = array();
        $dir = opendir(realpath($this->config['files_import_dir']));
        while($f = readdir($dir)){
            if(is_file($this->config['files_import_dir'].$f)) $output[] = array('id'=>$this->config['files_import_dir'].$f,'name'=>$f,'datetime'=>filemtime($this->config['files_import_dir'].$f),'date'=>date("d.m.Y H:i:s.", filemtime($this->config['files_import_dir'].$f)));
        }
        closedir($dir);
        return $output;
    }
    
    
    /**
     * Get file extension
     * @param $filePath
     * @return string
     */
    public static function getExtension($filePath)
    {
        $temp_arr1 = $filePath ? explode(DIRECTORY_SEPARATOR, $filePath) : array();
        $temp_arr = count($temp_arr1) ? explode('.', end($temp_arr1)) : array();
        $ext = count($temp_arr) > 1 ? end($temp_arr) : '';
        return strtolower($ext);
    }
}

