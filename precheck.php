<?php

/**
 *  @module         SQL-Executer
 *  @version        see info.php of this module
 *  @authors        cms-lab
 *  @copyright      2013-2014 cms-lab 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH'))
{
    include(LEPTON_PATH . '/framework/class.secure.php');
}
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while (($level < 10) && (!file_exists($root . '/framework/class.secure.php')))
    {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root . '/framework/class.secure.php'))
    {
        include($root . '/framework/class.secure.php');
    }
    else
    {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include class.secure.php

// Checking Requirements
$PRECHECK['LEPTON_VERSION'] = array('VERSION' => '1.3.3', 'OPERATOR' => '>=');

$PRECHECK['ADDONS']      = array(
    'lib_twig' => array('VERSION' => '0.1.16.1', 'OPERATOR' => '>='),
)

?>