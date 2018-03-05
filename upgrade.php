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

// changes for L*4    
// remove tables
LEPTON_handle::drop_table('mod_sqlexecuter_settings');
LEPTON_handle::drop_table('mod_sqlexecuter_permissions');

// remove columns
$database->simple_query("ALTER TABLE ".TABLE_PREFIX."mod_sqlexecuter 
  DROP `modified_when`,
  DROP `modified_by`,
  DROP `admin_edit`,
  DROP `admin_view`,
  DROP `show_wysiwyg`
");  

// modify table
$database->simple_query("ALTER TABLE ".TABLE_PREFIX."mod_sqlexecuter MODIFY COLUMN `code` longtext AFTER `comments`"); 
$database->simple_query("ALTER TABLE ".TABLE_PREFIX."mod_sqlexecuter MODIFY COLUMN `active` int AFTER `code`");  

//modify addon entry
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'addons` SET `directory` =\'sqlexecuter\' WHERE `guid` =\'5f5c6c6d-ef3e-4202-904c-6d2f9aa01dda\'');	

// delete old directory
LEPTON_handle::delete_obsolete_directories('/modules/sql_executer');
?>