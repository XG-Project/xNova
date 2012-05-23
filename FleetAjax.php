<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  ,  TRUE);
define('INSTALL' , FALSE);
define('XGP_ROOT',	'./');

include(XGP_ROOT . 'global.php');

$UserSpyProbes  = $planetrow['spy_sonde'];
$UserRecycles   = $planetrow['recycler'];
$UserDeuterium  = $planetrow['deuterium'];
$UserMissiles   = $planetrow['interplanetary_misil'];

$fleet          = array();
$speedalls      = array();
$PartialFleet   = FALSE;
$PartialCount   = 0;

foreach ($reslist['fleet'] as $Node => $ShipID)
{
	$TName = "ship".$ShipID;
	if ($ShipID > 200 && $ShipID < 300 && $_POST[$TName] > 0)
	{
		if ($_POST[$TName] > $planetrow[$resource[$ShipID]])
		{
			$fleet['fleetarray'][$ShipID]   = $planetrow[$resource[$ShipID]];
			$fleet['fleetlist']            .= $ShipID .",". $planetrow[$resource[$ShipID]] .";";
			$fleet['amount']               += $planetrow[$resource[$ShipID]];
			$PartialCount                  += $planetrow[$resource[$ShipID]];
			$PartialFleet                   = TRUE;
		}
		else
		{
			$fleet['fleetarray'][$ShipID]   = $_POST[$TName];
			$fleet['fleetlist']            .= $ShipID .",". $_POST[$TName] .";";
			$fleet['amount']               += $_POST[$TName];
			$speedalls[$ShipID]             = $_POST[$TName];
		}
	}
}

if ($PartialFleet == TRUE)
{
	if ( $PartialCount < 1 )
	{
		$ResultMessage = "610; ".$lang['fa_not_enough_probes']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
		die ($ResultMessage);
	}
}

$galaxy          = intval($_POST['galaxy']);
if ($galaxy > MAX_GALAXY_IN_WORLD || $galaxy < 1)
{
	$ResultMessage = "602; ".$lang['fa_galaxy_not_exist']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ( $ResultMessage );
}

$system = intval($_POST['system']);

if ($system > MAX_SYSTEM_IN_GALAXY || $system < 1)
{
	$ResultMessage = "602; ".$lang['fa_system_not_exist']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ( $ResultMessage );
}

$planet = intval($_POST['planet']);

if ($planet > MAX_PLANET_IN_SYSTEM || $planet < 1)
{
	$ResultMessage = "602; ".$lang['fa_planet_not_exist']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ( $ResultMessage );
}

$FleetArray = $fleet['fleetarray'];

$CurrentFlyingFleets = doquery("SELECT COUNT(fleet_id) AS `Nbre` FROM {{table}} WHERE `fleet_owner` = '".$user['id']."';", 'fleets', TRUE);
$CurrentFlyingFleets = $CurrentFlyingFleets["Nbre"];

$QrySelectEnemy  = "SELECT * FROM {{table}} ";
$QrySelectEnemy .= "WHERE ";
$QrySelectEnemy .= "`galaxy` = '". intval($_POST['galaxy']) ."' AND ";
$QrySelectEnemy .= "`system` = '". intval($_POST['system']) ."' AND ";
$QrySelectEnemy .= "`planet` = '". intval($_POST['planet']) ."' AND ";
$QrySelectEnemy .= "`planet_type` = '". intval($_POST['planettype']) ."';";
$TargetRow = doquery( $QrySelectEnemy, 'planets', TRUE);

if ($TargetRow['id_owner'] == '')
{
	$TargetUser = $user;
}
elseif ($TargetRow['id_owner'] != '')
{
	$TargetUser = doquery("SELECT * FROM {{table}} WHERE `id` = '". $TargetRow['id_owner'] ."';", 'users', TRUE);
}

// invisible debris by jstar
if ($_POST['mission']== 8)
{
	$TargetGPlanet = doquery("SELECT invisible_start_time, metal, crystal FROM {{table}} WHERE galaxy = '". intval($_POST['galaxy']) ."' AND system = '". intval($_POST['system']) ."' AND planet = '". intval($_POST['planet']) ."'", "galaxy",TRUE);

	if($TargetGPlanet['metal'] == 0 && $TargetGPlanet['crystal'] == 0 && time() > ($TargetGPlanet['invisible_start_time']+DEBRIS_LIFE_TIME))
	{
		die();
	}
}

