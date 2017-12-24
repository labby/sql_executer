<?php

/**
 *  @module         SQL-Executer
 *  @version        see info.php of this module
 *  @authors        CMS-LAB
 *  @copyright      2013-2017 cms-lab 
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




// create new table
	$table = TABLE_PREFIX .'mod_sqlexecuter';
	$database->query("CREATE TABLE `$table` (
		`id` INT NOT NULL auto_increment,
		`name` VARCHAR(32) NOT NULL,
		`code` LONGTEXT NOT NULL ,
		`description` TEXT NOT NULL,
		`modified_when` INT NOT NULL default '0',
		`modified_by` INT NOT NULL default '0',
		`active` INT NOT NULL default '0',
		`admin_edit` INT NOT NULL default '0',
		`admin_view` INT NOT NULL default '0',
		`show_wysiwyg` INT NOT NULL default '0',
		`comments` TEXT NOT NULL,
		PRIMARY KEY ( `id` )
		)"
	);
	
	$database->query("INSERT INTO `".TABLE_PREFIX."mod_sqlexecuter` (name, code, description, active, comments  ) 
					VALUES ('example', 'UPDATE `lep_pages` SET `language` = \"EN\" where `language` = \"EN\";', 'set language', '1', 'runs pages table')");	
	
	// check for errors
if ($database->is_error()) {
 echo $datbase->get_error();
}



// create the new permissions table
$table = TABLE_PREFIX .'mod_sqlexecuter_permissions';
$database->query("CREATE TABLE `$table` (
	`id` INT(10) UNSIGNED NOT NULL,
	`edit_groups` VARCHAR(50) NOT NULL,
	`view_groups` VARCHAR(50) NOT NULL,
	PRIMARY KEY ( `id` )
	)"
);

	// check for errors
if ($database->is_error()) {
 echo $datbase->get_error();
}

// create the settings table
$table = TABLE_PREFIX .'mod_sqlexecuter_settings';
$database->query("CREATE TABLE `$table` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`attribute` VARCHAR(50) NOT NULL DEFAULT '0',
	`value` VARCHAR(50) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	)"
);

	// check for errors
if ($database->is_error()) {
 echo $datbase->get_error();
}

// insert settings
$database->query("INSERT INTO `".TABLE_PREFIX ."mod_sqlexecuter_settings` (`id`, `attribute`, `value`) VALUES
(1, 'Delete sql', '1'),
(2, 'Add sql', '1'),
(3, 'Modify sql', '1'),
(4, 'Manage perms', '1');
");



?>