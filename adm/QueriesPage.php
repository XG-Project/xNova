<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);
define('XN_ROOT', './../');

include(XN_ROOT.'global.php');

if ($user['authlevel'] != 3) die(message ($lang['404_page']));

$parse 	= $lang;
$Query	=	$_POST['querie'];

if ($_POST)
{
	$FinalQuery	=	str_replace("\'", "'", str_replace('\"', '"', $Query));

	if ( ! $db->query($FinalQuery))
	{
		$parse['display'] = "<tr><th><font color=red>".$db->error."</font></th></tr>";
	}
	else
	{
		$Log	.=	"\n".$lang['log_queries_title']."\n";
		$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_queries_succes']."\n";
		$Log	.=	$Query."\n";
		LogFunction($Log, "GeneralLog", $LogCanWork);
		$parse['display'] = "<tr><th><font color=lime>".$lang['qe_succes']."</font></th></tr>";
	}
}

display(parsetemplate(gettemplate('adm/QueriesBody'), $parse), FALSE, '', TRUE, FALSE);


/* End of file QueriesPage.php */
/* Location: ./adm/QueriesPage.php */