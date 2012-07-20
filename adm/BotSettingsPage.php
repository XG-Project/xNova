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

$parse	= $lang;
$page	= isset($_GET['page']) ? $_GET['page'] : NULL;

switch ($page)
{
	case 'new_bot':
		$user				= 	isset($_POST['user']) ? $_POST['user'] : NULL;
		$minutes_per_day	=	isset($_POST['minutes_per_day']) ? $_POST['minutes_per_day'] : NULL;

		$i		=	1;
		if ($_POST)
		{
			$CheckBots = doquery("SELECT `user` FROM {{table}} WHERE `user` = '".$db->real_escape_string($_POST['user'])."' ", "bots");
			$CheckUser = doquery("SELECT `id` FROM {{table}} WHERE `id` = '".$db->real_escape_string($_POST['user'])."' ", "users");

			if ( ! $user OR ! $minutes_per_day)
			{
				$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['bot_err_complete_all'].'</tr></th>';
				$i++;
			}

			if ( ! $user)
			{
				$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['bot_err_complete_user'].'</tr></th>';
				$i++;
			}

			if ($CheckBots->num_rows > 0)
			{
				$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['bot_err_bot_exist'].'</tr></th>';
				$i++;
			}

			if ($CheckUser->num_rows === 0)
			{
				$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['bot_err_user_not_exist'].'</tr></th>';
				$i++;
			}

			if ( ! $minutes_per_day OR ! is_numeric($minutes_per_day) OR $minutes_per_day > 1440)
			{
				$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['bot_err_minutes_per_day'].'</tr></th>';
				$i++;}

			if ($i	===	1)
			{
				$Query1  = "INSERT INTO {{table}} SET ";
				$Query1 .= "`user` = '" . $user . "', ";
				$Query1 .= "`minutes_per_day` = '" . $minutes_per_day . "'; ";

				doquery($Query1, "bots");
				update_config('bots', read_config('bots') + 1);

				$parse['display']	=	'<tr><th colspan="2"><font color=lime>Nuevo Bot creado.</font></tr></th>';
			}
		}

		display(parsetemplate(gettemplate('adm/CreateBotBody'), $parse), false, '', true, false);
	break;

	case 'delete_log':
			$file = fopen(XN_ROOT."adm/Log/BotLog.php", "w");
			fclose($file);
			display(parsetemplate(gettemplate('adm/DeleteBotBody'), $parse), false, '', true, false);
	break;

	default:
	extract($_GET);

	$query = doquery("SELECT * FROM {{table}}", 'bots');

	$parse['bots_list'] = '';
	while ($u = $query->fetch_array())
	{
		$parse['bots_list'] .= '
		<tr><td width="25">'. $u['id'] .'</td>
		<td width="25">'. $u['user'] .'</td>
		<td width="250">'. date('H:i:s - j/n/Y', $u['last_time']) .'</td>
		<td width="250">'. date('H:i:s - j/n/Y', $u['next_time']) .'</td>
		<td width="100">'. $u['minutes_per_day'] .'</td>
		<td width="230">'. $u['last_planet'] .'</td>
		<td width="100"><a href="BotSettingsPage.php?delete='. $u['id'] .'" onClick="return confirm(\''.$lang['bot_delete_confirm'].'\');" border="0"><img src="../styles/images/r1.png" border=\"0\"></a></td>
		<td width="95"><a href="AccountDataPage.php?id_u='. $u['user'] .'" border="0"><img src="../styles/images/Adm/GO.png" border="0"></a></td></tr>';
		if (isset($u['error_text']))
			$parse['bots_list'] .= '<tr><th colspan="8" class="b">'.nl2br($u['error_text']).'</td></tr>';
	}

	$parse['bots_list'] .= '<tr><th class="b" colspan="8">Hay un total de '.read_config('bots').' bots creados.</th></tr>';


	if (isset($delete))
	{
		$bot = doquery('SELECT `user` FROM {{table}} WHERE `id`='.$delete, 'bots', TRUE);
		doquery('DELETE FROM {{table}} WHERE `id`='.$delete, 'bots');
		update_config('bots', read_config('bots') - 1);
		unlink(XN_ROOT.'includes/bots/'.md5($bot['user']).'.botdb');

		header ("Location: BotSettingsPage.php");
	}
	elseif (isset($deleteall))
	{
		doquery("TRUNCATE TABLE {{table}}", 'bots');
		update_config('bots', 0);
		foreach(scandir(XN_ROOT.'includes/bots/') as $file)
			if(is_file(XN_ROOT.'includes/bots/'.$file)) unlink(XN_ROOT.'includes/bots/'.$file);

		header ("Location: BotSettingsPage.php");
	}
	$parse['log'] = htmlentities(file_get_contents(XN_ROOT.'adm/Log/BotLog.php'), ENT_COMPAT, 'UTF-8');

	display(parsetemplate(gettemplate('adm/BotSettingsBody'), $parse), false, '', true, false);
}


/* End of file BotSettingsPage.php */
/* Location: ./adm/BotSettingsPage.php */