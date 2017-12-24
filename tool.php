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

require_once(LEPTON_PATH.'/modules/sql_executer/functions.php');
    
global $parser;
global $loader;

if (!is_object($parser) ) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

$loader->prependPath( dirname(__FILE__)."/templates/" );

$parser->addGlobal('ADMIN_URL', ADMIN_URL);
$parser->addGlobal('IMGURL', LEPTON_URL . '/modules/sql_executer/img');
$parser->addGlobal('DOCURL', LEPTON_URL . '/modules/sql_executer/docs/readme.php?url='.LEPTON_URL.'/modules/sql_executer/docs');
$parser->addGlobal('action', ADMIN_URL . '/admintools/tool.php?tool=sql_executer');
$parser->addGlobal('TEXT', $TEXT);

global $settings;
$settings = get_settings();

/**
 *	Load Language file
 */
$lang = dirname(__FILE__)."/languages/".LANGUAGE.".php";
include( file_exists($lang) ? $lang : dirname(__FILE__)."/languages/EN.php" );

$parser->addGlobal('MOD_SQLEXECUTER', $MOD_SQLEXECUTER);

if ( isset( $_REQUEST[ 'del' ] ) && is_numeric( $_REQUEST[ 'del' ] ) )
{
    $_POST[ 'markedsql' ] = $_REQUEST[ 'del' ];
    $_REQUEST[ 'delete' ]     = 1;
}
if ( isset( $_REQUEST[ 'toggle' ] ) && is_numeric( $_REQUEST[ 'toggle' ] ) )
{
    toggle_active( $_REQUEST[ 'toggle' ] );
    list_sql();
}
elseif ( isset( $_REQUEST[ 'execute' ] ) && is_numeric( $_REQUEST[ 'execute' ] ) )
{
    $message = execute_sql( $_REQUEST[ 'execute' ] );
    list_sql( $message );
}
elseif ( isset( $_REQUEST[ 'add' ] ) )
{
    edit_sql( 'new' );
}
elseif ( isset( $_REQUEST[ 'edit' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    edit_sql( $_REQUEST[ 'edit' ] );
}
elseif ( isset( $_REQUEST[ 'delete' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    delete_sql();
}
elseif ( isset( $_REQUEST[ 'datafile' ] ) && is_numeric( $_REQUEST[ 'datafile' ] ) )
{
    edit_datafile( $_REQUEST[ 'datafile' ] );
}
elseif ( isset( $_REQUEST[ 'perms' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    manage_perms();
}
else
{
    list_sql();
}

?>