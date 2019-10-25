<?php
/**
 * Adds modActions and modMenus into package
 *
 * @package doodles
 * @subpackage build
 */

$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'doodles',
    'namespace' => 'doodles',
    'action' => 'home',
    'parent' => 'components',
    'description' => 'doodles.desc',
    'icon' => 'images/icons/plugin.gif',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
),'',true,true);

unset($menus);

return $menu;