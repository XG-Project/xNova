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

if ($EditUsers != 1) die();

	$parse = $lang;

	if ($_POST['md5q'] != "")
	{
		doquery ("UPDATE {{table}} SET `password` = '" . md5 ($_POST['md5q']) . "' WHERE `username` = '".$_POST['user']."';", 'users');
	}

	display( parsetemplate( gettemplate("adm/ChangePassBody"), $parse), FALSE, '', TRUE, FALSE);

?>