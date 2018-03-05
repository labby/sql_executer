<?php

/**
 *  @module         SQL-Executer
 *  @version        see info.php of this module
 *  @authors        CMS-LAB
 *  @copyright      2013-2018 cms-lab 
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

$debug = true;

if (true === $debug) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}

if(isset ($_GET['tool'])) {
	$toolname = $_GET['tool'];
} else {
	die('[1]');
}

// get instance of functions file
$oSQF = sqlexecuter_functions::getInstance();

if(isset ($_GET['tool']) && (empty($_POST)) ) {
	$oSQF->list_sql();	
} elseif(isset ($_POST['job']) && ($_POST['job']== 'show_info') ) {
	$oSQF->show_info();
} elseif(isset ($_POST['toggle']) ) {
	$oSQF->toggle_active( $_POST['toggle'] );
} elseif(isset ($_POST['execute_sql']) ) {
	$oSQF->execute_sql( $_POST['execute_sql'] );
} elseif(isset ($_POST['edit_sql']) && ($_POST['edit_sql']!= '') ) {
	$oSQF->edit_sql($_POST['edit_sql']);
} elseif(isset ($_POST['cancel']) ) {
	$oSQF->list_sql();	
} elseif(isset ($_POST['save_sql']) && ($_POST['save_sql']!= '') ) {
	$oSQF->save_sql($_POST['save_sql']);
} elseif(isset ($_POST['delete_sql']) && ($_POST['delete_sql']!= '') ) {
	$oSQF->delete_sql($_POST['delete_sql']);
}


?>