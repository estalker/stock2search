<?php
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/stock.class.php');
class Stock_mysql extends Stock {
    public function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>