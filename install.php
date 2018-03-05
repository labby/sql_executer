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

// check if older release is already installed
if(file_exists(LEPTON_PATH.'/modules/sql_executer/info.php')) {
	require_once LEPTON_PATH.'/modules/sqlexecuter/upgrade.php';
} else {
	// create new table
	$table_fields="
		`id` INT NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(32) NOT NULL default '',
		`description` TEXT NOT NULL,
		`comments` TEXT NOT NULL,	
		`code` LONGTEXT NOT NULL ,
		`active` INT NOT NULL default '0',	
		 PRIMARY KEY ( `id` )
	";
	LEPTON_handle::install_table("mod_sqlexecuter", $table_fields);

	// insert some default values
	$field_values="
	(NULL,'empty temp', 'empty temp table', 'if your ip is locked ', 'TRUNCATE lep_temp', 1)
	";
	LEPTON_handle::insert_values("mod_sqlexecuter", $field_values);
}
?>