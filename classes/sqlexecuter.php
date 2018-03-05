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

class sqlexecuter extends LEPTON_abstract
{
	public $all_sql = array();	
	public $database = 0;
	public $admin = 0;
	public $addon_color = 'blue';
	public $action = LEPTON_URL.'/modules/sqlexecuter/';	
	public $action_url = ADMIN_URL . '/admintools/tool.php?tool=sqlexecuter';	

	public static $instance;

	public function initialize() 
	{
		$this->database = LEPTON_database::getInstance();		
		$this->init_tool();			
	}
	
	public function init_tool( $sToolname = '' )
	{
		$this->admin = LEPTON_admin::getInstance();
		//get all queries
		$this->all_sql = array();
		$this->database->execute_query(
			"SELECT * FROM ".TABLE_PREFIX."mod_sqlexecuter " ,
			true,
			$this->all_sql,
			true
		);

	}

} // end of class
?>