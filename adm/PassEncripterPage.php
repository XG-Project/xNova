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
	if (isset($_POST['pass']) && $_POST['pass'] != "")
	{
		$parse['sent_pass']	= $_POST['pass'];
		$parse['md5_res']	= md5($_POST['pass']);
		$parse['sha1_res']	= sha1($_POST['pass']);
	}
	else
	{
		$parse['sent_pass']	= '';
		$parse['md5_res']	= md5('');
		$parse['sha1_res']	= sha1('');
	}
	display(parsetemplate(gettemplate('adm/PassEncripterBody'), $parse), FALSE, '', TRUE, FALSE);

?>