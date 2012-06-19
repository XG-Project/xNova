<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowFleet2Page
{
	function __construct ( $CurrentUser , $CurrentPlanet )
	{
		global $resource, $pricelist, $reslist, $lang;

		#####################################################################################################
		// SOME DEFAULT VALUES
		#####################################################################################################
		// ARRAYS
		$exp_values		= array ( 1,2,3,4,5 );
		$hold_values	= array ( 0,1,2,4,8,16,32 );

		// LANG
		$parse					= $lang;

		// LOAD TEMPLATES REQUIRED
		$mission_row_template	= gettemplate ( 'fleet/fleet2_mission_row' );
		$input_template			= gettemplate ( 'fleet/fleet2_inputs' );
		$stay_template			= gettemplate ( 'fleet/fleet2_stay_row' );
		$options_template		= gettemplate ( 'fleet/fleet_options' );

		// OTHER VALUES
		$galaxy     			= intval($_POST['galaxy']);
		$system     			= intval($_POST['system']);
		$planet     			= intval($_POST['planet']);
		$planettype 			= intval($_POST['planettype']);
		$fleet_acs 				= intval($_POST['fleet_group']);
		$YourPlanet 			= FALSE;
		$UsedPlanet 			= FALSE;
		$MissionSelector		= '';

		// QUERYS
		$select        			= doquery ( "SELECT `id_owner`
												FROM `{{table}}`
												WHERE galaxy = '$galaxy' AND
														system = '$system' AND
														planet = '$planet' AND
														planet_type = '$planettype'" , "planets" , TRUE );

		if ( $select )
		{
			if ( $select['id_owner'] == $CurrentUser['id'] )
			{
				$YourPlanet = TRUE;
				$UsedPlanet = TRUE;
			}
			else
			{
				$UsedPlanet = TRUE;
			}
		}

		if ( $_POST['planettype'] == 2 )
		{
			if ($_POST['ship209'] >= 1)
			{
				$missiontype = array ( 8 => $lang['type_mission'][8] );
			}
			else
			{
				$missiontype = array();
			}
		}
		elseif ($_POST['planettype'] == 1 or $_POST['planettype'] == 3)
		{
			if ($_POST['ship208'] >= 1 && !$UsedPlanet)
			{
				$missiontype = array ( 7 => $lang['type_mission'][7] );
			}

			elseif ($_POST['ship210'] >= 1 && !$YourPlanet)
			{
				$missiontype = array ( 6 => $lang['type_mission'][6] );
			}


			if ( $_POST['ship202'] >= 1 or
				 $_POST['ship203'] >= 1 or
				 $_POST['ship204'] >= 1 or
				 $_POST['ship205'] >= 1 or
				 $_POST['ship206'] >= 1 or
				 $_POST['ship207'] >= 1 or
				 $_POST['ship210'] >= 1 or
				 $_POST['ship211'] >= 1 or
				 $_POST['ship213'] >= 1 or
				 $_POST['ship214'] >= 1 or
				 $_POST['ship215'] >= 1 )
			{

				if ( !$YourPlanet )
				{
					$missiontype[1] = $lang['type_mission'][1];
				}

				$missiontype[3] 	= $lang['type_mission'][3];
				$missiontype[5] 	= $lang['type_mission'][5];
			}
		}
		elseif ( $_POST['ship209'] >= 1 or $_POST['ship208'] )
		{
			$missiontype[3] 		= $lang['type_mission'][3];
		}

		if ($YourPlanet)
		{
			$missiontype[4] 		= $lang['type_mission'][4];
		}

		if ($_POST['planettype'] == 3 || $_POST['planettype'] == 1 && ($fleet_acs > 0) && $UsedPlanet)
		{
			$acs = doquery ( "SELECT * FROM `{{table}}` WHERE `id`= ".$fleet_acs."" , "aks" , TRUE );

			if ( 	$acs['galaxy'] == $galaxy &&
					$acs['planet'] == $planet &&
					$acs['system'] == $system &&
					$acs['planet_type'] == $planettype )
			{
				$missiontype[2] 	= $lang['type_mission'][2];
			}
		}

		if($_POST['planettype'] == 3 && $_POST['ship214'] >= 1 && !$YourPlanet && $UsedPlanet)
		{
			$missiontype[9] = $lang['type_mission'][9];
		}

		$fleetarray    		= unserialize(base64_decode(str_rot13($_POST["usedfleet"])));
		$mission       		= $_POST['target_mission'];
		$SpeedFactor   		= $_POST['speedfactor'];
		$AllFleetSpeed 		= Fleets::fleet_max_speed ($fleetarray, 0, $CurrentUser);
		$GenFleetSpeed 		= $_POST['speed'];
		$MaxFleetSpeed 		= min($AllFleetSpeed);
		$distance      		= Fleets::target_distance($_POST['thisgalaxy'], $_POST['galaxy'], $_POST['thissystem'], $_POST['system'], $_POST['thisplanet'], $_POST['planet']);
		$duration      		= Fleets::mission_duration($GenFleetSpeed, $MaxFleetSpeed, $distance, $SpeedFactor);
		$consumption   		= Fleets::fleet_consumption($fleetarray, $SpeedFactor, $duration, $distance, $MaxFleetSpeed, $CurrentUser);

		#####################################################################################################
		// INPUTS DATA
		#####################################################################################################
		$parse['metal'] 			= floor($CurrentPlanet["metal"]);
		$parse['crystal'] 			= floor($CurrentPlanet["crystal"]);
		$parse['deuterium'] 		= floor($CurrentPlanet["deuterium"]);
		$parse['consumption'] 		= $consumption;
		$parse['distance']			= $distance;
		$parse['speedfactor'] 		= $_POST['speedfactor'];
		$parse['thisgalaxy'] 		= $_POST["thisgalaxy"];
		$parse['thissystem'] 		= $_POST["thissystem"];
		$parse['thisplanet'] 		= $_POST["thisplanet"];
		$parse['galaxy'] 			= $_POST["galaxy"];
		$parse['system'] 			= $_POST["system"];
		$parse['planet'] 			= $_POST["planet"];
		$parse['thisplanettype']	= $_POST["thisplanettype"];
		$parse['planettype'] 		= $_POST["planettype"];
		$parse['speedallsmin'] 		= $_POST["speedallsmin"];
		$parse['speed'] 			= $_POST['speed'];
		$parse['speedfactor'] 		= $_POST["speedfactor"];
		$parse['usedfleet'] 		= $_POST["usedfleet"];
		$parse['maxepedition'] 		= $_POST['maxepedition'];
		$parse['curepedition'] 		= $_POST['curepedition'];
		$parse['fleet_group'] 		= $_POST['fleet_group'];
		$parse['acs_target_mr'] 	= $_POST['acs_target_mr'];

		#####################################################################################################
		// EXTRA INPUTS
		#####################################################################################################
		foreach ( $fleetarray as $Ship => $Count )
		{
			$input_parse['ship']		=	$Ship;
			$input_parse['amount']		=	$Count;
			$input_parse['capacity']	=	$pricelist[$Ship]['capacity'];
			$input_parse['consumption']	=	Fleets::ship_consumption ( $Ship , $CurrentUser );
			$input_parse['speed']		=	Fleets::fleet_max_speed ( "" , $Ship , $CurrentUser );

			$input_extra .= parsetemplate ( $input_template , $input_parse );
		}

		#####################################################################################################
		// TOP TABLE TITLE
		#####################################################################################################
		if ( $_POST['thisplanettype'] == 1 )
		{
			$parse['title'] = "". $_POST['thisgalaxy'] .":". $_POST['thissystem'] .":". $_POST['thisplanet'] ." - ".$lang['fl_planet']."";

		}
		elseif ( $_POST['thisplanettype'] == 3 )
		{
			$parse['title'] = "". $_POST['thisgalaxy'] .":". $_POST['thissystem'] .":". $_POST['thisplanet'] ." - ".$lang['fl_moon']."";
		}

		#####################################################################################################
		// MISSION TYPES
		#####################################################################################################
		if ( count ( $missiontype ) > 0 )
		{
			if ( $planet == 16 )
			{
				$parse_mission['value']					= 15;
				$parse_mission['mission']				= $lang['type_mission'][15];
				$parse_mission['expedition_message']	= $lang['fl_expedition_alert_message'];
				$parse_mission['id']					= ' ';
				$parse_mission['checked']				= ' checked="checked"';

				$MissionSelector	.=	parsetemplate ( $mission_row_template , $parse_mission );
			}
			else
			{
				$i = 0;

				foreach ( $missiontype as $a => $b )
				{
					$parse_mission['value']					= $a;
					$parse_mission['mission']				= $b;
					$parse_mission['expedition_message']	= '';
					$parse_mission['id']					= ' id="inpuT_' . $i . '" ';
					$parse_mission['checked']				= ( ( $mission == $a ) ? ' checked="checked"' : '' );

					$i++;

					$MissionSelector	.=	parsetemplate ( $mission_row_template , $parse_mission );
				}
			}
		}
		else
		{
			header ( "location:game.php?page=fleet" );
		}

		#####################################################################################################
		// STAY / EXPEDITION BLOCKS
		#####################################################################################################
		if ( $planet == 16 )
		{
			$stay['stay_type']			= 'expeditiontime';

			foreach ( $exp_values as $value )
			{
				$stay['value']			= $value;
				$stay['selected']		= '';
				$stay['title']			= $value;

				$stay_row['options']  .= parsetemplate ( $options_template , $stay );
			}

			$StayBlock = parsetemplate ( $stay_template , $stay_row );
		}
		elseif ( $missiontype[5] != '' )
		{
			$stay['stay_type']				= 'holdingtime';

			foreach ( $hold_values as $value )
			{

				$stay['value']			= $value;
				$stay['selected']		= ( ( $value == 1 ) ? ' selected' : '' );
				$stay['title']			= $value;

				$stay_row['options']  .= parsetemplate ( $options_template , $stay );
			}

			$StayBlock = parsetemplate ( $stay_template , $stay_row );
		}

		$parse['input_extra'] 			= $input_extra;
		$parse['missionselector'] 		= $MissionSelector;
		$parse['stayblock'] 			= $StayBlock;

		display ( parsetemplate ( gettemplate ( 'fleet/fleet2_table' ) , $parse ) );
	}
}
?>