$UserPoints    = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". $user['id'] ."';", 'statpoints', TRUE);
$User2Points   = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". $TargetUser['id'] ."';", 'statpoints', TRUE);

$CurrentPoints = $UserPoints['total_points'];
$TargetPoints  = $User2Points['total_points'];
$TargetVacat   = $TargetUser['urlaubs_modus'];

if ((Fleets::get_max_fleets ( $user[$resource[108]] , $user['rpg_amiral'] )) <= $CurrentFlyingFleets)
{
	$ResultMessage = "612; ".$lang['fa_no_more_slots']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ($ResultMessage);
}

if (!is_array($FleetArray))
{
	$ResultMessage = "618; ".$lang['fa_no_recyclers']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ($ResultMessage);
}

if (!(($_POST["mission"] == 6) OR ($_POST["mission"] == 8)))
{
	$ResultMessage = "618; ".$lang['fa_mission_not_available']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ($ResultMessage);
}

foreach ($FleetArray as $Ships => $Count)
{
	if ($Count > $planetrow[$resource[$Ships]])
	{
		$ResultMessage = "611; ".$lang['fa_no_ships']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
		die ( $ResultMessage );
	}
}

if ($TargetVacat && $_POST['mission'] != 8)
{
	$ResultMessage = "605; ".$lang['fa_vacation_mode']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ($ResultMessage);
}

if($user['urlaubs_modus'])
{
	$ResultMessage = "620; ".$lang['fa_vacation_mode_current']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ($ResultMessage);
}

if($TargetUser['onlinetime'] >= (time()-60 * 60 * 24 * 7))
{
	if ( is_weak ( $CurrentPoints , $TargetPoints ) && $TargetRow['id_owner'] != '' && $_POST['mission'] == 6 )
	{
		$ResultMessage = "603; ".$lang['fa_week_player']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
		die ( $ResultMessage );
	}

	if ( is_strong ( $CurrentPoints , $TargetPoints ) && $TargetRow['id_owner'] != '' && $_POST['mission'] == 6 )
	{
		$ResultMessage = "604; ".$lang['fa_strong_player']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
		die ( $ResultMessage );
	}
}

if ($TargetRow['id_owner'] == '' && $_POST['mission'] != 8 )
{
	$ResultMessage = "601; ".$lang['fa_planet_not_exist']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ($ResultMessage);
}

if (($TargetRow["id_owner"] == $planetrow["id_owner"]) && ($_POST["mission"] == 6))
{
	$ResultMessage = "618; ".$lang['fa_not_spy_yourself']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ( $ResultMessage );
}

if ($_POST['thisgalaxy'] != $planetrow['galaxy'] |
	$_POST['thissystem'] != $planetrow['system'] |
	$_POST['thisplanet'] != $planetrow['planet'] |
	$_POST['thisplanettype'] != $planetrow['planet_type'])
{
	$ResultMessage = "618; ".$lang['fa_not_attack_yourself']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ($ResultMessage);
}

$Distance    = Fleets::target_distance ($_POST['thisgalaxy'], $_POST['galaxy'], $_POST['thissystem'], $_POST['system'], $_POST['thisplanet'], $_POST['planet']);
$speedall    = Fleets::fleet_max_speed ($FleetArray, 0, $user);
$SpeedAllMin = min($speedall);
$Duration    = Fleets::mission_duration ( 10, $SpeedAllMin, $Distance, GetGameSpeedFactor ());

$fleet['fly_time']   = $Duration;
$fleet['start_time'] = $Duration + time();
$fleet['end_time']   = ($Duration * 2) + time();

