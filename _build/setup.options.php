<?php
/**
 * Build the setup options form.
 *
 * @package stocks
 * @subpackage build
 */
/* set some default values */

/* get values based on mode */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $output = '<h2>Stocks Installer</h2>
        <p>Thanks for installing Stocks! Please review the setup options below before proceeding.</p><br />';
        break;
    case xPDOTransport::ACTION_UPGRADE:
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return $output;