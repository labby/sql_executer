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
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
} //defined( 'LEPTON_PATH' )
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	} //( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) )
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	} //file_exists( $root . '/framework/class.secure.php' )
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

$module_directory = 'sqlexecuter';
$module_name = 'SQL Executer';
$module_function = 'tool';
$module_version = '1.0.0';
$module_platform = '4.x';
$module_delete =  true;
$module_author = 'CMS-LAB';
$module_license = '<a href="http://cms-lab.com/_documentation/sql-executer/license.php" target="_blank">GNU General Public License</a>';
$module_description = 'This admintool allows you to maintain your database';
$module_home = 'http://www.cms-lab.com/';
$module_guid = '5f5c6c6d-ef3e-4202-904c-6d2f9aa01dda';

?>