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

$msj['new_complete_all'] = '¡Complete todos los campos!';
$msj['new_complete_player'] = '¡Introduzca el Id del Jugador!';
$msj['new_complete_every_time'] = '¡Introzudca el tiempo de actualización del Bot!';
$msj['new_error_player_exist'] = '¡El Id del Jugador seleccionado ya tiene un Bot asignado!';

$parse = $lang;

switch ($_GET[page])
{
	case 'new_bot':
		$user				= 	isset($_POST['user']) ? $_POST['user'] : NULL;
		$minutes_per_day	=	isset($_POST['minutes_per_day']) ? $_POST['minutes_per_day'] : NULL;

		$i		=	1;
		if ($_POST)
		{
			$CheckPlayer = doquery("SELECT `user` FROM {{table}} WHERE `user` = '" . mysql_real_escape_string($_POST['user']) . "' ", "bots", true);

			if ( ! $user OR ! $minutes_per_day)
			{
				$parse['display']	.=	'<tr><th colspan="2" class="red">'.$msj['new_complete_all'].'</tr></th>';
				$i++;
			}

			if ( ! $user)
			{
				$parse['display']	.=	'<tr><th colspan="2" class="red">'.$msj['new_complete_player'].'</tr></th>';
				$i++;
			}

			if ($CheckPlayer)
			{
				$parse['display']	.=	'<tr><th colspan="2" class="red">'.$msj['new_error_player_exist'].'</tr></th>';
				$i++;
			}

			if ( ! $minutes_per_day)
			{
				$parse['display']	.=	'<tr><th colspan="2" class="red">'.$msj['new_complete_every_time'].'</tr></th>';
				$i++;}

			if ($i	===	1)
			{
				$Query1  = "INSERT INTO {{table}} SET ";
				$Query1 .= "`user` = '" . $player . "', ";
				$Query1 .= "`minutes_per_day` = '" . $every_time . "'; ";

				doquery($Query1, "bots");
				update_config('bots', read_config('bots') + 1);

				$parse['display']	=	'<tr><th colspan="2"><font color=lime>Nuevo Bot creado.</font></tr></th>';
			}
		}

		display(parsetemplate(gettemplate('adm/CreateBotBody'), $parse), false, '', true, false);
	break;

	case 'delete_log':
			$file = fopen(XGP_ROOT."adm/Log/BotLog.php", "w");
			fclose($file);
			display(parsetemplate(gettemplate('adm/DeleteBotBody'), $parse), false, '', true, false);
	break;

	default:
	extract($_GET);

	$query = doquery("SELECT * FROM {{table}}", 'bots');

	$parse['bots_list'] = '';
	while ($u = mysql_fetch_array($query))
	{
		$i++;

		$parse['bots_list'] .= '
		<tr><td width="25">'. $u['id'] .'</td>
		<td width="25">'. $u['user'] .'</td>
		<td width="250">'. date('H:i:s - j/n/Y', $u['last_time']) .'</td>
		<td width="250">'. date('H:i:s - j/n/Y', $u['next_time']) .'</td>
		<td width="100">'. $u['minutes_per_day'] .'</td>
		<td width="230">'. $u['last_planet'] .'</td>
		<td width="100"><a href="?delete='. $u['id'] .'" border="0"><img src="../styles/images/r1.png" border=\"0\"></a></td>
		<td width="95"><a href="AccountDataPage.php?id_u='. $u['user'] .'" border="0"><img src="../styles/images/Adm/GO.png" border="0"></a></td></tr>';
		if (isset($u['error_text']))
			$parse['bots_list'] .= '<tr><th colspan="8" class="b">'.nl2br($u['error_text']).'</td></tr>';
	}

	$parse['bots_list'] .= '<tr><th class="b" colspan="8">Hay un total de '.read_config('bots').' bots creados.</th></tr>';


	if (isset($delete))
	{
		doquery("DELETE FROM {{table}} WHERE `id`=$delete", 'bots');
		update_config('bots', read_config('bots') - 1);
		header ("Location: BotSettingsPage.php");
	}
	elseif ($deleteall == 'yes')
	{
		doquery("TRUNCATE TABLE {{table}}", 'bots');
		update_config('bots', 0);
		header ("Location: BotSettingsPage.php");
	}
	$parse['log'] = htmlentities(file_get_contents(XGP_ROOT.'adm/Log/BotLog.php'));

	display(parsetemplate(gettemplate('adm/BotSettingsBody'), $parse), false, '', true, false);
}


/* End of file BotSettingsPage.php */
/* Location: ./adm/BotSettingsPage.php */