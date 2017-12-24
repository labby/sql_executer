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



/**
 * get a list of all sql and show them
 **/
function list_sql( $info = NULL )
{
    global $admin, $parser, $database, $settings, $MOD_SQLEXECUTER;

    // check for global read perms
    $groups = $admin->get_groups_id();

	$backups = 1;
    $rows = array();

    $fields = 't1.id, name, code, description, active, comments, view_groups, edit_groups';
    $query  = $database->query( "SELECT $fields FROM " . TABLE_PREFIX . "mod_sqlexecuter AS t1 LEFT OUTER JOIN " . TABLE_PREFIX . "mod_sqlexecuter_permissions AS t2 ON t1.id=t2.id ORDER BY name ASC" );

    if ( $query->numRows() )
    {
        while ( $sql_content = $query->fetchRow() )
        {
            // the current user needs global edit permissions, or specific edit permissions to see this
            if ( !is_allowed( 'modify_sql', $groups ) )
            {
                // get edit groups for this 
                if ( $sql_content[ 'edit_groups' ] )
                {
                    if ( $admin->get_user_id() != 1 && !is_in_array( $sql_content[ 'edit_groups' ], $groups ) )
                    {
                        continue;
                    }
                    else
                    {
                        $sql_content[ 'user_can_modify_this' ] = true;
                    }
                }
            }
            $comments = str_replace( array(
                "\r\n",
                "\n",
                "\r"
            ), '<br />', $sql_content[ 'comments' ] );
            if ( !strpos( $comments, "[[" ) ) //
            {
                $comments = '<span class="usage">' . $MOD_SQLEXECUTER[ 'Use' ] . ": [[" . $sql_content[ 'name' ] . "]]</span><br />" . $comments;
            }
            $sql_content[ 'comments' ] = $comments;

            array_push( $rows, $sql_content ); 
        }
    }
	
	$problem =  (strstr( $info, "Problem: ") != FALSE) ? 1 : 0;
		
    echo $parser->render( 
    	'modify.lte', 
    	array(
        'rows'       => $rows,
        'num_rows'	=> count($rows),
        'info'       => $info,
        'problem'	=> $problem,
        'can_delete' => ( is_allowed( 'Delete sql', $groups ) ? 1 : NULL ),
        'can_modify' => ( is_allowed( 'Modify sql', $groups ) ? 1 : NULL ),
        'can_perms'  => ( is_allowed( 'Manage perms', $groups ) ? 1 : NULL ),
        'can_add'    => ( is_allowed( 'Add sql', $groups ) ? 1 : NULL )
    ) );

} // end function list_sql()



/**
 *	delete a sql entry
 **/
function delete_sql()
{
    global $admin, $parser, $database, $MOD_SQLEXECUTER;

    $groups = $admin->get_groups_id();
    if ( !is_allowed( 'delete_sql', $groups ) )
    {
        $admin->print_error( $MOD_SQLEXECUTER[ "You don't have the permission to do this" ] );
    }

    $errors = array();

    // get all marked sql
    $marked = isset( $_POST[ 'markedsql' ] ) ? $_POST[ 'markedsql' ] : array();

    if ( isset( $marked ) && !is_array( $marked ) )
    {
        $marked = array(
             $marked
        );
    }

    if ( !count( $marked ) )
    {
        list_sql( $MOD_SQLEXECUTER[ 'Please mark some SQL to delete' ] );
        return; // should never be reached
    }

    foreach ( $marked as $id )
    {
        // get the name; needed to delete data file
        $query = $database->query( "SELECT name FROM " . TABLE_PREFIX . "mod_sqlexecuter WHERE id = '$id'" );
        $data  = $query->fetchRow();
        $database->query( "DELETE FROM " . TABLE_PREFIX . "mod_sqlexecuter WHERE id = '$id'" );
        if ( $database->is_error() )
        {
            $errors[] = sprintf($MOD_SQLEXECUTER[ 'Unable to delete sql: {{id}}'], array(
                 'id' => $id
            ) );
        }
        
        // look for a data file
        $file_names = array(
        	dirname( __FILE__ ) . '/data/' . $data[ 'name' ] . '.txt',
        	dirname( __FILE__ ) . '/data/' . strtolower( $data[ 'name' ] ) . '.txt',
        	dirname( __FILE__ ) . '/data/' . strtoupper( $data[ 'name' ] ) . '.txt'
        );
        foreach($file_names as $temp_file_name) {
			if ( file_exists( $temp_file_name) )
        	{
            	unlink( $temp_file_name );
        	}
        }
    }

    list_sql( implode( "<br />", $errors ) );
    return;

} // end function delete_sql()



