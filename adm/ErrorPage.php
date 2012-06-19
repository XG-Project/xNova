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

if ($ConfigGame != 1) die(message ($lang['404_page']));

	$parse = $lang;

	extract($_GET);

	$query = doquery("SELECT * FROM {{table}}", 'errors');

	$i = 0;

	while ($u = mysql_fetch_array($query))
	{
		$i++;

		$parse['errors_list'] .= "

		<tr><td width=\"25\">". $u['error_id'] ."</td>
		<td width=\"170\">". $u['error_type'] ."</td>
		<td width=\"230\">". date('d/m/Y h:i:s', $u['error_time']) ."</td>
		<td width=\"95\"><a href=\"?delete=". $u['error_id'] ."\" border=\"0\"><img src=\"../styles/images/r1.png\" border=\"0\"></a></td></tr>
		<tr><th colspan=\"4\" class=b>".  nl2br($u['error_text'])."</td></tr>";
	}

	$parse['errors_list'] .= "<tr><th class=b colspan=5>". $i . $lang['er_errors'] ."</th></tr>";


	if (isset($delete))
	{
		doquery("DELETE FROM {{table}} WHERE `error_id`=$delete", 'errors');
		$Log	.=	"\n".$lang['log_errores_title']."\n";
		$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_errors']."\n";
		LogFunction($Log, "GeneralLog", $LogCanWork);
		header ( 'location:ErrorPage.php' );
	}
	elseif ($deleteall == 'yes')
	{
		doquery("TRUNCATE TABLE {{table}}", 'errors');
		$Log	.=	"\n".$lang['log_errores_title']."\n";
		$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_all_errors']."\n";
		LogFunction($Log, "GeneralLog", $LogCanWork);
		header ( 'location:ErrorPage.php' );
	}

	display(parsetemplate(gettemplate('adm/ErrorMessagesBody'), $parse), FALSE, '', TRUE, FALSE);

?>