<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

class ShowOptionsPage
{
	private function CheckIfIsBuilding($CurrentUser)
	{
		$activity	= doquery ( "SELECT (
											(
												SELECT COUNT( fleet_id ) AS quantity
													FROM {{table}}fleets
														WHERE fleet_owner = '" . intval ( $CurrentUser['id'] ) . "'
											)
										+
											(
												SELECT COUNT(id) AS quantity
													FROM {{table}}planets
														WHERE id_owner = '" . intval ( $CurrentUser['id'] ) . "' AND
														(b_building <> 0 OR b_tech <> 0 OR b_hangar <> 0)
											)
										) as total" , '' , TRUE );

		if ( $activity['total'] > 0 )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function __construct($CurrentUser)
	{
		global $lang;

		$mode = isset ( $_GET['mode'] ) ? $_GET['mode'] : NULL;

		if ($_POST && $mode == "exit")
		{
			if (isset($_POST["exit_modus"]) && $_POST["exit_modus"] == 'on' and $CurrentUser['urlaubs_until'] <= time())
			{
				$urlaubs_modus = "0";

				doquery("UPDATE {{table}} SET
				`urlaubs_modus` = '0',
				`urlaubs_until` = '0'
				WHERE `id` = '".intval($CurrentUser['id'])."' LIMIT 1", "users");

				die(header("location:game.php?page=options"));
			}
			else
			{
				$urlaubs_modus = "1";
				die(header("location:game.php?page=options"));
			}
		}

		if ($_POST && $mode == "change")
		{
			if ($CurrentUser['authlevel'] > 0)
			{
				if ($_POST['adm_pl_prot'] == 'on')
					doquery ("UPDATE {{table}} SET `id_level` = '".intval($CurrentUser['authlevel'])."' WHERE `id_owner` = '".intval($CurrentUser['id'])."';", 'planets');
				else
					doquery ("UPDATE {{table}} SET `id_level` = '0' WHERE `id_owner` = '".intval($CurrentUser['id'])."';", 'planets');
			}
			// < ------------------------------------------------------- EL SKIN ------------------------------------------------------- >
			if (isset($_POST["design"]) && $_POST["design"] == 'on')
			{
				$design = "1";
			}
			else
			{
				$design = "0";
			}
			// < ------------------------------------------------- COMPROBACION DE IP -------------------------------------------------- >
			if (isset($_POST["noipcheck"]) && $_POST["noipcheck"] == 'on')
			{
				$noipcheck = "1";
			}
			else
			{
				$noipcheck = "0";
			}
			// < ------------------------------------------------- NOMBRE DE USUARIO --------------------------------------------------- >
			if (isset($_POST["db_character"]) && $_POST["db_character"] != '')
			{
				$username = mysql_escape_value ( $_POST['db_character'] );
			}
			else
			{
				$username = mysql_escape_value ( $CurrentUser['username'] );
			}
			// < ------------------------------------------------- DIRECCION DE EMAIL -------------------------------------------------- >

			if (isset($_POST["db_email"]) && $_POST["db_email"] != '')
			{
				$db_email = mysql_escape_value ( $_POST['db_email'] );
			}
			else
			{
				$db_email = mysql_escape_value ( $CurrentUser['email'] );
			}
			// < ------------------------------------------------- CANTIDAD DE SONDAS -------------------------------------------------- >
			if (isset($_POST["spio_anz"]) && is_numeric($_POST["spio_anz"]))
			{
				$spio_anz = intval($_POST["spio_anz"]);
			}
			else
			{
				$spio_anz = "1";
			}
			// < ------------------------------------------------- TIEMPO TOOLTIP ------------------------------------------------------ >
			if (isset($_POST["settings_tooltiptime"]) && is_numeric($_POST["settings_tooltiptime"]))
			{
				$settings_tooltiptime = intval($_POST["settings_tooltiptime"]);
			}
			else
			{
				$settings_tooltiptime = "1";
			}
			// < ------------------------------------------------- MENSAJES DE FLOTAS -------------------------------------------------- >
			if (isset($_POST["settings_fleetactions"]) && is_numeric($_POST["settings_fleetactions"]))
			{
				$settings_fleetactions = intval($_POST["settings_fleetactions"]);
			}
			else
			{
				$settings_fleetactions = "1";
			}
			// < ------------------------------------------------- SONDAS DE ESPIONAJE ------------------------------------------------- >
			if (isset($_POST["settings_esp"]) && $_POST["settings_esp"] == 'on')
			{
				$settings_esp = "1";
			}
			else
			{
				$settings_esp = "0";
			}
			// < ------------------------------------------------- ESCRIBIR MENSAJE ---------------------------------------------------- >
			if (isset($_POST["settings_wri"]) && $_POST["settings_wri"] == 'on')
			{
				$settings_wri = "1";
			}
			else
			{
				$settings_wri = "0";
			}
			// < --------------------------------------------- AÑADIR A LISTA DE AMIGOS ------------------------------------------------ >
			if (isset($_POST["settings_bud"]) && $_POST["settings_bud"] == 'on')
			{
				$settings_bud = "1";
			}
			else
			{
				$settings_bud = "0";
			}

			// < ------------------------------------------------- ATAQUE CON MISILES -------------------------------------------------- >
			if (isset($_POST["settings_mis"]) && $_POST["settings_mis"] == 'on')
			{
				$settings_mis = "1";
			}
			else
			{
				$settings_mis = "0";
			}
			// < ------------------------------------------------- VER REPORTE --------------------------------------------------------- >
			if (isset($_POST["settings_rep"]) && $_POST["settings_rep"] == 'on')
			{
				$settings_rep = "1";
			}
			else
			{
				$settings_rep = "0";
			}
			// < ------------------------------------------------- MODO VACACIONES ----------------------------------------------------- >
			if (isset($_POST["urlaubs_modus"]) && $_POST["urlaubs_modus"] == 'on')
			{
				if($this->CheckIfIsBuilding($CurrentUser))
				{
					message($lang['op_cant_activate_vacation_mode'], "game.php?page=options",2);
				}

				$urlaubs_modus = "1";
				$time = time() + 86400;
				doquery("UPDATE {{table}} SET
				`urlaubs_modus` = '$urlaubs_modus',
				`urlaubs_until` = '$time'
				WHERE `id` = '".intval($CurrentUser["id"])."' LIMIT 1", "users");

				$query = doquery("SELECT * FROM {{table}} WHERE id_owner = '".intval($CurrentUser['id'])."'", 'planets');

				while($id = mysql_fetch_array($query))
				{
					doquery("UPDATE {{table}} SET
					metal_perhour = '".read_config ( 'metal_basic_income' )."',
					crystal_perhour = '".read_config ( 'crystal_basic_income' )."',
					deuterium_perhour = '".read_config ( 'deuterium_basic_income' )."',
					energy_used = '0',
					energy_max = '0',
					metal_mine_porcent = '0',
					crystal_mine_porcent = '0',
					deuterium_sintetizer_porcent = '0',
					solar_plant_porcent = '0',
					fusion_plant_porcent = '0',
					solar_satelit_porcent = '0'
					WHERE id = '{$id['id']}' AND `planet_type` = 1 ", 'planets');
				}
			}
			else
				$urlaubs_modus = "0";

			// < ------------------------------------------------- BORRAR CUENTA ------------------------------------------------------- >
			if (isset($_POST["db_deaktjava"]) && $_POST["db_deaktjava"] == 'on')
			{
				$db_deaktjava = time();
			}
			else
			{
				$db_deaktjava = "0";
			}

			$SetSort  = mysql_escape_value($_POST['settings_sort']);
			$SetOrder = mysql_escape_value($_POST['settings_order']);
			//// < -------------------------------------- ACTUALIZAR TODO LO SETEADO ANTES --------------------------------------------- >
			doquery("UPDATE {{table}} SET
			`email` = '$db_email',
			`dpath` = '$_POST[dpath]',
			`design` = '$design',
			`noipcheck` = '$noipcheck',
			`planet_sort` = '$SetSort',
			`planet_sort_order` = '$SetOrder',
			`spio_anz` = '$spio_anz',
			`settings_tooltiptime` = '$settings_tooltiptime',
			`settings_fleetactions` = '$settings_fleetactions',
			`settings_allylogo` = '$settings_allylogo',
			`settings_esp` = '$settings_esp',
			`settings_wri` = '$settings_wri',
			`settings_bud` = '$settings_bud',
			`settings_mis` = '$settings_mis',
			`settings_rep` = '$settings_rep',
			`urlaubs_modus` = '$urlaubs_modus',
			`db_deaktjava` = '$db_deaktjava'
			WHERE `id` = '".$CurrentUser["id"]."' LIMIT 1", "users");
			// < ------------------------------------------------- CAMBIO DE CLAVE ----------------------------------------------------- >
			if (isset($_POST["db_password"]) && md5($_POST["db_password"]) == $CurrentUser["password"])
			{
				if ($_POST["newpass1"] == $_POST["newpass2"])
				{
					if ($_POST["newpass1"] != "")
					{
						$newpass = md5($_POST["newpass1"]);
						doquery("UPDATE {{table}} SET `password` = '{$newpass}' WHERE `id` = '".intval($CurrentUser['id'])."' LIMIT 1", "users");
						setcookie(COOKIE_NAME, "", time()-100000, "/", "", 0);
						message($lang['op_password_changed'],"index.php",1);
					}
				}
			}
			// < --------------------------------------------- CAMBIO DE NOMBRE DE USUARIO --------------------------------------------- >
			if ($CurrentUser['username'] != $_POST["db_character"])
			{
				$query = doquery("SELECT id FROM {{table}} WHERE username='".mysql_escape_value ($_POST["db_character"])."'", 'users', TRUE);

				if (!$query)
				{
					doquery("UPDATE {{table}} SET username='".mysql_escape_value ($username)."' WHERE id='".intval($CurrentUser['id'])."' LIMIT 1", "users");
					setcookie(COOKIE_NAME, "", time()-100000, "/", "", 0);
					message($lang['op_username_changed'], "index.php", 1);
				}
			}
			message($lang['op_options_changed'], "game.php?page=options", 1);
		}
		else
		{
			$parse			= $lang;
			$parse['dpath'] = DPATH;

			if($CurrentUser['urlaubs_modus'])
			{
				$parse['opt_modev_data'] 	= ($CurrentUser['urlaubs_modus'] == 1)?" checked='checked'/":'';
				$parse['opt_modev_exit'] 	= ($CurrentUser['urlaubs_modus'] == 0)?" checked='1'/":'';
				$parse['vacation_until'] 	= date("d.m.Y G:i:s",$CurrentUser['urlaubs_until']);

				display(parsetemplate(gettemplate('options/options_body_vmode'), $parse), FALSE);
			}
			else
			{
				$parse['opt_lst_ord_data']   = "<option value =\"0\"". (($CurrentUser['planet_sort'] == 0) ? " selected": "") .">" . $lang['op_sort_colonization'] . "</option>";
				$parse['opt_lst_ord_data']  .= "<option value =\"1\"". (($CurrentUser['planet_sort'] == 1) ? " selected": "") .">" . $lang['op_sort_coords'] . "</option>";
				$parse['opt_lst_ord_data']  .= "<option value =\"2\"". (($CurrentUser['planet_sort'] == 2) ? " selected": "") .">" . $lang['op_sort_alpha'] . "</option>";
				$parse['opt_lst_cla_data']   = "<option value =\"0\"". (($CurrentUser['planet_sort_order'] == 0) ? " selected": "") .">" . $lang['op_sort_asc'] . "</option>";
				$parse['opt_lst_cla_data']  .= "<option value =\"1\"". (($CurrentUser['planet_sort_order'] == 1) ? " selected": "") .">" . $lang['op_sort_desc'] . "</option>";

				$SkinsFolder 			= opendir(XGP_ROOT . 'styles/skins');
				$parse['opt_skin_data']	= '';
				
				while (($SkinsSubFolder = readdir($SkinsFolder)) !== FALSE)
				{
					if($SkinsSubFolder != '.' && $SkinsSubFolder != '..' && $SkinsSubFolder != '.htaccess' && $SkinsSubFolder != '.svn' && $SkinsSubFolder != 'index.html')
					{
						$parse['opt_skin_data'] .= "<option ";

						if($CurrentUser['dpath'] == $SkinsSubFolder)
							$parse['opt_skin_data'] .= "selected = selected";

						$parse['opt_skin_data'] .= " value=\"".$SkinsSubFolder."\">".$SkinsSubFolder."</option>";
					}
				}

				if ($CurrentUser['authlevel'] > 0)
				{
					$IsProtOn 					= doquery ("SELECT `id_level` FROM {{table}} WHERE `id_owner` = '".intval($CurrentUser['id'])."' LIMIT 1;", 'planets', TRUE);
					$parse['adm_pl_prot_data']	= ($IsProtOn['id_level'] > 0) ? " checked='checked'":'';
					$parse['opt_adm_frame']  	= parsetemplate(gettemplate('options/options_admadd'), $parse);
				}
				$parse['opt_usern_data'] 	= $CurrentUser['username'];
				$parse['opt_mail1_data'] 	= $CurrentUser['email'];
				$parse['opt_mail2_data'] 	= $CurrentUser['email_2'];
				$parse['opt_dpath_data'] 	= $CurrentUser['dpath'];
				$parse['opt_probe_data'] 	= $CurrentUser['spio_anz'];
				$parse['opt_toolt_data'] 	= $CurrentUser['settings_tooltiptime'];
				$parse['opt_fleet_data'] 	= $CurrentUser['settings_fleetactions'];
				$parse['opt_sskin_data'] 	= ($CurrentUser['design'] == 1) ? " checked='checked'":'';
				$parse['opt_noipc_data'] 	= ($CurrentUser['noipcheck'] == 1) ? " checked='checked'":'';
				$parse['opt_allyl_data'] 	= ($CurrentUser['settings_allylogo'] == 1) ? " checked='checked'":'';
				$parse['opt_delac_data'] 	= ($CurrentUser['db_deaktjava'] == 1) ? " checked='checked'":'';
				$parse['user_settings_rep'] = ($CurrentUser['settings_rep'] == 1) ? " checked='checked'":'';
				$parse['user_settings_esp'] = ($CurrentUser['settings_esp'] == 1) ? " checked='checked'":'';
				$parse['user_settings_wri'] = ($CurrentUser['settings_wri'] == 1) ? " checked='checked'":'';
				$parse['user_settings_mis'] = ($CurrentUser['settings_mis'] == 1) ? " checked='checked'":'';
				$parse['user_settings_bud'] = ($CurrentUser['settings_bud'] == 1) ? " checked='checked'":'';
				$parse['db_deaktjava']		= ($CurrentUser['db_deaktjava']  > 0) ? " checked='checked'":'';

				display(parsetemplate(gettemplate('options/options_body'), $parse));
			}
		}
	}
}
?>
