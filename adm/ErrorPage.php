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

include(XN_ROOT . 'global.php');

if ($ConfigGame != 1) die(message ($lang['404_page']));

	$parse = $lang;

	extract($_GET);

	$page	= isset($page) ? $page : NULL;
	$parse['errors_list'] = '';

	switch ($page)
	{
		case 'sql':
			$query = doquery("SELECT * FROM {{table}} WHERE `error_type` != 'PHP' ORDER BY `error_time`", 'errors');

			$i = 0;

			while ($u = $query->fetch_array())
			{
				$i++;

				$parse['errors_list'] .= "

				<tr><td width=\"25\">". $u['error_id'] ."</td>
				<td width=\"70\">". $u['error_sender'] ."</td>
				<td width=\"100\">". $u['error_type'] ."</td>
				<td width=\"230\">". date('d/m/Y h:i:s', $u['error_time']) ."</td>
				<td width=\"95\"><a href=\"?page=sql&delete=". $u['error_id'] ."\" border=\"0\"><img src=\"../styles/images/r1.png\" border=\"0\"></a></td></tr>
				<tr><th colspan=\"5\" class=b>".  nl2br($u['error_text'])."</td></tr>";
			}

			$parse['errors_list'] .= "<tr><th class=b colspan=5>". $i . $lang['er_errors'] ."</th></tr>";


			if (isset($delete))
			{
				doquery("DELETE FROM {{table}} WHERE `error_id`=$delete", 'errors');
				$Log	=	"\n".$lang['log_errores_title']."\n";
				$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_errors']."\n";
				LogFunction($Log, "GeneralLog", $LogCanWork);
				header ( 'location:ErrorPage.php?page=sql' );
			}
			elseif (isset($deleteall) && $deleteall == 'yes')
			{
				doquery("DELETE FROM {{table}} WHERE `error_type` != 'PHP'", 'errors');
				$Log	=	"\n".$lang['log_errores_title']."\n";
				$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_all_sql_errors']."\n";
				LogFunction($Log, "GeneralLog", $LogCanWork);
				header ( 'location:ErrorPage.php?page=sql' );
			}

			display(parsetemplate(gettemplate('adm/SQLErrorMessagesBody'), $parse), FALSE, '', TRUE, FALSE);
		break;
		case 'php':
			$error_level	= array('32767', '8192', '4096', '2048', '8', '2');
			$show			= array();
			foreach ($error_level as $error)
			{
				if( ! isset($_POST['submit']))
				{
					$show[$error] = TRUE;
					$parse['checked_'.$error] = 'checked';
				}
				elseif (isset($_POST['show_'.$error]) && $_POST['show_'.$error])
				{
					$show[$error] = TRUE;
					$parse['checked_'.$error] = 'checked';
				}
				else
				{
					$parse['checked_'.$error] = '';
				}
			}

			if( ! empty($show))
			{
				$filter	= ' AND (';
				$i		= 0;
				$total	= count($show);
				foreach ($show as $key => $value)
				{
					$i++;
					$filter .= '`error_level` = '.$key;
					if($i != $total) $filter .= ' OR ';
				}
				$filter .= ')';
			}

			$query = doquery("SELECT * FROM {{table}} WHERE `error_type` = 'PHP'".$filter." ORDER BY `error_file` ASC, `error_line` ASC", 'errors');

			$i = 0;

			$error_text		= array('E_ALL', 'E_DEPRECATED', 'E_RECOVERABLE_ERROR', 'E_STRICT', 'E_NOTICE', 'E_WARNING');

			while ($u = $query->fetch_array())
			{
				$i++;

				$parse['errors_list'] .= "

				<tr><td width=\"25\">". $u['error_id'] ."</td>
				<td width=\"50\">". date('d/m/Y H:i:s', $u['error_time']) ."</td>
				<td width=\"70\">". (( !$u['error_sender']) ? $lang['er_public'] : $u['error_sender']) ."</td>
				<td width=\"50\">". str_replace($error_level, $error_text, $u['error_level']) ."</td>
				<td width=\"100\">". $u['error_file'] ."</td>
				<td width=\"100\">". $u['error_line'] ."</td>
				<td width=\"100\">". str_replace('%lang%', $lang['lang_key'], $u['error_text']) ."</td>
				<td width=\"95\"><a href=\"?page=php&delete=". $u['error_id'] ."\" border=\"0\"><img src=\"../styles/images/r1.png\" border=\"0\"></a></td></tr>";
			}

			$parse['errors_list'] .= "<tr><th class=b colspan=5>". $i . $lang['er_errors'] ."</th></tr>";


			if (isset($delete))
			{
				doquery("DELETE FROM {{table}} WHERE `error_id`=$delete", 'errors');
				$Log	=	"\n".$lang['log_errores_title']."\n";
				$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_errors']."\n";
				LogFunction($Log, "GeneralLog", $LogCanWork);
				header ( 'location:ErrorPage.php?page=php' );
			}
			elseif (isset($deleteall) && $deleteall === 'yes')
			{
				doquery("DELETE FROM {{table}} WHERE `error_type` = 'PHP'", 'errors');
				$Log	=	"\n".$lang['log_errores_title']."\n";
				$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_all_php_errors']."\n";
				LogFunction($Log, "GeneralLog", $LogCanWork);
				header ( 'location:ErrorPage.php?page=php' );
			}

			display(parsetemplate(gettemplate('adm/PHPErrorMessagesBody'), $parse), FALSE, '', TRUE, FALSE);
		break;
		default:
			display(parsetemplate(gettemplate('adm/ErrorMenu'), $parse), FALSE, '', TRUE, FALSE);
	}

?>