/**
 * edit a sql
 **/
function edit_sql( $id )
{
    global $admin, $parser, $database, $MOD_SQLEXECUTER, $TEXT;

    $groups = $admin->get_groups_id();

    if ( $id == 'new' && !is_allowed( 'add_sql', $groups ) )
    {
        $admin->print_error( $MOD_SQLEXECUTER[ "You don't have the permission to do this" ] );
    }
    else
    {
        if ( !is_allowed( 'modify_sql', $groups ) )
        {
            $admin->print_error( $MOD_SQLEXECUTER[ "You don't have the permission to do this" ] );
        }
    }

    $problem  = NULL;
    $info     = NULL;
    $problems = array();

    if ( isset( $_POST[ 'cancel' ] ) )
    {
        return list_sql();
    }

    if ( $id != 'new' )
    {
        $query        = $database->query( "SELECT * FROM " . TABLE_PREFIX . "mod_sqlexecuter WHERE id = '$id'" );
        $data         = $query->fetchRow();
    }
    else
    {
        $data = array(
            'name' => '',
            'active' => 1,
            'description' => '',
            'code' => '',
            'comments' => ''
        );
    }

    if ( isset( $_POST[ 'save' ] ) || isset( $_POST[ 'save_and_back' ] ) )
    {
        
            
            if ( $admin->get_post( 'name' ) == '' )
            {
                $problems[] = $MOD_SQLEXECUTER['Please enter a name!'];
            }
            if ( $admin->get_post( 'code' ) == '' )
            {
                $problems[] = $MOD_SQLEXECUTER['You have entered no code!'];
            }

            if ( count( $problems ) == 0 )
            {
                $continue      = true;
                $title         = addslashes( $admin->get_post( 'name' ) );
                $active        = $admin->get_post( 'active' );
                $show_wysiwyg  = $admin->get_post( 'show_wysiwyg' );
                $description   = addslashes( $admin->get_post( 'description' ) );
                
                $content       = $admin->get_post( 'code' );
                $comments      = addslashes( $admin->get_post( 'comments' ) );
                $modified_when = time();
                $modified_by   = $admin->get_user_id();
                if ( $id == 'new' )
                {
                    // check for doubles
                    $query = $database->query( "SELECT * FROM " . TABLE_PREFIX . "mod_sqlexecuter WHERE name = '$title'" );
                    if ( $query->numRows() > 0 )
                    {
                        $problem  = $MOD_SQLEXECUTER['There is already a sql with the same name!'];
                        $continue = false;
                        $data     = $_POST;
                        $data['code'] = stripslashes( $_POST[ 'code' ] );
                    }
                    else
                    {
						$code  = addslashes( $content );
						// generate query
						$query = "INSERT INTO " . TABLE_PREFIX . "mod_sqlexecuter VALUES "
							   . "(''," . "'$title', " . "'$code', " . "'$description', " . "'$modified_when', " . "'$modified_by', " . "'$active',1,1, '$show_wysiwyg', '$comments' )";
					    $result = $database->query( $query );
					    if ( $database->is_error() )
					    {
					        echo "ERROR: ", $database->get_error();
					    }
                        
                    }
                }
                else
                { 
                    // Update row
                    $database->query( "UPDATE " . TABLE_PREFIX . "mod_sqlexecuter SET name = '$title', active = '$active', show_wysiwyg = '$show_wysiwyg', description = '$description', code = '"
                                    . addslashes( $content )
                                    . "', comments = '$comments', modified_when = '$modified_when', modified_by = '$modified_by' WHERE id = '$id'"
                    );
                    // reload data
                    $query = $database->query( "SELECT * FROM " . TABLE_PREFIX . "mod_sqlexecuter WHERE id = '$id'" );
                    $data  = $query->fetchRow();
                }
                if ( $continue )
                {
                    // Check if there is a db error
                    if ( $database->is_error() )
                    {
                        $problem = $database->get_error();
                    }
                    else
                    {
                        if ( $id == 'new' || isset( $_POST[ 'save_and_back' ] ) )
                        {
                            list_sql( $MOD_SQLEXECUTER['The SQL was saved'] );
                            return; // should never be reached
                        }
                        else
                        {
                            $info = $MOD_SQLEXECUTER['The SQL was saved'];
                        }
                    }
                }
            }
            else
            {
                $problem = implode( "<br />", $problems );
            }
    }

    echo $parser->render(
    	'edit.lte',
    	array(
    	'LANG'	=> $MOD_SQLEXECUTER,
        'problem' => $problem,
        'info' => $info,
        'data' => $data,
        'id'   => $id,
        'name' => $data[ 'name' ],
		'register_area' => registerEditArea( 'code','sql'),
        'TEXT' => $TEXT
    ) );
} // end function edit_sql()



