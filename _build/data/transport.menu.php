<?php
/**
 * Adds modActions and modMenus into package
 *
 * @package stocks
 * @subpackage build
 */

$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'stocks',
    'namespace' => 'stocks',
    'action' => 'home',
    'parent' => 'components',
    'description' => 'stocks.desc',
    'icon' => 'images/icons/plugin.gif',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
),'',true,true);

unset($menus);

return $menu;