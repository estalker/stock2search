<?php
require_once dirname(dirname(__FILE__)) . '/model/doodles/doodles.class.php';
/**
 * @package doodles
 * @subpackage controllers
 */
abstract class DoodlesManagerController extends modExtraManagerController {
    /** @var Doodles $doodles */
    public $doodles;
    public function initialize() {
        $this->doodles = new Doodles($this->modx);

        $this->addCss($this->doodles->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->doodles->config['jsUrl'].'mgr/doodles.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Doodles.config = '.$this->modx->toJSON($this->doodles->config).';
        });
        </script>');
        return parent::initialize();
    }
    public function getLanguageTopics() {
        return array('doodles:default');
    }
    public function checkPermissions() { return true;}
}
/**
 * @package doodles
 * @subpackage controllers
 */
class IndexManagerController extends DoodlesManagerController {
    public static function getDefaultController() { return 'home'; }
}

/**
 * @package doodles
 * @subpackage controllers
 */
class DoodlesHomeManagerController extends DoodlesManagerController {
    public function process(array $scriptProperties = array()) {

    }
    public function getPageTitle() { return $this->modx->lexicon('doodles'); }
    public function loadCustomCssJs() {
        $this->addJavascript($this->doodles->config['jsUrl'].'mgr/widgets/doodles.grid.js');
        $this->addJavascript($this->doodles->config['jsUrl'].'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->doodles->config['jsUrl'].'mgr/sections/index.js');
    }
    public function getTemplateFile() { return $this->doodles->config['templatesPath'].'home.tpl'; }
}