/**
 *	manage global sql permissions
 **/
function manage_perms()
{
    global $admin, $parser, $database, $settings, $MOD_SQLEXECUTER;
    $info   = NULL;
    $groups = array();
    $rows   = array();

    $this_user_groups = $admin->get_groups_id();
    if ( !is_allowed( 'Manage perms', $this_user_groups ) )
    {
        $admin->print_error( $MOD_SQLEXECUTER[ "You don't have the permission to do this" ] );
    }

    // get available groups
    $query = $database->query( 'SELECT group_id, name FROM ' . TABLE_PREFIX . 'groups ORDER BY name' );
    if ( $query->numRows() )
    {
        while ( $row = $query->fetchRow() )
        {
            $groups[ $row[ 'group_id' ] ] = $row[ 'name' ];
        }
    }

    if ( isset( $_REQUEST[ 'save' ] ) || isset( $_REQUEST[ 'save_and_back' ] ) )
    {
        foreach ( $settings as $key => $value )
        {
            if ( isset( $_REQUEST[ $key ] ) )
            {
                $database->query( 'UPDATE ' . TABLE_PREFIX . "mod_sqlexecuter_settings SET value='" . implode( '|', $_REQUEST[ $key ] ) . "' WHERE attribute='" . $key . "';" );
            }
        }
        // reload settings
        $settings = get_settings();
        $info     = $MOD_SQLEXECUTER[ 'Permissions saved' ];
        if ( isset( $_REQUEST[ 'save_and_back' ] ) )
        {
            return list_sql( $info );
        }
    }

    foreach ( $settings as $key => $value )
    {
        $line = array();
        foreach ( $groups as $id => $name )
        {
            $line[] = '<input type="checkbox" name="' . $key . '[]" id="' . $key . '_' . $id . '" value="' . $id . '"' . ( is_in_array( $value, $id ) ? ' checked="checked"' : NULL ) . ' />' . '<label for="' . $key . '_' . $id . '">' . $name . '</label>' . "\n";
        }
        $rows[] = array(
            'groups' => implode( '', $line ),
            'name' => $MOD_SQLEXECUTER[ $key ]
        );
    }

    // sort rows by permission name (=text)
	sort($rows);
	
    echo $parser->render(
    	'permissions.lte',
    	array(
        'rows' => $rows,
        'info' => $info,
        'num_rows' => count($rows)
    ) );

} // end function manage_perms()

/**
 * edit a sql datafile
 **/
function edit_datafile( $id )
{
    global $admin, $parser, $database,$MOD_SQLEXECUTER;
    $info = $problem = NULL;

    $groups = $admin->get_groups_id();
    if ( !is_allowed( 'modify_sql', $groups ) )
    {
        $admin->print_error( $MOD_SQLEXECUTER["You don't have the permission to do this"] );
    }

    if ( isset( $_POST[ 'cancel' ] ) )
    {
        return list_sql();
    }

    $query = $database->query( "SELECT name FROM " . TABLE_PREFIX . "mod_sqlexecuter WHERE id = '$id'" );
    $data  = $query->fetchRow();

	$files = array(
		dirname( __FILE__ ) . '/data/' . $data[ 'name' ] . '.txt',
		dirname( __FILE__ ) . '/data/' . strtolower( $data[ 'name' ] ) . '.txt',
		dirname( __FILE__ ) . '/data/' . strtoupper( $data[ 'name' ] ) . '.txt'
	);
	foreach($files as &$temp_filename)
	{
	
    	// find the file
    	if ( file_exists( $temp_filename ) )
    	{
    		$file = $temp_filename;
    		break;
    	}
    }

    // slurp file
    $contents = implode( '', file( $file ) );

    if ( isset( $_POST[ 'save' ] ) || isset( $_POST[ 'save_and_back' ] ) )
    {
        $new_contents = htmlentities( $_POST[ 'contents' ] );
        // create backup copy
        copy( $file, $file . '.bak' );
        $fh = fopen( $file, 'w' );
        if ( is_resource( $fh ) )
        {
            fwrite( $fh, $new_contents );
            fclose( $fh );
            $info = $MOD_SQLEXECUTER['The datafile has been saved'];
            if ( isset( $_POST[ 'save_and_back' ] ) )
            {
                return list_sql( $info );
            }
        }
        else
        {
            $problem = sprintf($MOD_SQLEXECUTER[ 'Unable to write to file [{{file}}]'], array(
                 'file' => str_ireplace( LEPTON_PATH, 'LEPTON_PATH', $file )
            ) );
        }
    }

    $parser->output( 'edit_datafile.lte', array(
        'info' => $info,
        'problem' => $problem,
        'name' => $data[ 'name' ],
        'id' => $id,
        'contents' => htmlspecialchars( $contents )
    ) );
} // end function edit_sql()


