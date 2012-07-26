<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);
define('XN_ROOT', './../');

include(XN_ROOT . 'global.php');
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