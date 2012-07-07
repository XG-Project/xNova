<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowFleet3Page
{
	function ShowFleet3Page($CurrentUser, $CurrentPlanet)
	{
		global $resource, $pricelist, $reslist, $lang;

		include_once ( XGP_ROOT . 'includes/functions/IsVacationMode.php' );

		$parse	=	$lang;

		if ( IsVacationMode ( $CurrentUser ) )
		{
			exit ( message ( $lang['fl_vacation_mode_active'] , "game.php?page=overview" , 2 ) );
		}

		$fleet_group_mr = 0;

		if ( $_POST['fleet_group'] > 0 )
		{
			if ( $_POST['mission'] == 2 )
			{
				$target = 	"g" .
							intval ( $_POST["galaxy"] ) .
							"s" .
							intval ( $_POST["system"] ) .
							"p" . intval ( $_POST["planet"] ) .
							"t" . intval ( $_POST["planettype"] );

				if ( $_POST['acs_target_mr'] == $target )
				{
					$aks_count_mr = doquery ( "SELECT COUNT(*)
												FROM {{table}}
												WHERE id = '" . intval ( $_POST['fleet_group'] ) . "'" , 'aks' );

					if ($aks_count_mr > 0)
					{
						$fleet_group_mr = $_POST['fleet_group'];
					}
				}
			}
		}

		if(($_POST['fleet_group'] == 0) && ($_POST['mission'] == 2))
		{
			$_POST['mission'] = 1;
		}

		$TargetPlanet  		= doquery("SELECT `id_owner`,`id_level`,`destruyed`,`ally_deposit` FROM {{table}} WHERE `galaxy` = '". intval($_POST['galaxy']) ."' AND `system` = '". intval($_POST['system']) ."' AND `planet` = '". intval($_POST['planet']) ."' AND `planet_type` = '". intval($_POST['planettype']) ."';", 'planets', TRUE);
		$MyDBRec       		= doquery("SELECT `id`,`onlinetime`,`ally_id`,`urlaubs_modus` FROM {{table}} WHERE `id` = '". intval($CurrentUser['id'])."';", 'users', TRUE);

		$fleetarray  = unserialize ( base64_decode ( str_rot13 ( $_POST["usedfleet"] ) ) );

		if ( $TargetPlanet["destruyed"] != 0 )
		{
			exit ( header ( "Location: game.php?page=fleet" ) );
		}


		if ( !is_array ( $fleetarray ) )
		{
			exit ( header ( "Location: game.php?page=fleet" ) );
		}


		foreach ( $fleetarray as $Ship => $Count )
		{
			$Count = intval ( $Count );

			if ($Count > $CurrentPlanet[$resource[$Ship]])
			{
				exit ( header ( "location:game.php?page=fleet" ) );
			}
		}

		$error              = 0;
		$galaxy             = intval($_POST['galaxy']);
		$system             = intval($_POST['system']);
		$planet             = intval($_POST['planet']);
		$planettype         = intval($_POST['planettype']);
		$fleetmission       = intval($_POST['mission']);

		//fix by jstar
		if ( $fleetmission == 7 && !isset($fleetarray[208]) )
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		if ($planettype != 1 && $planettype != 2 && $planettype != 3)
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		//fix invisible debris like ogame by jstar
		if ($fleetmission == 8)
		{
			$YourPlanet = FALSE;
			$UsedPlanet = FALSE;
			$select     = doquery("SELECT * FROM {{table}} WHERE galaxy = '". $galaxy ."' AND system = '". $system ."' AND planet = '". $planet ."'", "planets");
			$select2    = doquery("SELECT invisible_start_time, metal, crystal FROM {{table}} WHERE galaxy = '". $galaxy ."' AND system = '". $system ."' AND planet = '". $planet ."'", "galaxy",TRUE);
			if($select2['metal'] == 0 && $select2['crystal'] == 0 && time() > ($select2['invisible_start_time']+DEBRIS_LIFE_TIME))
			{
				exit ( header ( "location:game.php?page=fleet" ) );
			}
		}
		else
		{
			$YourPlanet = FALSE;
			$UsedPlanet = FALSE;
			$select     = doquery("SELECT * FROM {{table}} WHERE galaxy = '". $galaxy ."' AND system = '". $system ."' AND planet = '". $planet ."' AND planet_type = '". $planettype ."'", "planets");
		}

		if ($CurrentPlanet['galaxy'] == $galaxy && $CurrentPlanet['system'] == $system &&
			$CurrentPlanet['planet'] == $planet && $CurrentPlanet['planet_type'] == $planettype)
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		if ($_POST['mission'] != 15)
		{
			if (mysql_num_rows($select) < 1 && $fleetmission != 7)
			{
				exit ( header ( "location:game.php?page=fleet" ) );
			}
			elseif ($fleetmission == 9 && mysql_num_rows($select) < 1)
			{
				exit ( header ( "location:game.php?page=fleet" ) );
			}
		}
		else
		{
			$MaxExpedition      = $CurrentUser[$resource[124]];

			if ($MaxExpedition >= 1)
			{
				$maxexpde  			= doquery("SELECT COUNT(fleet_owner) AS `expedi` FROM {{table}} WHERE `fleet_owner` = '".intval($CurrentUser['id'])."' AND `fleet_mission` = '15';", 'fleets', TRUE);
				$ExpeditionEnCours  = $maxexpde['expedi'];
				$EnvoiMaxExpedition = Fleets::get_max_expeditions ( $MaxExpedition );
			}
			else
			{
				$ExpeditionEnCours 	= 0;
				$EnvoiMaxExpedition = 0;
			}

			if($EnvoiMaxExpedition == 0 )
			{
				message ("<font color=\"red\"><b>".$lang['fl_expedition_tech_required']."</b></font>", "game.php?page=fleet", 2);

			}
			elseif ($ExpeditionEnCours >= $EnvoiMaxExpedition )
			{
				message ("<font color=\"red\"><b>".$lang['fl_expedition_fleets_limit']."</b></font>", "game.php?page=fleet", 2);
			}
		}

		$select = mysql_fetch_array($select);

		if ($select['id_owner'] == $CurrentUser['id'])
		{
			$YourPlanet = TRUE;
			$UsedPlanet = TRUE;
		}
		elseif (!empty($select['id_owner']))
		{
			$YourPlanet = FALSE;
			$UsedPlanet = TRUE;
		}
		else
		{
			$YourPlanet = FALSE;
			$UsedPlanet = FALSE;
		}

		//fix by jstar
		if($fleetmission == 9)
		{
			$countfleettype = count ( $fleetarray );

			if($YourPlanet or !$UsedPlanet or $planettype != 3)
			{
				exit ( header ( "location:game.php?page=fleet" ) );
			}
			elseif($countfleettype==1 && !(isset($fleetarray[214])))
			{
				exit ( header ( "location:game.php?page=fleet" ) );
			}
			elseif($countfleettype==2 && !(isset($fleetarray[214])))
			{
				exit ( header ( "location:game.php?page=fleet" ) );
			}
			elseif($countfleettype>2)
			{
				exit ( header ( "location:game.php?page=fleet" ) );
			}
		}

		if (empty($fleetmission))
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		if ($TargetPlanet['id_owner'] == '')
		{
			$HeDBRec = $MyDBRec;
		}
		elseif ($TargetPlanet['id_owner'] != '')
		{
			$HeDBRec = doquery("SELECT `id`,`onlinetime`,`ally_id`,`urlaubs_modus` FROM {{table}} WHERE `id` = '". intval($TargetPlanet['id_owner']) ."';", 'users', TRUE);
		}

		$UserPoints    = doquery("SELECT `total_points` FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". intval($MyDBRec['id']) ."';", 'statpoints', TRUE);
		$User2Points   = doquery("SELECT `total_points` FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". intval($HeDBRec['id']) ."';", 'statpoints', TRUE);

		$MyGameLevel  = $UserPoints['total_points'];
		$HeGameLevel  = $User2Points['total_points'];

		if($HeDBRec['onlinetime'] >= (time()-60 * 60 * 24 * 7))
		{
			if ( is_weak ( $MyGameLevel , $HeGameLevel ) && 
					$TargetPlanet['id_owner'] != '' && 
					($_POST['mission'] == 1 or $_POST['mission'] == 6 or $_POST['mission'] == 9))
			{
				message("<font color=\"lime\"><b>".$lang['fl_week_player']."</b></font>", "game.php?page=fleet", 2);
			}

			if ( is_strong ( $MyGameLevel , $HeGameLevel ) &&
					$TargetPlanet['id_owner'] != '' &&
					($_POST['mission'] == 1 or $_POST['mission'] == 5 or $_POST['mission'] == 6 or $_POST['mission'] == 9))
			{
				message("<font color=\"red\"><b>".$lang['fl_strong_player']."</b></font>", "game.php?page=fleet", 2);
			}
		}

		if ($HeDBRec['urlaubs_modus'] && $_POST['mission'] != 8)
		{
			message("<font color=\"lime\"><b>".$lang['fl_in_vacation_player']."</b></font>", "game.php?page=fleet", 2);
		}

		$FlyingFleets = mysql_fetch_assoc(doquery("SELECT COUNT(fleet_id) as Number FROM {{table}} WHERE `fleet_owner`='".intval($CurrentUser['id'])."'", 'fleets'));
		$ActualFleets = $FlyingFleets["Number"];

		if ((Fleets::get_max_fleets ( $CurrentUser[$resource[108]] , $CurrentUser['rpg_amiral'] ) ) <= $ActualFleets)
		{
			message($lang['fl_no_slots'], "game.php?page=fleet", 1);
		}

		if ($_POST['resource1'] + $_POST['resource2'] + $_POST['resource3'] < 1 && $_POST['mission'] == 3)
		{
			message("<font color=\"lime\"><b>".$lang['fl_empty_transport']."</b></font>", "game.php?page=fleet", 1);
		}

		if ($_POST['mission'] != 15)
		{
			if ($TargetPlanet['id_owner'] == '' && $_POST['mission'] < 7)
			{
				exit ( header ( "location:game.php?page=fleet" ) );
			}

			if ($TargetPlanet['id_owner'] != '' && $_POST['mission'] == 7)
			{
				message ("<font color=\"red\"><b>".$lang['fl_planet_populed']."</b></font>", "game.php?page=fleet", 2);
			}
						
			if ($HeDBRec['ally_id'] != $MyDBRec['ally_id'] && $_POST['mission'] == 4)
			{
				message ("<font color=\"red\"><b>".$lang['fl_stay_not_on_enemy']."</b></font>", "game.php?page=fleet", 2);
			}
			
			if (($TargetPlanet["id_owner"] == $CurrentPlanet["id_owner"]) && (($_POST["mission"] == 1) or ($_POST["mission"] == 6)))
			{
				exit ( header ( "location:game.php?page=fleet" ) );
			}

			if (($TargetPlanet["id_owner"] != $CurrentPlanet["id_owner"]) && ($_POST["mission"] == 4))
			{
				message ("<font color=\"red\"><b>".$lang['fl_deploy_only_your_planets']."</b></font>","game.php?page=fleet", 2);
			}
			
			if($_POST['mission'] == 5)
			{	
				$buddy = doquery ( "SELECT COUNT( * ) AS buddys
										FROM  `{{table}}` 
											WHERE (
												(
													sender ='" . intval($CurrentPlanet['id_owner']) . "'
													AND owner ='" . intval($TargetPlanet['id_owner']) . "'
												)
												OR (
													sender ='" . intval($TargetPlanet['id_owner']) . "'
													AND owner ='" . intval($CurrentPlanet['id_owner']) . "'
												)
											)
											AND active =1" , 'buddy' , TRUE );

/*
				if ($_POST['planettype']==3)
				{
					$x = doquery("SELECT `ally_deposit` FROM {{table}} WHERE `galaxy` = '". intval($_POST['galaxy']) ."' AND `system` = '". intval($_POST['system']) ."' AND `planet` = '". intval($_POST['planet']) ."' AND `planet_type` = 1;", 'planets', TRUE);
				}
				else
				{
					$x = $TargetPlanet;
				}
*/
			//	if (($HeDBRec['ally_id'] != $MyDBRec['ally_id'] && $buddy<1) ||  $x['ally_deposit'] < 1)
			
			
				if ( $HeDBRec['ally_id'] != $MyDBRec['ally_id'] && $buddy['buddys'] < 1 )
				{
					message ("<font color=\"red\"><b>".$lang['fl_stay_not_on_enemy']."</b></font>", "game.php?page=fleet", 2);
				}
			}
		}

		$missiontype	= Fleets::get_missions();
		$speed_possible	= array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1);
		$AllFleetSpeed	= Fleets::fleet_max_speed ($fleetarray, 0, $CurrentUser);
		$GenFleetSpeed  = $_POST['speed'];
		$SpeedFactor    = read_config ( 'fleet_speed' ) / 2500;
		$MaxFleetSpeed  = min($AllFleetSpeed);

		if (!in_array($GenFleetSpeed, $speed_possible))
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		if ($MaxFleetSpeed != $_POST['speedallsmin'])
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		if (!$_POST['planettype'])
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		if (!$_POST['galaxy'] || !is_numeric($_POST['galaxy']) || $_POST['galaxy'] > MAX_GALAXY_IN_WORLD || $_POST['galaxy'] < 1)
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		if (!$_POST['system'] || !is_numeric($_POST['system']) || $_POST['system'] > MAX_SYSTEM_IN_GALAXY || $_POST['system'] < 1)
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		if (!$_POST['planet'] || !is_numeric($_POST['planet']) || $_POST['planet'] > (MAX_PLANET_IN_SYSTEM + 1) || $_POST['planet'] < 1)
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		if ($_POST['thisgalaxy'] != $CurrentPlanet['galaxy'] |
			$_POST['thissystem'] != $CurrentPlanet['system'] |
			$_POST['thisplanet'] != $CurrentPlanet['planet'] |
			$_POST['thisplanettype'] != $CurrentPlanet['planet_type'])
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		if (!isset($fleetarray))
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		$distance      = Fleets::target_distance($_POST['thisgalaxy'], $_POST['galaxy'], $_POST['thissystem'], $_POST['system'], $_POST['thisplanet'], $_POST['planet']);
		$duration      = Fleets::mission_duration($GenFleetSpeed, $MaxFleetSpeed, $distance, $SpeedFactor);
		$consumption   = Fleets::fleet_consumption($fleetarray, $SpeedFactor, $duration, $distance, $MaxFleetSpeed, $CurrentUser);

		$fleet['start_time'] = $duration + time();

		// START CODE BY JSTAR
		if ($_POST['mission'] == 15)
		{
			$StayDuration	= floor($_POST['expeditiontime']);

			if ( $StayDuration <= floor ( sqrt ( $CurrentUser['expedition_tech'] ) ) && $StayDuration > 0 )
			{
				$StayDuration    = $StayDuration  * 3600;
				$StayTime        = $fleet['start_time'] + $StayDuration;
			}
			else
			{
				exit ( header ( "location:game.php?page=fleet" ) );
			}
		} // END CODE BY JSTAR
		elseif ($_POST['mission'] == 5)
		{
			$StayDuration    = $_POST['holdingtime'] * 3600;
			$StayTime        = $fleet['start_time'] + $_POST['holdingtime'] * 3600;
		}
		else
		{
			$StayDuration    = 0;
			$StayTime        = 0;
		}

		$fleet['end_time']   = $StayDuration + (2 * $duration) + time();
		$FleetStorage        = 0;
		$FleetShipCount      = 0;
		$fleet_array         = "";
		$FleetSubQRY         = "";

		//fix by jstar
		$haveSpyProbos		= FALSE;

		foreach ($fleetarray as $Ship => $Count)
		{
			$Count = intval($Count);

			if($Ship == 210)
			{
				$haveSpyProbos = TRUE;
			}

			$FleetStorage    += $pricelist[$Ship]["capacity"] * $Count;
			$FleetShipCount  += $Count;
			$fleet_array     .= $Ship .",". $Count .";";
			$FleetSubQRY     .= "`".$resource[$Ship] . "` = `" . $resource[$Ship] . "` - " . $Count . ", ";
		}

		if(!$haveSpyProbos AND $_POST['mission'] == 6)
		{
			exit ( header ( "location:game.php?page=fleet" ) );
		}

		$FleetStorage        -= $consumption;
		$StorageNeeded        = 0;

		$_POST['resource1'] = max(0, (int)trim($_POST['resource1']));
		$_POST['resource2'] = max(0, (int)trim($_POST['resource2']));
		$_POST['resource3'] = max(0, (int)trim($_POST['resource3']));

		if ($_POST['resource1'] < 1)
		{
			$TransMetal      = 0;
		}
		else
		{
			$TransMetal      = $_POST['resource1'];
			$StorageNeeded  += $TransMetal;
		}

		if ($_POST['resource2'] < 1)
		{
			$TransCrystal    = 0;
		}
		else
		{
			$TransCrystal    = $_POST['resource2'];
			$StorageNeeded  += $TransCrystal;
		}
		if ($_POST['resource3'] < 1)
		{
			$TransDeuterium  = 0;
		}
		else
		{
			$TransDeuterium  = $_POST['resource3'];
			$StorageNeeded  += $TransDeuterium;
		}

		$StockMetal      = $CurrentPlanet['metal'];
		$StockCrystal    = $CurrentPlanet['crystal'];
		$StockDeuterium  = $CurrentPlanet['deuterium'];
		$StockDeuterium -= $consumption;

		$StockOk         = FALSE;

		if ($StockMetal >= $TransMetal)
		{
			if ($StockCrystal >= $TransCrystal)
			{
				if ($StockDeuterium >= $TransDeuterium)
				{
					$StockOk         = TRUE;
				}
			}
		}

		if (!$StockOk)
		{
			message ("<font color=\"red\"><b>". $lang['fl_no_enought_deuterium'] . Format::pretty_number($consumption) ."</b></font>", "game.php?page=fleet", 2);
		}

		if ( $StorageNeeded > $FleetStorage)
		{
			message ("<font color=\"red\"><b>". $lang['fl_no_enought_cargo_capacity'] . Format::pretty_number($StorageNeeded - $FleetStorage) ."</b></font>", "game.php?page=fleet", 2);
		}

		if ($TargetPlanet['id_level'] > $CurrentUser['authlevel'] && read_config ( 'adm_attack' ) == 0)
		{
			message($lang['fl_admins_cannot_be_attacked'], "game.php?page=fleet",2);
		}

		if ($fleet_group_mr != 0)
		{
			$AksStartTime = doquery("SELECT MAX(`fleet_start_time`) AS Start FROM {{table}} WHERE `fleet_group` = '". $fleet_group_mr . "';", "fleets", TRUE);

			if ($AksStartTime['Start'] >= $fleet['start_time'])
			{
				$fleet['end_time']        += $AksStartTime['Start'] -  $fleet['start_time'];
				$fleet['start_time']     = $AksStartTime['Start'];
			}
			else
			{
				$QryUpdateFleets = "UPDATE {{table}} SET ";
				$QryUpdateFleets .= "`fleet_start_time` = '". $fleet['start_time'] ."', ";
				$QryUpdateFleets .= "`fleet_end_time` = fleet_end_time + '".($fleet['start_time'] - $AksStartTime['Start'])."' ";
				$QryUpdateFleets .= "WHERE ";
				$QryUpdateFleets .= "`fleet_group` = '". $fleet_group_mr ."';";
				doquery($QryUpdateFleets, 'fleets');
				$fleet['end_time']         += $fleet['start_time'] -  $AksStartTime['Start'];
			}
		}

		$QryInsertFleet  = "INSERT INTO {{table}} SET ";
		$QryInsertFleet .= "`fleet_owner` = '". intval($CurrentUser['id']) ."', ";
		$QryInsertFleet .= "`fleet_mission` = '".intval($_POST['mission'])."',  ";
		$QryInsertFleet .= "`fleet_amount` = '". intval($FleetShipCount) ."', ";
		$QryInsertFleet .= "`fleet_array` = '". $fleet_array ."', ";
		$QryInsertFleet .= "`fleet_start_time` = '". $fleet['start_time'] ."', ";
		$QryInsertFleet .= "`fleet_start_galaxy` = '". intval($_POST['thisgalaxy']) ."', ";
		$QryInsertFleet .= "`fleet_start_system` = '". intval($_POST['thissystem']) ."', ";
		$QryInsertFleet .= "`fleet_start_planet` = '". intval($_POST['thisplanet']) ."', ";
		$QryInsertFleet .= "`fleet_start_type` = '". intval($_POST['thisplanettype']) ."', ";
		$QryInsertFleet .= "`fleet_end_time` = '". intval($fleet['end_time']) ."', ";
		$QryInsertFleet .= "`fleet_end_stay` = '". intval($StayTime) ."', ";
		$QryInsertFleet .= "`fleet_end_galaxy` = '". intval($_POST['galaxy']) ."', ";
		$QryInsertFleet .= "`fleet_end_system` = '". intval($_POST['system']) ."', ";
		$QryInsertFleet .= "`fleet_end_planet` = '". intval($_POST['planet']) ."', ";
		$QryInsertFleet .= "`fleet_end_type` = '". intval($_POST['planettype']) ."', ";
		$QryInsertFleet .= "`fleet_resource_metal` = '". $TransMetal ."', ";
		$QryInsertFleet .= "`fleet_resource_crystal` = '". $TransCrystal ."', ";
		$QryInsertFleet .= "`fleet_resource_deuterium` = '". $TransDeuterium ."', ";
		$QryInsertFleet .= "`fleet_target_owner` = '". intval($TargetPlanet['id_owner']) ."', ";
		$QryInsertFleet .= "`fleet_group` = '".intval($fleet_group_mr)."',  ";
		$QryInsertFleet .= "`start_time` = '". time() ."';";
		doquery( $QryInsertFleet, 'fleets');

		$QryUpdatePlanet  = "UPDATE `{{table}}` SET ";
		$QryUpdatePlanet .= $FleetSubQRY;
		$QryUpdatePlanet .= "`metal` = `metal` - ". $TransMetal .", ";
		$QryUpdatePlanet .= "`crystal` = `crystal` - ". $TransCrystal .", ";
		$QryUpdatePlanet .= "`deuterium` = `deuterium` - ". ($TransDeuterium + $consumption) ." ";
		$QryUpdatePlanet .= "WHERE ";
		$QryUpdatePlanet .= "`id` = ". intval($CurrentPlanet['id']) ." LIMIT 1;";
		doquery ($QryUpdatePlanet, "planets");

		$parse['mission'] 		= $missiontype[$_POST['mission']];
		$parse['distance'] 		= Format::pretty_number($distance);
		$parse['speedallsmin'] 	= Format::pretty_number($_POST['speedallsmin']);
		$parse['consumption'] 	= Format::pretty_number($consumption);
		$parse['from']	 		= $_POST['thisgalaxy'] .":". $_POST['thissystem']. ":". $_POST['thisplanet'];
		$parse['destination']	= $_POST['galaxy'] .":". $_POST['system'] .":". $_POST['planet'];
		$parse['start_time'] 	= date("M D d H:i:s", $fleet['start_time']);
		$parse['end_time'] 		= date("M D d H:i:s", $fleet['end_time']);

		$ships_row_template		= gettemplate ( 'fleet/fleet3_ships_row' );

		foreach ( $fleetarray as $Ship => $Count )
		{
			$fleet_list['ship']		=	$lang['tech'][$Ship];
			$fleet_list['amount']	=	Format::pretty_number ( $Count );

			$ships_list			   .=	parsetemplate ( $ships_row_template , $fleet_list );
		}

		$parse['fleet_list'] 	= $ships_list;

		display ( parsetemplate ( gettemplate ( 'fleet/fleet3_table' ) , $parse ) , FALSE );
	}
}
?>