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
	'Add sql' => 'SQLs hinzuf&uuml;gen',    
 	'Back to overview' => 'Zur&uuml;ck zur &Uuml;bersicht',   
 	'Create new' => 'Neues SQL',  
	'Delete' => 'L&ouml;schen',
	'Delete sql' => 'SQLs l&ouml;schen',  
 	'description' => 'Beschreibung',
	'edit_sql'	=> 'SQL bearbeiten',
 	'list_queries' => 'Liste Abfragen',	
//	'Manage permissions' => 'Rechte verwalten',
	'Manage perms' => 'Rechte verwalten',	
	'Manage global permissions' => 'Globale Rechte verwalten',    
	'marked' => 'markierte',
	'Modify' => 'Bearbeiten',
	'Modify sql' => 'SQLs bearbeiten',   
 	'name' => 'Name',       
 	'no_sqls' => 'Keine SQLs gefunden',
	'not_active' => 'Nicht aktiv',
	'Permissions' => 'Rechte',
	'Permissions saved' => 'Rechte gespeichert',     
	'Please enter a name!' => 'Bitte einen Namen eingeben!',
	'Please mark some sql to delete' => 'Bitte SQL(s) zum L&ouml;schen markieren',
	'record_deleted' => 'Datensatz gelöscht',
	'record_saved' => 'Datensatz gespeichert',
 	'run' => 'Ausführen',  
 	'run_ok' => 'SQL Query erfolgreich ausgeführt',
 	'run_false' => 'SQL Query nicht erfolgreich ausgeführt',	
	'Save and Back' => 'Speichern und zur&uuml;ck',         
 	'Size' => 'Dateigr&ouml;sse',
 	'SQL not active' => 'SQL Statement ist nicht aktiv',
	'toggle_saved' => 'Status umgestellt',	
 	'The SQL was saved' => 'SQL gespeichert',
	'Unable to delete SQL: {{id}}' => 'Fehler beim L&ouml;schen von SQL: {{id}}',
	'Use' => 'Verwendung',
	'Valid' => 'Valide',
	'View groups' => 'Gruppen anschauen',        
 	'You have entered no code!' => 'Es wurde kein Code eingegeben!',
 	'You dont have the permission to do this' => 'Keine Berechtigung'  

);

?>