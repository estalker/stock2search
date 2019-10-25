<?php
/**
 * @package stocks
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'tpl',
        'desc' => 'prop_stocks.tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'rowTpl',
        'lexicon' => 'stocks:properties',
    ),
    array(
        'name' => 'sort',
        'desc' => 'prop_stocks.sort_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'name',
        'lexicon' => 'stocks:properties',
    ),
    array(
        'name' => 'dir',
        'desc' => 'prop_stocks.dir_desc',
        'type' => 'list',
        'options' => array(
            array('text' => 'prop_stocks.ascending','value' => 'ASC'),
            array('text' => 'prop_stocks.descending','value' => 'DESC'),
        ),
        'value' => 'DESC',
        'lexicon' => 'stocks:properties',
    ),
);
return $properties;