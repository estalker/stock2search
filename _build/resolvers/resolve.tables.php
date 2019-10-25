<?php
/**
 * Resolve creating custom db tables during install.
 *
 * @package stocks
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('stocks.core_path',null,$modx->getOption('core_path').'components/stocks/').'model/';
            $modx->addPackage('stocks',$modelPath);

            $manager = $modx->getManager();

            $manager->createObjectContainer('Stock');

            break;
        case xPDOTransport::ACTION_UPGRADE:
            break;
    }
}
return true;