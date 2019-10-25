<?php
require_once dirname(dirname(__FILE__)) . '/model/stocks/stocks.class.php';
/**
 * @package stocks
 * @subpackage controllers
 */
abstract class StocksManagerController extends modExtraManagerController {
    /** @var Stocks $stocks */
    public $stocks;
    public function initialize() {
        $this->stocks = new Stocks($this->modx);

        $this->addCss($this->stocks->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->stocks->config['jsUrl'].'mgr/stocks.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Stocks.config = '.$this->modx->toJSON($this->stocks->config).';
        });
        </script>');
        return parent::initialize();
    }
    public function getLanguageTopics() {
        return array('stocks:default');
    }
    public function checkPermissions() { return true;}
}
/**
 * @package stocks
 * @subpackage controllers
 */
class IndexManagerController extends StocksManagerController {
    public static function getDefaultController() { return 'home'; }
}

/**
 * @package stocks
 * @subpackage controllers
 */
class StocksHomeManagerController extends StocksManagerController {
    public function process(array $scriptProperties = array()) {

    }
    public function getPageTitle() { return $this->modx->lexicon('stocks'); }
    public function loadCustomCssJs() {
        $this->addJavascript($this->stocks->config['jsUrl'].'mgr/widgets/stocks.grid.js');
        $this->addJavascript($this->stocks->config['jsUrl'].'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->stocks->config['jsUrl'].'mgr/sections/index.js');
    }
    public function getTemplateFile() { return $this->stocks->config['templatesPath'].'home.tpl'; }
}
