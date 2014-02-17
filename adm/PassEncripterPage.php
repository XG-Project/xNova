<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);
define('XGP_ROOT', './../');

include(XGP_ROOT . 'global.php');
include('AdminFunctions/Autorization.php');

if ($ToolsCanUse != 1) die(message ($lang['404_page']));

	$parse = $lang;
	if ($_POST['md5q'] != "")
	{
		$parse['md5_md5'] = $_POST['md5q'];
		$parse['md5_enc'] = md5 ($_POST['md5q']);
	}
	else
	{
		$parse['md5_md5'] = "";
		$parse['md5_enc'] = md5 ("");
	}
	display(parsetemplate(gettemplate('adm/PassEncripterBody'), $parse), FALSE, '', TRUE, FALSE);

?>