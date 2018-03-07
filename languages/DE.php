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

$MOD_SQLEXECUTER = array(
 	'actions' => 'Aktionen',
 	'active' => 'Aktiv',
 	'description' => 'Beschreibung',
	'edit_sql'	=> 'SQL bearbeiten',
 	'list_queries' => 'Liste Abfragen',	
 	'name' => 'Name',       
 	'no_sqls' => 'Keine SQLs gefunden',
	'not_active' => 'Nicht aktiv',
	'record_deleted' => 'Datensatz gelöscht',
	'record_saved' => 'Datensatz gespeichert',
 	'run' => 'Ausführen',  
 	'run_ok' => 'SQL Query erfolgreich ausgeführt',
 	'run_false' => 'SQL Query nicht erfolgreich ausgeführt',
 	'sure' 	=> 'Bist du sicher?',	
	'toggle_saved' => 'Status umgestellt'
);

?>