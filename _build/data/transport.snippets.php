<?php
/**
 * @package stocks
 * @subpackage build
 */
function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = trim(str_replace(array('<?php','?>'),'',$o));
    return $o;
}
$snippets = array();

/* course snippets */
$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'Stocks',
    'description' => 'Displays a list of Stocks.',
    'snippet' => getSnippetContent($sources['elements'].'snippets/snippet.stocks.php'),
),'',true,true);
$properties = include $sources['data'].'properties/properties.stocks.php';
$snippets[1]->setProperties($properties);
unset($properties);

return $snippets;