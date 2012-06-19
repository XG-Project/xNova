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

if ($user['authlevel'] != 3) die(message ($lang['404_page']));


$parse	=	$lang;

function ResetUniverse ( $CurrentUser )
{
		doquery( "RENAME TABLE {{table}} TO {{table}}_s", 'planets' );
		doquery( "RENAME TABLE {{table}} TO {{table}}_s", 'users' );

		doquery( "CREATE  TABLE IF NOT EXISTS {{table}} ( LIKE {{table}}_s );", 'planets');
		doquery( "CREATE  TABLE IF NOT EXISTS {{table}} ( LIKE {{table}}_s );", 'users');

		doquery( "TRUNCATE TABLE {{table}}", 'aks');
		doquery( "TRUNCATE TABLE {{table}}", 'alliance');
		doquery( "TRUNCATE TABLE {{table}}", 'banned');
		doquery( "TRUNCATE TABLE {{table}}", 'buddy');
		doquery( "TRUNCATE TABLE {{table}}", 'galaxy');
		doquery( "TRUNCATE TABLE {{table}}", 'errors');
		doquery( "TRUNCATE TABLE {{table}}", 'fleets');
		doquery( "TRUNCATE TABLE {{table}}", 'messages');
		doquery( "TRUNCATE TABLE {{table}}", 'notes');
		doquery( "TRUNCATE TABLE {{table}}", 'rw');
		doquery( "TRUNCATE TABLE {{table}}", 'statpoints');

		$AllUsers  = doquery ("SELECT `username`,`password`,`email`, `email_2`,`authlevel`,`galaxy`,`system`,`planet`, `dpath`, `onlinetime`, `register_time`, `id_planet` FROM {{table}} WHERE 1;", 'users_s');
		$LimitTime = time() - (15 * (24 * (60 * 60)));
		$TransUser = 0;
		while ( $TheUser = mysql_fetch_assoc($AllUsers) )
		{
			if ( $TheUser['onlinetime'] > $LimitTime )
			{
				$UserPlanet     = doquery ("SELECT `name` FROM {{table}} WHERE `id` = '". $TheUser['id_planet']."';", 'planets_s', TRUE);
				if ($UserPlanet['name'] != "")
				{
					$Time	=	time();

					$QryInsertUser  = "INSERT INTO {{table}} SET ";
					$QryInsertUser .= "`username` = '".      $TheUser['username']      ."', ";
					$QryInsertUser .= "`email` = '".         $TheUser['email']         ."', ";
					$QryInsertUser .= "`email_2` = '".       $TheUser['email_2']       ."', ";
					$QryInsertUser .= "`id_planet` = '0', ";
					$QryInsertUser .= "`authlevel` = '".     $TheUser['authlevel']     ."', ";
					$QryInsertUser .= "`dpath` = '".         $TheUser['dpath']         ."', ";
					$QryInsertUser .= "`galaxy` = '".        $TheUser['galaxy']        ."', ";
					$QryInsertUser .= "`system` = '".        $TheUser['system']        ."', ";
					$QryInsertUser .= "`planet` = '".        $TheUser['planet']        ."', ";
					$QryInsertUser .= "`register_time` = '". $TheUser['register_time'] ."', ";
					$QryInsertUser .= "`onlinetime` = '". 	 $Time ."', ";
					$QryInsertUser .= "`password` = '".      $TheUser['password']      ."';";
					doquery( $QryInsertUser, 'users');
					doquery("UPDATE {{table}} SET `bana` = '0' WHERE `id` > '1'", "users");

					$NewUser        = doquery("SELECT `id` FROM {{table}} WHERE `username` = '". $TheUser['username'] ."' LIMIT 1;", 'users', TRUE);

					CreateOnePlanetRecord ($TheUser['galaxy'], $TheUser['system'], $TheUser['planet'], $NewUser['id'], $UserPlanet['name'], TRUE);


					doquery("UPDATE {{table}} SET `id_level` = '".$TheUser['authlevel']."' WHERE `id_owner` = '".$NewUser['id']."'", "planets");
					$PlanetID       = doquery("SELECT `id` FROM {{table}} WHERE `id_owner` = '". $NewUser['id'] ."' LIMIT 1;", 'planets', TRUE);

					$QryUpdateUser  = "UPDATE {{table}} SET ";
					$QryUpdateUser .= "`id_planet` = '".      $PlanetID['id'] ."', ";
					$QryUpdateUser .= "`current_planet` = '". $PlanetID['id'] ."' ";
					$QryUpdateUser .= "WHERE ";
					$QryUpdateUser .= "`id` = '".             $NewUser['id']  ."';";
					doquery( $QryUpdateUser, 'users');
					$TransUser++;
				}
			}
		}

		update_config ( 'users_amount' , $TransUser );
		doquery("DROP TABLE {{table}}", 'planets_s');
		doquery("DROP TABLE {{table}}", 'users_s');
}


