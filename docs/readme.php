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
 

 
$html = file_get_contents( dirname(__FILE__)."/readme.html" );
$html = str_replace(
	"{{ url }}",
	$_GET['url'],
	$html
);

echo $html;
?>