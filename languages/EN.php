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
 	'actions' => 'Actions',
 	'active' => 'Active',
	'Add sql' => 'Add SQL',    
 	'Back to overview' => 'Back to overview',   
 	'Create new' => 'Create new',  
	'Delete' => 'Delete',
	'Delete sql' => 'Delete sql',  
 	'description' => 'Description',
	'edit_sql'	=> 'Edit SQL ',  
 	'list_queries' => 'List Queries',		
//	'Manage permissions' => 'Manage permissions',
	'Manage perms' => 'Manage permissions',
	'Manage global permissions' => 'Manage global permissions',      
	'marked' => 'marked',
	'Modify' => 'Modify',
	'Modify sql' => 'Modify sql',
 	'name' => 'Name',          
 	'no_sqls' => 'No sql found',
	'not_active' => 'not aktive',
	'Permissions' => 'Permissions',
	'Permissions saved' => 'Permissions saved',   
	'Please enter a name!' => 'Please enter a name!',
	'Please mark some sql to delete' => 'Please mark some sql to delete',
	'record_deleted' => 'Record deleted',
	'record_saved' => 'Record saved',
 	'run' => 'Execute',
 	'run_ok' => 'SQL Query executed succesfull',
 	'run_false' => 'SQL Query executed not succesfull',	
	'Save and Back' => 'Save and Back',           
 	'Size' => 'Size',
 	'SQL not active' => 'SQL Statement is not active',
	'toggle_saved' => 'toggle saved',		
 	'The SQL was saved' => 'The SQL was saved',
	'Unable to delete SQL: {{id}}' => 'Unable to delete SQL: {{id}}',
	'Use' => 'Use',
	'Valid' => 'Valid',
	'View groups' => 'view groups',        
 	'You have entered no code!' => 'You have entered no code!',
 	'You dont have the permission to do this' => 'You dont have the permission to do this'  


);

?>