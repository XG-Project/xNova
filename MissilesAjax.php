<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('XGP_ROOT',	'./');

include(XGP_ROOT . 'global.php');

$g     = intval($_GET['galaxy']);
$s     = intval($_GET['system']);
$i     = intval($_GET['planet']);
$anz     = intval($_POST['SendMI']);
$pziel     = $_POST['Target'];

if ($anz < 0)
{
	$anz = 0;
}

$currentplanet	= doquery("SELECT * FROM {{table}} WHERE id={$user['current_planet']}",'planets',TRUE);
$iraks          = $currentplanet['interplanetary_misil'];
$tempvar1      	= abs($s - $currentplanet['system']);
$tempvar2      	= ($user['impulse_motor_tech'] * 2) - 1;
$tempvar3      	= doquery("SELECT * FROM {{table}} WHERE galaxy = ".$g."  AND system = ".$s." AND planet = ".$i." AND planet_type = 1 limit 1",  'planets',TRUE);
$tempvar4      	= doquery("SELECT * FROM {{table}} WHERE id = ".$tempvar3['id_owner']. " limit 1",'users', TRUE);
$UserPoints     = doquery("SELECT * FROM {{table}} WHERE `stat_type` =  '1' AND `stat_code` = '1' AND `id_owner` = '". $user['id'] ."';",  'statpoints', TRUE);
$User2Points     = doquery("SELECT * FROM {{table}} WHERE `stat_type` =  '1' AND `stat_code` = '1' AND `id_owner` = '". $tempvar3['id_owner']  ."';", 'statpoints', TRUE);

$MyGameLevel     = $UserPoints['total_points'];
$HeGameLevel     = $User2Points['total_points'];

$error = "";
if ($currentplanet['silo'] < 4)
{
	$error .= $lang['ma_silo_level'].'<br>';
	$errors++;
}
if ($user['impulse_motor_tech'] == 0)
{
	$error .= $lang['ma_impulse_drive_required'].'<br>';
	$errors++;
}
if ($tempvar1 >= $tempvar2 || $g != $currentplanet['galaxy'])
{
	$error .= $lang['ma_not_send_other_galaxy'].'<br>';
	$errors++;
}
if (!$tempvar3)
{
	$error .= $lang['ma_planet_doesnt_exists'].'<br>';
	$errors++;
}
if ($anz > $iraks)
{
	$error .= $lang['ma_cant_send'] . $anz . $lang['ma_missile'] . $iraks.'<br>';
	$errors++;
}
if (((!is_numeric($pziel) && $pziel != "all") or ($pziel < 0 or $pziel > 8)))
{
	$error .= $lang['ma_wrong_target'].'<br>';
	$errors++;
}
if ($iraks==0)
{
	$error .= $lang['ma_no_missiles'].'<br>';
	$errors++;
}
if ($anz==0)
{
	$error .= $lang['ma_add_missile_number'].'<br>';
	$errors++;
}
if ($tempvar4['onlinetime'] >= (time()-60 * 60 * 24 * 7)){
	if ( is_weak ( $MyGameLevel , $HeGameLevel ) )
	{
		$error .= $lang['fl_week_player'].'<br>';
		$errors++;
	}elseif ( is_strong ( $MyGameLevel , $HeGameLevel ) ){
		$error .= $lang['fl_strong_player'].'<br>';
		$errors++;
	}
}
if ($tempvar4['urlaubs_modus']==1){
	$error .= $lang['fl_in_vacation_player'].'<br>';
	$errors++;
}

if ($errors != 0)
{
	message ($error, "game.php?page=galaxy&mode=0&galaxy=".$g."&system=".$s, 3);
}

$ziel_id = $tempvar3["id_owner"];

$flugzeit = round(((30 + (60 * $tempvar1)) * 2500) / read_config ( 'fleet_speed' ) );

$DefenseLabel =
array(
0 => $lang['tech'][401],
1 => $lang['tech'][402],
2 => $lang['tech'][403],
3 => $lang['tech'][404],
4 => $lang['tech'][405],
5 => $lang['tech'][406],
6 => $lang['tech'][407],
7 => $lang['tech'][408],
'all' => $lang['ma_all']);


doquery("INSERT INTO {{table}} SET
fleet_owner = ".$user['id'].",
fleet_mission = 10,
fleet_amount = ".$anz.",
fleet_array = '503,".$anz."',
fleet_start_time = '".(time() + $flugzeit)."',
fleet_start_galaxy = '".$currentplanet['galaxy']."',
fleet_start_system = '".$currentplanet['system']."',
fleet_start_planet ='".$currentplanet['planet']."',
fleet_start_type = 1,
fleet_end_time = '".(time() + $flugzeit+1)."',
fleet_end_stay = 0,
fleet_end_galaxy = '".$g."',
fleet_end_system = '".$s."',
fleet_end_planet = '".$i."',
fleet_end_type = 1,
fleet_target_obj = '".$pziel."',
fleet_resource_metal = 0,
fleet_resource_crystal = 0,
fleet_resource_deuterium = 0,
fleet_target_owner = '".$ziel_id."',
fleet_group = 0,
fleet_mess = 0,
start_time = ".time().";", 'fleets');

doquery("UPDATE {{table}} SET interplanetary_misil =  (interplanetary_misil - ".$anz.") WHERE id =  '".$user['current_planet']."'", 'planets');

message("<b>".$anz."</b>". $lang['ma_missiles_sended'] .$DefenseLabel[$pziel], "game.php?page=overview", 3);
?>