/**
 *	Function to switch between active/inactive
 **/
function toggle_active( $id )
{
    global $admin, $parser, $database;

    $groups = $admin->get_groups_id();
    if ( !is_allowed( 'modify_sql', $groups ) )
    {
        $admin->print_error( $MOD_SQLEXECUTER[ "You don't have the permission to do this" ] );
    }

    $query = $database->query( "SELECT `active` FROM " . TABLE_PREFIX . "mod_sqlexecuter WHERE id = '$id'" );
    $data  = $query->fetchRow();

    $new = ( $data[ 'active' ] == 1 ) ? 0 : 1;

    $database->query( 'UPDATE ' . TABLE_PREFIX . "mod_sqlexecuter SET active='$new' WHERE id = '$id'" );

} // end function toggle_active()

/**
 * checks if any item of $allowed is in $current
 **/
function is_in_array( $allowed, $current )
{
    if ( !is_array( $allowed ) )
    {
        if ( substr_count( $allowed, '|' ) )
        {
            $allowed = explode( '|', $allowed );
        }
        else
        {
            $allowed = array(
                 $allowed
            );
        }
    }
    if ( !is_array( $current ) )
    {
        if ( substr_count( $current, '|' ) )
        {
            $current = explode( '|', $current );
        }
        else
        {
            $current = array(
                 $current
            );
        }
    }
    foreach ( $allowed as $gid )
    {
        if ( in_array( $gid, $current ) )
        {
            return true;
        }
    }
    return false;
} // end function is_in_array()

/**
 *
 **/
function is_allowed( $perm, $gid )
{
    global $admin, $settings;
    // admin is always allowed to do all
    if ( $admin->get_user_id() == 1 )
    {
        return true;
    }
    if ( !array_key_exists( $perm, $settings ) )
    {
        return false;
    }
    else
    {
        $value = $settings[ $perm ];
        if ( !is_array( $value ) )
        {
            $value = array(
                 $value
            );
        }
        return is_in_array( $value, $gid );
    }
    return false;
} // end function is_allowed()



/**
 * get the module settings from the DB; returns array
 **/
function get_settings()
{
    global $admin, $database;
    $settings = array();
    $query    = $database->query( 'SELECT * FROM ' . TABLE_PREFIX . 'mod_sqlexecuter_settings' );
    if ( $query->numRows() )
    {
        while ( $row = $query->fetchRow() )
        {
            if ( substr_count( $row[ 'value' ], '|' ) )
            {
                $row[ 'value' ] = explode( '|', $row[ 'value' ] );
            }
            $settings[ $row[ 'attribute' ] ] = $row[ 'value' ];
        }
    }
    return $settings;
} // end function get_settings()


/**
 * function to run sql statements from backend
 **/
function execute_sql ( $id ) {
	global $database, $MOD_SQLEXECUTER;
 	global $parser, $loader;
 	
	$result = array();
	$database->execute_query(
		"SELECT `code`,`active` from `".TABLE_PREFIX."mod_sqlexecuter` WHERE id = '".$id."'",
		true,
		$result,
		false
	 );
 
	if (count($result) == 0) {
		return "Problem: ".$MOD_SQLEXECUTER["No sql found"];
	}
	if ($result['active'] == 0) {
		return "Problem: ".$MOD_SQLEXECUTER["SQL not active"];
		//return $problem = $MOD_SQLEXECUTER["SQL not active"];
 	} else {
		$sql_result = array();
		$database->execute_query(
			$result['code'],
			true,
			$sql_result
		);

		if ($database->is_error()) {
			return "Problem: ".$MOD_SQLEXECUTER['Run false']."<br />".$database->get_error();
		} else {
			$return_message = $MOD_SQLEXECUTER['Run ok'];
			if (count($sql_result) > 0) {
				$return_message .= $parser->render(
					'sql_result_list.lte',
					array(
    	    			'header_items' => array_keys($sql_result[0]),
 				       'results' => $sql_result
    				)
    			);
    		}
			return $return_message;
		}
 	}
	return NULL;
}
?>