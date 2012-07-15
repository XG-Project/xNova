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
	$player		= 	$_POST['player'];
	$every_time	=	$_POST['every_time'];

	$i		=	1;
	if ($_POST)
	{
		$CheckPlayer = doquery("SELECT `player` FROM {{table}} WHERE `player` = '" . mysql_real_escape_string($_POST['player']) . "' ", "bots", true);

		if (!$player || !$every_time){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$msj['new_complete_all'].'</tr></th>';
			$i++;}

		if (!$player){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$msj['new_complete_player'].'</tr></th>';
			$i++;}

		if ($CheckPlayer){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$msj['new_error_player_exist'].'</tr></th>';
			$i++;}

		if (!$every_time){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$msj['new_complete_every_time'].'</tr></th>';
			$i++;}

		if ($i	==	'1'){
			$Query1  = "INSERT INTO {{table}} SET ";
			$Query1 .= "`player` = '" . $player . "', ";
			$Query1 .= "`every_time` = '" . $every_time . "'; ";

			doquery($Query1, "bots");
			update_config('bots', read_config('bots') + 1);

			$parse['display']	=	'<tr><th colspan="2"><font color=lime>Nuevo Bot creado.</font></tr></th>';
		}
	}

	display(parsetemplate(gettemplate('adm/CreateBotBody'), $parse), false, '', true, false);
		break;

		case 'deletenew_bot':

	    $do = unlink(XGP_ROOT."adm/Log/BotLog.php");
	    if($do=="1")

	display(parsetemplate(gettemplate('adm/DeleteBotBody'), $parse), false, '', true, false);
		break;

		default:
	{

		extract($_GET);

		$query = doquery("SELECT * FROM {{table}}", 'bots');

		$i = 0;

		while ($u = mysql_fetch_array($query))
		{
			$i++;

			$parse['bots_list'] .= "

			<tr><td width=\"25\">". $u['id'] ."</td>
			<td width=\"170\">". $u['player'] ."</td>
			<td width=\"230\">". $u['last_time'] ."</td>
			<td width=\"230\">". $u['every_time'] ."</td>
			<td width=\"230\">". $u['last_planet'] ."</td>
			<td width=\"230\">". $u['type'] ."</td>
			<td width=\"95\"><a href=\"?delete=". $u['id'] ."\" border=\"0\"><img src=\"../styles/images/r1.png\" border=\"0\"></a></td>
			<td width=\"95\"><a href=\"AccountDataPage.php?id_u=". $u['player'] ."&regexp=&id_u2=\" border=\"0\"><img src=\"../styles/images/Adm/GO.png\" border=\"0\"></a></td></tr>
			<tr><th colspan=\"4\" class=b>".  nl2br($u['error_text'])."</td></tr>";
		}

		$parse['bots_list'] .= "<tr><th class=b colspan=8>Hay un total de ". $i ." bots creados.</th></tr>";


		if (isset($delete)){
			doquery("DELETE FROM {{table}} WHERE `id`=$delete", 'bots');
			header ("Location: BotSettingsPage.php");}
		elseif ($deleteall == 'yes'){
			doquery("TRUNCATE TABLE {{table}}", 'bots');
			header ("Location: BotSettingsPage.php");}
	}

		display(parsetemplate(gettemplate('adm/BotSettingsBody'), $parse), false, '', true, false);
}


/* End of file BotSettingsPage.php */
/* Location: ./adm/BotSettingsPage.php */