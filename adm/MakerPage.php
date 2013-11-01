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

if ($EditUsers != 1) die(message ($lang['404_page']));

$parse	=	$lang;


switch ($_GET[page])
{
	case 'new_user':
	$name		=	$_POST['name'];
	$pass 		= 	$_POST['password'];
	$email 		= 	$_POST['email'];
	$galaxy		=	$_POST['galaxy'];
	$system		=	$_POST['system'];
	$planet		=	$_POST['planet'];
	$auth		=	$_POST['authlevel'];
	$time		=	time();
	$i			=	0;


	for ($L = 0; $L < 4; $L++)
	{
		if ($user['authlevel'] == 3)
			$parse['uplvels']	.= "<option value=\"".$L."\">".$lang['rank'][$L]."</option>";
		else
			$parse['uplvels']	 = '<option value="0">'.$lang['rank'][0].'</option>';
	}


	if ($_POST)
	{
		$CheckUser = doquery("SELECT `username` FROM {{table}} WHERE `username` = '" . mysql_escape_value($_POST['name']) . "' LIMIT 1", "users", TRUE);
		$CheckMail = doquery("SELECT `email` FROM {{table}} WHERE `email` = '" . mysql_escape_value($_POST['email']) . "' LIMIT 1", "users", TRUE);
		$CheckRows = doquery("SELECT * FROM {{table}} WHERE `galaxy` = '".$galaxy."' AND `system` = '".$system."' AND `planet` = '".$planet."' LIMIT 1", "galaxy", TRUE);


		if (!ctype_digit($galaxy) &&  !ctype_digit($system) && !ctype_digit($planet)){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['only_numbers'].'</tr></th>';
			$i++;}
		elseif ($galaxy > MAX_GALAXY_IN_WORLD || $system > MAX_SYSTEM_IN_GALAXY || $planet > MAX_PLANET_IN_SYSTEM || $galaxy < 1 || $system < 1 || $planet < 1){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_coord'].'</tr></th>';
			$i++;}

		if (!$name || !$pass || !$email || !$galaxy || !$system || !$planet){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_complete_all'].'</tr></th>';
			$i++;}

		if (!valid_email(strip_tags($email))){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_email2'].'</tr></th>';
			$i++;}

		if ($CheckUser){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_name'].'</tr></th>';
			$i++;}

		if ($CheckMail){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_email'].'</tr></th>';
			$i++;}

		if ($CheckRows){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_galaxy'].'</tr></th>';
			$i++;}

		if (strlen($pass) < 4){
			$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_passw'].'</tr></th>';
			$i++;}



		if ($i	==	'0'){
			$Query1  = "INSERT INTO {{table}} SET ";
			$Query1 .= "`username` = '" . mysql_escape_value(strip_tags($name)) . "', ";
			$Query1 .= "`email` = '" . mysql_escape_value($email) . "', ";
			$Query1 .= "`email_2` = '" . mysql_escape_value($email) . "', ";
			$Query1 .= "`ip_at_reg` = '" . $_SERVER["REMOTE_ADDR"] . "', ";
			$Query1 .= "`id_planet` = '0', ";
			$Query1 .= "`register_time` = '" .$time. "', ";
			$Query1 .= "`onlinetime` = '" .$time. "', ";
			$Query1 .= "`authlevel` = '" .$auth. "', ";
			$Query1 .= "`password`='" . md5($pass) . "';";
			doquery($Query1, "users");

			$ID_USER 	= doquery("SELECT `id` FROM {{table}} WHERE `username` = '" . mysql_escape_value($name) . "' LIMIT 1", "users", TRUE);

			CreateOnePlanetRecord ($galaxy, $system, $planet, $ID_USER['id'], $UserPlanet, TRUE);

			$ID_PLANET 	= doquery("SELECT `id` FROM {{table}} WHERE `id_owner` = '". $ID_USER['id'] ."' LIMIT 1" , "planets", TRUE);

			doquery("UPDATE {{table}} SET `id_level` = '".$auth."' WHERE `id` = '".$ID_PLANET['id']."'", "planets");

			$QryUpdateUser = "UPDATE {{table}} SET ";
			$QryUpdateUser .= "`id_planet` = '" . $ID_PLANET['id'] . "', ";
			$QryUpdateUser .= "`current_planet` = '" . $ID_PLANET['id'] . "', ";
			$QryUpdateUser .= "`galaxy` = '" . $galaxy . "', ";
			$QryUpdateUser .= "`system` = '" . $system . "', ";
			$QryUpdateUser .= "`planet` = '" . $planet . "' ";
			$QryUpdateUser .= "WHERE ";
			$QryUpdateUser .= "`id` = '" . $ID_USER['id'] . "' ";
			$QryUpdateUser .= "LIMIT 1;";
			doquery($QryUpdateUser, "users");


			$Log	.=	"\n".$lang['log_new_user_title']."\n";
			$Log	.=	$lang['log_the_user'].$user['username'].$lang['log_new_user'].":\n";
			$Log	.=	$lang['log_new_user_name'].": ".$name."\n";
			$Log	.=	$lang['log_new_user_coor'].": [".$galaxy.":".$system.":".$planet."]\n";
			$Log	.=	$lang['log_new_user_email'].": ".$email."\n";
			$Log	.=	$lang['log_new_user_auth'].": ".$lang['new_range11'][$auth]."\n";

			LogFunction($Log, "GeneralLog", $LogCanWork);
			$parse['display']	=	'<tr><th colspan="2"><font color=lime>'.$lang['new_user_success'].'</font></tr></th>';
		}
	}

	display(parsetemplate(gettemplate('adm/CreateNewUserBody'), $parse), FALSE, '', TRUE, FALSE);
	break;

	case 'new_moon':
	if ($_POST && $_POST['add_moon'])
	{
		$PlanetID  	= $_POST['add_moon'];
		$MoonName  	= $_POST['name'];
		$Diameter	= $_POST['diameter'];
		$TempMin	= $_POST['temp_min'];
		$TempMax	= $_POST['temp_max'];
		$FieldMax	= $_POST['field_max'];

		$MoonPlanet		= 	doquery("SELECT * FROM {{table}} WHERE `id` = '".$PlanetID."' AND `planet_type` = '1'", 'planets', TRUE);
		$MoonGalaxy		= 	doquery("SELECT * FROM {{table}} WHERE `id_planet` = '".$PlanetID."'", 'galaxy', TRUE);


	if ($MoonPlanet && is_numeric($PlanetID))
	{
		if ($MoonGalaxy['id_luna'] == 0 && $MoonPlanet['planet_type'] == 1 && $MoonPlanet['destruyed'] == 0)
		{
			$Galaxy    = $MoonPlanet['galaxy'];
			$System    = $MoonPlanet['system'];
			$Planet    = $MoonPlanet['planet'];
			$Owner     = $MoonPlanet['id_owner'];
			$MoonID    = time();


			if ($_POST['diameter_check'] == 'on')
			{
				$SizeMin	= 4500;
				$SizeMax    = 9999;
				$size       = rand ($SizeMin, $SizeMax);
			}
			elseif ($_POST['diameter_check'] != 'on' && is_numeric($Diameter))
			{
				$size	=	$Diameter;
			}
			else
			{
				$parse['display']	=	"<tr><th colspan=3><font color=red>".$lang['only_numbers']."</font></th></tr>";
			}


			if ($_POST['temp_check']	==	'on')
			{
				$maxtemp	= $MoonPlanet['temp_max'] - rand(10, 45);
				$mintemp	= $MoonPlanet['temp_min'] - rand(10, 45);
			}
			elseif ($_POST['temp_check']	!=	'on' && is_numeric($TempMax) && is_numeric($TempMin) )
			{
				$maxtemp	=	$TempMax;
				$mintemp	=	$TempMin;
			}
			else
			{
				$parse['display']	=	"<tr><th colspan=3><font color=red>".$lang['only_numbers']."</font></th></tr>";
			}

				$QueryFind	=	doquery("SELECT `id_level` FROM {{table}} WHERE `id` = '".$PlanetID."'", "planets", TRUE);

				$QryInsertMoonInPlanet  = "INSERT INTO {{table}} SET ";
				$QryInsertMoonInPlanet .= "`name` = '".$MoonName."', ";
				$QryInsertMoonInPlanet .= "`id_owner` = '". $Owner ."', ";
				$QryInsertMoonInPlanet .= "`id_level` = '". $QueryFind['id_level'] ."', ";
				$QryInsertMoonInPlanet .= "`galaxy` = '". $Galaxy ."', ";
				$QryInsertMoonInPlanet .= "`system` = '". $System ."', ";
				$QryInsertMoonInPlanet .= "`planet` = '". $Planet ."', ";
				$QryInsertMoonInPlanet .= "`last_update` = '". time() ."', ";
				$QryInsertMoonInPlanet .= "`planet_type` = '3', ";
				$QryInsertMoonInPlanet .= "`image` = 'mond', ";
				$QryInsertMoonInPlanet .= "`diameter` = '". $size ."', ";
				$QryInsertMoonInPlanet .= "`field_max` = '".$FieldMax."', ";
				$QryInsertMoonInPlanet .= "`temp_min` = '". $mintemp ."', ";
				$QryInsertMoonInPlanet .= "`temp_max` = '". $maxtemp ."', ";
				$QryInsertMoonInPlanet .= "`metal` = '0', ";
				$QryInsertMoonInPlanet .= "`metal_perhour` = '0', ";
				$QryInsertMoonInPlanet .= "`metal_max` = '".BASE_STORAGE_SIZE."', ";
				$QryInsertMoonInPlanet .= "`crystal` = '0', ";
				$QryInsertMoonInPlanet .= "`crystal_perhour` = '0', ";
				$QryInsertMoonInPlanet .= "`crystal_max` = '".BASE_STORAGE_SIZE."', ";
				$QryInsertMoonInPlanet .= "`deuterium` = '0', ";
				$QryInsertMoonInPlanet .= "`deuterium_perhour` = '0', ";
				$QryInsertMoonInPlanet .= "`deuterium_max` = '".BASE_STORAGE_SIZE."';";
				doquery( $QryInsertMoonInPlanet , 'planets');

				$QryGetMoonIdFromLunas  = "SELECT * FROM {{table}} WHERE ";
				$QryGetMoonIdFromLunas .= "`galaxy` = '".  $Galaxy ."' AND ";
				$QryGetMoonIdFromLunas .= "`system` = '".  $System ."' AND ";
				$QryGetMoonIdFromLunas .= "`planet` = '". $Planet ."' AND ";
				$QryGetMoonIdFromLunas .= "`planet_type` = '3';";
				$PlanetRow = doquery( $QryGetMoonIdFromLunas , 'planets', TRUE);

				$QryUpdateMoonInGalaxy  = "UPDATE {{table}} SET ";
				$QryUpdateMoonInGalaxy .= "`id_luna` = '". $PlanetRow['id'] ."', ";
				$QryUpdateMoonInGalaxy .= "`luna` = '0' ";
				$QryUpdateMoonInGalaxy .= "WHERE ";
				$QryUpdateMoonInGalaxy .= "`galaxy` = '". $Galaxy ."' AND ";
				$QryUpdateMoonInGalaxy .= "`system` = '". $System ."' AND ";
				$QryUpdateMoonInGalaxy .= "`planet` = '". $Planet ."';";
				doquery( $QryUpdateMoonInGalaxy , 'galaxy');

				$parse['display']	=	"<tr><th colspan=3><font color=lime>".$lang['mo_moon_added']."</font></th></tr>";
			}
			else
			{
				$parse['display']	=	"<tr><th colspan=3><font color=red>".$lang['mo_moon_unavaible']."</font></th></tr>";
			}
		}
		else
		{
			$parse['display']	=	"<tr><th colspan=3><font color=red>".$lang['mo_planet_doesnt_exist']."</font></th></tr>";
		}
	}
	elseif($_POST && $_POST['del_moon'])
	{
		$MoonID	= $_POST['del_moon'];

		$MoonSelected  			= doquery("SELECT * FROM {{table}} WHERE `id` = '". $MoonID ."'", 'planets', TRUE);
		if ($MoonSelected && is_numeric($MoonID))
		{
			if ($MoonSelected['planet_type'] == 3)
			{
				$Galaxy    = $MoonSelected['galaxy'];
				$System    = $MoonSelected['system'];
				$Planet    = $MoonSelected['planet'];

				doquery("DELETE FROM {{table}} WHERE `galaxy` ='".$Galaxy."' AND `system` ='".$System."' AND `planet` ='".$Planet."' AND `planet_type` = '3'",'planets');

				$QryUpdateGalaxy  = "UPDATE {{table}} SET ";
				$QryUpdateGalaxy .= "`id_luna` = '0' ";
				$QryUpdateGalaxy .= "WHERE ";
				$QryUpdateGalaxy .= "`galaxy` = '". $Galaxy ."' AND ";
				$QryUpdateGalaxy .= "`system` = '". $System ."' AND ";
				$QryUpdateGalaxy .= "`planet` = '". $Planet ."' ";
				$QryUpdateGalaxy .= "LIMIT 1;";
				doquery( $QryUpdateGalaxy , 'galaxy');

				$parse['display2']	=	"<tr><th colspan=3><font color=lime>".$lang['mo_moon_deleted']."</font></th></tr>";
			}
			else
			{
				$parse['display2']	=	"<tr><th colspan=3><font color=red>".$lang['mo_moon_only']."</font></th></tr>";
			}
		}
		else
		{
			$parse['display2']	=	"<tr><th colspan=3><font color=red>".$lang['mo_moon_doesnt_exist']."</font></th></tr>";
		}
	}

	display (parsetemplate(gettemplate("adm/MoonOptionsBody"), $parse), FALSE, '', TRUE, FALSE);
	break;

	case 'new_planet':
	$mode      = $_POST['mode'];

	if ($_POST && $mode == 'agregar')
	{
   		$id          = $_POST['id'];
    	$galaxy      = $_POST['galaxy'];
   	 	$system      = $_POST['system'];
   	 	$planet      = $_POST['planet'];
		$name        = $_POST['name'];



		$field_max   = $_POST['field_max'];

		$i	=	0;
		$QueryS	=	doquery("SELECT * FROM {{table}} WHERE `galaxy` = '".$galaxy."' AND `system` = '".$system."' AND `planet` = '".$planet."'", "galaxy", TRUE);
		$QueryS2	=	doquery("SELECT * FROM {{table}} WHERE `id` = '".$id."'", "users", TRUE);
		if (is_numeric($_POST['id']) && isset($_POST['id']) && !$QueryS && $QueryS2)
		{
    		if ($galaxy < 1 or $system < 1 or $planet < 1 or !is_numeric($galaxy) or !is_numeric($system) or !is_numeric($planet)){
    			$Error	.=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_all'].'</font></th></tr>';
				$i++;}

			if ($galaxy > MAX_GALAXY_IN_WORLD or $system > MAX_SYSTEM_IN_GALAXY or $planet > MAX_PLANET_IN_SYSTEM){
				$Error	.=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_all2'].'</font></th></tr>';
				$i++;}

			if ($i	==	0)
			{
				CreateOnePlanetRecord ($galaxy, $system, $planet, $id, '', '', FALSE) ;
				$QueryS3	=	doquery("SELECT * FROM {{table}} WHERE `id_owner` = '".$id."' LIMIT 1", "planets", TRUE);

				$QryUpdatePlanet  = "UPDATE {{table}} SET ";
				if ($field_max > 0 && is_numeric($field_max))
					$QryUpdatePlanet .= "`field_max` = '".$field_max."', ";
				if (strlen($name) > 0)
					$QryUpdatePlanet .= "`name` = '".$name."', ";
				$QryUpdatePlanet .= "`id_level` = '".$QueryS3['id_level']."' ";
				$QryUpdatePlanet .= "WHERE ";
				$QryUpdatePlanet .= "`galaxy` = '". $galaxy ."' AND ";
				$QryUpdatePlanet .= "`system` = '". $system ."' AND ";
				$QryUpdatePlanet .= "`planet` = '". $planet ."' AND ";
				$QryUpdatePlanet .= "`planet_type` = '1'";
				doquery($QryUpdatePlanet , 'planets');

    			$parse['display']	=	'<tr><th colspan="2"><font color=lime>'.$lang['po_complete_succes'].'</font></th></tr>';
			}
			else
			{
				$parse['display']	=	$Error;
			}
		}
		else
		{
			$parse['display']	=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_all'].'</font></th></tr>';
		}
	}
	elseif ($_POST && $mode == 'borrar')
	{
		$id	=	$_POST['id'];
		if (is_numeric($id) && isset($id))
		{
			$QueryS	=	doquery("SELECT * FROM {{table}} WHERE `id` = '".$id."'", "planets", TRUE);

			if ($QueryS)
			{
				if ($QueryS['planet_type'] == '1')
				{
					$QueryS2	=	doquery("SELECT * FROM {{table}} WHERE `id_planet` = '".$id."'", "galaxy", TRUE);
					if ($QueryS2['id_luna'] > 0)
					{
						doquery("DELETE FROM {{table}} WHERE `galaxy` = '".$QueryS['galaxy']."' AND `system` = '".$QueryS['system']."' AND
							`planet` = '".$QueryS['planet']."' AND `planet_type` = '3'", "planets");
					}
					doquery("DELETE FROM {{table}} WHERE `id` = '".$id."'", 'planets');
    				doquery("DELETE FROM {{table}} WHERE `id_planet` ='".$id."'", 'galaxy');
					$Error	.=	'<tr><th colspan="2"><font color=lime>'.$lang['po_complete_succes2'].'</font></th></tr>';
				}
				else
				{
					$Error	.=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_invalid3'].'</font></th></tr>';
				}
			}
			else
			{
				$Error	.=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_invalid2'].'</font></th></tr>';
			}
		}
		else
		{
			$Error	.=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_invalid'].'</font></th></tr>';
		}
		$parse['display2']	=	$Error;
	}

	display (parsetemplate(gettemplate('adm/PlanetOptionsBody'),  $parse), FALSE, '', TRUE, FALSE);
	break;

	default:

	display( parsetemplate(gettemplate('adm/CreatorBody'), $parse), FALSE, '', TRUE, FALSE);
	break;
}
?>
