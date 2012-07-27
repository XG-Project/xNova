<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */


if ( ! defined('INSIDE')) die(header("location: ./../../"));
if ($user['authlevel'] < 3) die(message($lang['404_page']));

class ShowModerationPage {

	public function __construct($moderation)
	{
		global $lang;
		$parse = $lang;

		if ($moderation === 1)
		{
			$QueryModeration	=	read_config ('moderation');
			$QueryModerationEx	=	explode(";", $QueryModeration);
			$Moderator			=	explode(",", $QueryModerationEx[0]);
			$Operator			=	explode(",", $QueryModerationEx[1]);
			$Administrator		=	explode(",", $QueryModerationEx[2]); // Solo sirve para el historial


			// MODERADORES
			if ($Moderator[0]) $parse['view_m'] = ' checked';
			if ($Moderator[1]) $parse['edit_m'] = ' checked';
			if ($Moderator[2]) $parse['config_m'] = ' checked';
			if ($Moderator[3]) $parse['tools_m'] = ' checked';
			if ($Moderator[4]) $parse['log_m'] = ' checked';

			// OPERADORES
			if ($Operator[0]) $parse['view_o'] = ' checked';
			if ($Operator[1]) $parse['edit_o'] = ' checked';
			if ($Operator[2]) $parse['config_o'] = ' checked';
			if ($Operator[3]) $parse['tools_o'] = ' checked';
			if ($Operator[4]) $parse['log_o'] = ' checked';

			// ADMINISTRADOR (SOLO PARA EL HISTORIAL)
			if ($Administrator[0]) $parse['log_a'] = ' checked';


			$parse['mods']	=	$lang['rank'][1];
			$parse['oper']	=	$lang['rank'][2];
			$parse['adm']	=	$lang['rank'][3];

			if ($_SERVER['REQUEST_METHOD'] === 'POST')
			{
				$view_m		= isset($_POST['view_m']) ? (int)(bool) $_POST['view_m'] : 0;
				$edit_m		= isset($_POST['edit_m']) ? (int)(bool) $_POST['edit_m'] : 0;
				$config_m	= isset($_POST['config_m']) ? (int)(bool) $_POST['config_m'] : 0;
				$tools_m	= isset($_POST['tools_m']) ? (int)(bool) $_POST['tools_m'] : 0;
				$log_m		= isset($_POST['log_m']) ? (int)(bool) $_POST['log_m'] : 0;

				$view_o		= isset($_POST['view_o']) ? (int)(bool) $_POST['view_o'] : 0;
				$edit_o		= isset($_POST['edit_o']) ? (int)(bool) $_POST['edit_o'] : 0;
				$config_o	= isset($_POST['config_o']) ? (int)(bool) $_POST['config_o'] : 0;
				$tools_o	= isset($_POST['tools_o']) ? (int)(bool) $_POST['tools_o'] : 0;
				$log_o		= isset($_POST['log_o']) ? (int)(bool) $_POST['log_o'] : 0;

				$log_a		= isset($_POST['log_a']) ? (int)(bool) $_POST['log_a'] : 0;

				$QueryEdit	=	$view_m.",".$edit_m.",".$config_m.",".$tools_m.",".$log_m.";".
								$view_o.",".$edit_o.",".$config_o.",".$tools_o.",".$log_o.";".$log_a.";";

				$Log	.=	"\n".$lang['log_system_mod_title']."\n";
				$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_modify_personal'].":\n";
				$Log	.=	$lang['log_can_view_mod']."\n";
				$Log	.=	$lang['log_tools'].":     ".$lang['log_viewmod'][$tools_m]."\n";
				$Log	.=	$lang['log_edit'].":     ".$lang['log_viewmod'][$edit_m]."\n";
				$Log	.=	$lang['log_view'].":     ".$lang['log_viewmod'][$view_m]."\n";
				$Log	.=	$lang['log_config'].":     ".$lang['log_viewmod'][$config_m]."\n\n";
				$Log	.=	$lang['log_can_view_ope']."\n";
				$Log	.=	$lang['log_tools'].":     ".$lang['log_viewmod'][$tools_o]."\n";
				$Log	.=	$lang['log_edit'].":     ".$lang['log_viewmod'][$edit_o]."\n";
				$Log	.=	$lang['log_view'].":     ".$lang['log_viewmod'][$view_o]."\n";
				$Log	.=	$lang['log_config'].":     ".$lang['log_viewmod'][$config_o]."\n";

				LogFunction($Log, "ModerationLog", $LogCanWork);

				update_config('moderation' , $QueryEdit);
				header('location: admin.php?page=moderate&moderation=1');
			}

			display(parsetemplate(gettemplate('adm/ModerationBody'), $parse), TRUE, '', TRUE, TRUE);
		}
		elseif ($moderation === 2)
		{
			//
		}
		else
		{
			header('location: admin.php');
		}
	}
}
/*

$parse	=	$lang;

if ($_GET['moderation'] == '1')
{



}
elseif ($_GET['moderation'] == '2')
{
		for ($i	= 0; $i < 4; $i++)
		{
			$parse['authlevels']	.=	"<option value=\"".$i."\">".$lang['rank'][$i]."</option>";
		}


		if ($_GET['get'] == 'adm')
			$WHEREUSERS	=	"WHERE `authlevel` = '3'";
		elseif ($_GET['get'] == 'ope')
			$WHEREUSERS	=	"WHERE `authlevel` = '2'";
		elseif ($_GET['get'] == 'mod')
			$WHEREUSERS	=	"WHERE `authlevel` = '1'";
		elseif ($_GET['get'] == 'pla')
			$WHEREUSERS	=	"WHERE `authlevel` = '0'";


		$QueryUsers	=	doquery("SELECT `id`, `username`, `authlevel` FROM {{table}} ".$WHEREUSERS."", "users");


		while ($List = $QueryUsers->fetch_array())
		{
			$parse['List']	.=	"<option value=\"".$List['id']."\">".$List['username']."&nbsp;&nbsp;(".$lang['rank'][$List['authlevel']].")</option>";
		}


		if ($_POST)
		{
			if (isset($_POST[''] && $_POST['id_1'] != NULL && $_POST['id_2'] != NULL)
			{
				$parse['display']	=	'<tr><th colspan="3"><font color=red>'.$lang['ad_authlevel_error_2'].'</font></th></tr>';
			}
			elseif( ! $_POST['id_1'] && !$_POST['id_2'])
			{
				$parse['display']	=	'<tr><th colspan="3"><font color=red>'.$lang['ad_forgiven_id'].'</font></th></tr>';
			}
			elseif( ! $_POST['id_1'] && ! is_numeric($_POST['id_2']))
			{
				$parse['display']	=	'<tr><th colspan="3"><font color=red>'.$lang['only_numbers'].'</font></th></tr>';
			}
			elseif($_POST['id_1'] == '1' || $_POST['id_2'] == '1')
			{
				$parse['display']	=	'<tr><th colspan="3"><font color=red>'.$lang['ad_authlevel_error_3'].'</font></th></tr>';
			}
			else
			{
				if (isset($_POST[''] && $_POST['id_1'] != NULL)
					$id	=	$_POST['id_1'];
				else
					$id	=	$_POST['id_2'];


				$QueryFind	=	doquery("SELECT `authlevel` FROM {{table}} WHERE `id` = '".$id."'", "users", TRUE);

				if($QueryFind['authlevel'] != $_POST['authlevel'])
				{
					doquery("UPDATE {{table}} SET `authlevel` = '".$_POST['authlevel']."' WHERE `id` = '".$id."'", "users");
					doquery("UPDATE {{table}} SET `id_level` = '".$_POST['authlevel']."' WHERE `id_owner` = '".$id."';", 'planets');


					$ASD	=	$_POST['authlevel'];
					$Log	.=	"\n".$lang['log_system_auth_title']."\n";
					$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_change_auth_1'].$id.",\n";
					$Log	.=	$lang['log_change_auth_2'].$lang['ad_authlevel'][$ASD]."\n";

					LogFunction($Log, "ModerationLog", $LogCanWork);

					header ( 'location:Moderation.php?moderation=2&succes=yes' );
				}
				else
				{
					$parse['display']	=	'<tr><th colspan="3"><font color=red>'.$lang['ad_authlevel_error'].'</font></th></tr>';
				}
			}
		}

		if ($_GET['succes']	==	'yes')
			$parse['display']	=	'<tr><th colspan="3"><font color=lime>'.$lang['ad_authlevel_succes'].'</font></th></tr>';


		display (parsetemplate(gettemplate("adm/AuthlevelBody"), $parse), FALSE, '', TRUE, FALSE);
}
else
{
	die();
}
?>*/