if ($_POST)
{
 $Log	.=	"\n".$lang['log_the_user'].$user['username']." ".$lang['log_reseteo'].":\n";
 if ($_POST['resetall']	!=	'on')
 {
	// HANGARES Y DEFENSAS
	if ($_POST['defenses']	==	'on'){
		doquery("UPDATE {{table}} SET `misil_launcher` = '0', `small_laser` = '0', `big_laser` = '0',
									`gauss_canyon` = '0', `ionic_canyon` = '0', `buster_canyon` = '0',
									`small_protection_shield` = '0', `big_protection_shield` = '0',
									`interceptor_misil` = '0', `interplanetary_misil` = '0'", "planets");
		$Log	.=	$lang['log_defenses']."\n";}

	if ($_POST['ships']	==	'on'){
		doquery("UPDATE {{table}} SET `small_ship_cargo` = '0', `big_ship_cargo` = '0', `light_hunter` = '0',
									`heavy_hunter` = '0', `crusher` = '0', `battle_ship` = '0',
									`colonizer` = '0', `recycler` = '0', `spy_sonde` = '0',
									`bomber_ship` = '0', `solar_satelit` = '0', `destructor` = '0',
									`dearth_star` = '0', `battleship` = '0'", "planets");
		$Log	.=	$lang['log_ships']."\n";}

	if ($_POST['h_d']	==	'on'){
		doquery("UPDATE {{table}} SET `b_hangar` = '0', `b_hangar_plus` = '0', `b_hangar_id` = ''", "planets");
		$Log	.=	$lang['log_c_hangar']."\n";}



	// EDIFICIOS
	if ($_POST['edif_p']	==	'on'){
		doquery("UPDATE {{table}} SET `metal_mine` = '0', `crystal_mine` = '0', `deuterium_sintetizer` = '0',
									`solar_plant` = '0', `fusion_plant` = '0', `robot_factory` = '0',
									`nano_factory` = '0', `hangar` = '0', `metal_store` = '0',
									`crystal_store` = '0', `deuterium_store` = '0', `laboratory` = '0',
									`terraformer` = '0', `ally_deposit` = '0', `silo` = '0' WHERE `planet_type` = '1'", "planets");
		$Log	.=	$lang['log_buildings_planet']."\n";}

	if ($_POST['edif_l']	==	'on'){
		doquery("UPDATE {{table}} SET `mondbasis` = '0', `phalanx` = '0', `sprungtor` = '0',
									`last_jump_time` = '0', `fusion_plant` = '0', `robot_factory` = '0',
									`hangar` = '0', `metal_store` = '0', `crystal_store` = '0',
									`deuterium_store` = '0', `ally_deposit` = '0' WHERE `planet_type` = '3'", "planets");
		$Log	.=	$lang['log_buildings_moon']."\n";}

	if ($_POST['edif']	==	'on'){
		doquery("UPDATE {{table}} SET `b_building` = '0', `b_building_id` = ''", "planets");
		$Log	.=	$lang['log_c_buildings']."\n";}



	// INVESTIGACIONES Y OFICIALES
	if ($_POST['inves']	==	'on'){
		doquery("UPDATE {{table}} SET `spy_tech` = '0', `computer_tech` = '0', `military_tech` = '0',
									`defence_tech` = '0', `shield_tech` = '0', `energy_tech` = '0',
									`hyperspace_tech` = '0', `combustion_tech` = '0', `impulse_motor_tech` = '0',
									`hyperspace_motor_tech` = '0', `laser_tech` = '0', `ionic_tech` = '0',
									`buster_tech` = '0', `intergalactic_tech` = '0', `expedition_tech` = '0',
									`graviton_tech` = '0'", "users");
		$Log	.=	$lang['log_researchs']."\n";}

	if ($_POST['ofis']	==	'on'){
		doquery("UPDATE {{table}} SET `rpg_geologue` = '0', `rpg_amiral` = '0', `rpg_ingenieur` = '0',
									`rpg_technocrate` = '0'", "users");
		$Log	.=	$lang['log_officiers']."\n";}

	if ($_POST['inves_c']	==	'on'){
		doquery("UPDATE {{table}} SET `b_tech` = '0', `b_tech_id` = '0'", "planets");
		doquery("UPDATE {{table}} SET `b_tech_planet` = '0'", "users");
		$Log	.=	$lang['log_c_researchs']."\n";}



	// RECURSOS
	if ($_POST['dark']	==	'on'){
		doquery("UPDATE {{table}} SET `darkmatter` = '0'", "users");
		$Log	.=	$lang['log_darkmatter']."\n";}

	if ($_POST['resources']	==	'on'){
		doquery("UPDATE {{table}} SET `metal` = '0', `crystal` = '0', `deuterium` = '0'", "planets");
		$Log	.=	$lang['log_resources']."\n";}



	// GENERAL
	if ($_POST['notes']	==	'on'){
		doquery("TRUNCATE TABLE {{table}}", 'notes');
		$Log	.=	$lang['log_notes']."\n";}

	if ($_POST['rw']	==	'on'){
		doquery("TRUNCATE TABLE {{table}}", 'rw');
		$Log	.=	$lang['log_rw']."\n";}

	if ($_POST['friends']	==	'on'){
		doquery("TRUNCATE TABLE {{table}}", 'buddy');
		$Log	.=	$lang['log_friends']."\n";}

	if ($_POST['alliances']	==	'on'){
		doquery("TRUNCATE TABLE {{table}}", 'alliance');
		doquery("UPDATE {{table}} SET `ally_id` = '0', `ally_name` = '', `ally_request` = '0',
									`ally_request_text` = 'NULL', `ally_register_time` = '0', `ally_rank_id` = '0'", "users");
		$Log	.=	$lang['log_alliances']."\n";}


	if ($_POST['fleets']	==	'on'){
		doquery( "TRUNCATE TABLE {{table}}", 'fleets');
		$Log	.=	$lang['log_fleets']."\n";}

	if ($_POST['errors']	==	'on'){
		doquery( "TRUNCATE TABLE {{table}}", 'errors');
		$Log	.=	$lang['log_errors']."\n";}

	if ($_POST['banneds']	==	'on'){
		doquery("TRUNCATE TABLE {{table}}", 'banned');
		doquery("UPDATE {{table}} SET `bana` = '0', `banaday` = '0' WHERE `id` > '1'", "users");
		$Log	.=	$lang['log_banneds']."\n";}

	if ($_POST['messages']	==	'on'){
		doquery("TRUNCATE TABLE {{table}}", 'messages');
		doquery("UPDATE {{table}} SET `new_message` = '0'", "users");
		$Log	.=	$lang['log_messages']."\n";}

	if ($_POST['statpoints']	==	'on'){
		doquery("TRUNCATE TABLE {{table}}", 'statpoints');
		$Log	.=	$lang['log_statpoints']."\n";}

	if ($_POST['moons']	==	'on'){
		doquery("DELETE FROM {{table}} WHERE `planet_type` = '3'", 'planets');
		doquery("UPDATE {{table}} SET `id_luna` = '0'", 'galaxy');
		$Log	.=	$lang['log_moons']."\n";}
 }
 else // REINICIAR TODO
 {
	ResetUniverse ( $user );
	$Log	.=	$lang['log_all_uni']."\n";
 }

	LogFunction($Log, "ResetLog", $LogCanWork);
	$parse['good']	=	'<tr><th colspan="2"><center><font color=lime>'.$lang['re_reset_excess'].'</font></center></th></tr>';
}


display(parsetemplate(gettemplate('adm/ResetBody'), $parse), FALSE, '', TRUE, FALSE);
?>