$FleetShipCount      = 0;
$FleetDBArray        = "";
$FleetSubQRY         = "";
$consumption         = 0;
$SpeedFactor         = GetGameSpeedFactor ();
foreach ($FleetArray as $Ship => $Count)
{
	$ShipSpeed        = $pricelist[$Ship]["speed"];
	$spd              = 35000 / ($Duration * $SpeedFactor - 10) * sqrt($Distance * 10 / $ShipSpeed);
	$basicConsumption = $pricelist[$Ship]["consumption"] * $Count ;
	$consumption     += $basicConsumption * $Distance / 35000 * (($spd / 10) + 1) * (($spd / 10) + 1);
	$FleetShipCount  += $Count;
	$FleetDBArray    .= $Ship .",". $Count .";";
	$FleetSubQRY     .= "`".$resource[$Ship] . "` = `" . $resource[$Ship] . "` - " . $Count . " , ";
}
$consumption = round($consumption) + 1;

if ($UserDeuterium < $consumption)
{
	$ResultMessage = "613; ".$lang['fa_not_enough_fuel']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
	die ( $ResultMessage );
}

if ($TargetRow['id_level'] > $user['authlevel'])
{
	$Allowed = TRUE;
	switch ($_POST['mission'])
	{
		case 1:
		case 2:
		case 6:
		case 9:
		$Allowed = FALSE;
		break;
		case 3:
		case 4:
		case 5:
		case 7:
		case 8:
		case 15:
		break;
		default:
	}
	if ($Allowed == FALSE)
	{
		$ResultMessage = "619; ".$lang['fa_action_not_allowed']." |".$CurrentFlyingFleets." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;
		die ( $ResultMessage );
	}
}

$QryInsertFleet  = "INSERT INTO {{table}} SET ";
$QryInsertFleet .= "`fleet_owner` = '". $user['id'] ."', ";
$QryInsertFleet .= "`fleet_mission` = '". intval($_POST['mission']) ."', ";
$QryInsertFleet .= "`fleet_amount` = '". $FleetShipCount ."', ";
$QryInsertFleet .= "`fleet_array` = '". $FleetDBArray ."', ";
$QryInsertFleet .= "`fleet_start_time` = '". $fleet['start_time']. "', ";
$QryInsertFleet .= "`fleet_start_galaxy` = '". intval($_POST['thisgalaxy']) ."', ";
$QryInsertFleet .= "`fleet_start_system` = '". intval($_POST['thissystem']) ."', ";
$QryInsertFleet .= "`fleet_start_planet` = '". intval($_POST['thisplanet']) ."', ";
$QryInsertFleet .= "`fleet_start_type` = '". intval($_POST['thisplanettype']) ."', ";
$QryInsertFleet .= "`fleet_end_time` = '". $fleet['end_time'] ."', ";
$QryInsertFleet .= "`fleet_end_galaxy` = '". intval($_POST['galaxy']) ."', ";
$QryInsertFleet .= "`fleet_end_system` = '". intval($_POST['system']) ."', ";
$QryInsertFleet .= "`fleet_end_planet` = '". intval($_POST['planet']) ."', ";
$QryInsertFleet .= "`fleet_end_type` = '". intval($_POST['planettype']) ."', ";
$QryInsertFleet .= "`fleet_target_owner` = '". $TargetRow['id_owner'] ."', ";
$QryInsertFleet .= "`start_time` = '" . time() . "';";
doquery( $QryInsertFleet, 'fleets');

$UserDeuterium   -= $consumption;

if($UserDeuterium < 1)
	exit();

$QryUpdatePlanet  = "UPDATE {{table}} SET ";
$QryUpdatePlanet .= $FleetSubQRY;
$QryUpdatePlanet .= "`deuterium` = '".$UserDeuterium."' " ;
$QryUpdatePlanet .= "WHERE ";
$QryUpdatePlanet .= "`id` = '". $planetrow['id'] ."';";
doquery( $QryUpdatePlanet, 'planets');

$CurrentFlyingFleets++;

$planetrow 		= doquery("SELECT * FROM {{table}} WHERE `id` = '". $user['current_planet'] ."';", 'planets', TRUE);
$ResultMessage  = "600; ".$lang['fa_sending']." ". $FleetShipCount  ." ". $lang['tech'][$Ship] ." a ". $_POST['galaxy'] .":". $_POST['system'] .":". $_POST['planet'] ."...|";
$ResultMessage .= $CurrentFlyingFleets ." ".$UserSpyProbes." ".$UserRecycles." ".$UserMissiles;

die ($ResultMessage);
?>