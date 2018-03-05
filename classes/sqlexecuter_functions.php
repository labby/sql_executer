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

class sqlexecuter_functions extends sqlexecuter
{
	public static $instance;
	
public function initialize() 
	{	
		parent::initialize();
		$this->admin = LEPTON_admin::getInstance();
	}		

public function list_sql() 
	{	
		$info = '';
		if(isset ($_SESSION['result_info'])) {
			foreach($_SESSION['result_info'][0] as $key =>$temp)
			{
			$info .= $key. " = ".$temp."<br />";
			}
		} 

		$problem = '';
		if(isset ($_SESSION['problem_info'])) {
			foreach($_SESSION['problem_info'][0] as $key =>$temp)
			{
			$problem .= $key. " = ".$temp."<br />";
			}
		} 		
		// data for twig template engine	
		$data = array(
			'oSQF'		=> $this,
			'info'		=> $info,
			'problem'	=> $problem,				
			'leptoken'	=> get_leptoken(),
			'help'	=> 'http://cms-lab.com/_documentation/sql-executer/readme.php'				
			);

		/**	
		 *	get the template-engine.
		 */
		$oTwig = lib_twig_box::getInstance();
		$oTwig->registerModule('sqlexecuter');
			
		echo $oTwig->render( 
			"@sqlexecuter/list.lte",	//	template-filename
			$data						//	template-data
		);
		if(isset ($_SESSION['result_info'])) { unset($_SESSION['result_info']);}	
		if(isset ($_SESSION['problem_info'])) { unset($_SESSION['problem_info']);}		
	}
	
public function show_info() 
	{
		// create links
		$support_link = "<a href=\"#\">NO Live-Support / FAQ</a>";	
		$readme_link = "<a href=\"http://cms-lab.com/_documentation/sql-executer/readme.php \" class=\"info\" target=\"_blank\">Readme</a>";

		// data for twig template engine	
		$data = array(
			'oSQF'			=> $this,
			'leptoken'	=> get_leptoken(),			
			'readme_link'	=> $readme_link,		
			'SUPPORT'		=> $support_link,		
			'image_url'		=> 'http://cms-lab.com/_documentation/media/sqlexecuter/sqlexecuter.jpg'
			);

		/**	
		 *	get the template-engine.
		 */
		$oTwig = lib_twig_box::getInstance();
		$oTwig->registerModule('sqlexecuter');
			
		echo $oTwig->render( 
			"@sqlexecuter/info.lte",	//	template-filename
			$data						//	template-data
		);		
		
	}
	

public function toggle_active( $id )   // Function to switch between active/inactive
	{
		$data = $this->database->get_one( "SELECT `active` FROM ".TABLE_PREFIX."mod_sqlexecuter WHERE id = ".$id." " );

		$new = ( $data == 1 ) ? 0 : 1;

		$this->database->simple_query( "UPDATE ".TABLE_PREFIX."mod_sqlexecuter SET active=".$new." WHERE id = ".$id." " );
		
		// Check if there is a db error, else success
		if($this->database->is_error()) {
			$this->admin->print_error($this->database->get_error());
		} else {
			$this->admin->print_success($this->language['toggle_saved'], ADMIN_URL.'/admintools/tool.php?tool=sqlexecuter');
		}		
	} 	

	
public function edit_sql( $id ) 
	{
		//get selected query
		$current_query = array();
		$this->database->execute_query(
			"SELECT * FROM ".TABLE_PREFIX."mod_sqlexecuter WHERE id = ".$id." " ,
			true,
			$current_query,
			false
		);
		
		// data for twig template engine	
		$data = array(
			'oSQF'	=> $this,
			'current_query'	=> $current_query,			
			'register_area' => edit_area::registerEditArea( 'code','sql'),				
			'leptoken'	=> get_leptoken()		
			);

		/**	
		 *	get the template-engine.
		 */
		$oTwig = lib_twig_box::getInstance();
		$oTwig->registerModule('sqlexecuter');
			
		echo $oTwig->render( 
			"@sqlexecuter/edit.lte",	//	template-filename
			$data						//	template-data
		);		
		
	}

public function save_sql( $id ) 
	{
		// check if we have to save fields or add a new entry
		if(isset($_POST['save_sql']) && $_POST['save_sql'] == '-1') {

			// insert some default values
			$this->database->simple_query( "INSERT INTO ".TABLE_PREFIX."mod_sqlexecuter VALUES(NULL,'new', 'new entry', '', '', 1)" );
			
			// Check if there is a db error, else success
			if($this->database->is_error()) {
				$this->admin->print_error($this->database->get_error());
			} else {
				$this->admin->print_success($this->language['record_saved'], ADMIN_URL.'/admintools/tool.php?tool=sqlexecuter');
			}		
		}
		
		if (isset($_POST['save_sql']) && $_POST['save_sql'] > '0') {

			// insert some default values
			$this->database->simple_query( "UPDATE ".TABLE_PREFIX."mod_sqlexecuter SET name = '".$_POST['name']."', description = '".$_POST['description']."', code = '".$_POST['code']."', comments = '".$_POST['comments']."' WHERE id = ".$_POST['save_sql']." " );
			
			// Check if there is a db error, else success
			if($this->database->is_error()) {
				$this->admin->print_error($this->database->get_error());
			} else {
				$this->admin->print_success($this->language['record_saved'], ADMIN_URL.'/admintools/tool.php?tool=sqlexecuter');
			}				
		}
	}
	
public function execute_sql( $id ) 
	{
		//get selected query
		$current_query = array();
		$this->database->execute_query(
			"SELECT * FROM ".TABLE_PREFIX."mod_sqlexecuter WHERE id = ".$id." " ,
			true,
			$current_query,
			false
		);

		$result = array();
		$this->database->execute_query(
			" ".$current_query['code']." ",
			true,
			$result,
			true				
		);
		
		$_SESSION['result_info'] = array();	
		$_SESSION['problem_info'] = array();	
		
		if (count($result) > 0) {
			if ($this->database->is_error()) {
				unset($_SESSION['result_info']);
				$_SESSION['problem_info'] = $this->database->get_error();				
				return $this->language['run_false']."<br />".$this->database->get_error();
			} else {
				$_SESSION['result_info'] = $result;
				unset($_SESSION['problem_info']);
				$this->admin->print_success($this->language['run_ok'], ADMIN_URL.'/admintools/tool.php?tool=sqlexecuter');
				}					
		} else {
			if ($this->database->is_error()) {
				$_SESSION['problem_info'] = $this->database->get_error();
				unset($_SESSION['result_info']);				
				return $this->language['run_false']."<br />".$this->database->get_error();				
			} else {
				unset($_SESSION['result_info']);
				unset($_SESSION['problem_info']);
				$this->admin->print_success($this->language['run_ok'], ADMIN_URL.'/admintools/tool.php?tool=sqlexecuter');
			}
		}							
	}
	
public function delete_sql( $id ) 
	{
		$this->database->simple_query( "DELETE FROM ".TABLE_PREFIX."mod_sqlexecuter WHERE id = ".$id." " );
			
		// Check if there is a db error, else success
		if($this->database->is_error()) {
			$this->admin->print_error($this->database->get_error());
		} else {
			$this->admin->print_success($this->language['record_deleted'], ADMIN_URL.'/admintools/tool.php?tool=sqlexecuter');
		}			
	}	
} // end of class
?>