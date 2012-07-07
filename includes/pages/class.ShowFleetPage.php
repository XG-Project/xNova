<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowFleetPage
{
	function __construct ( $CurrentUser , $CurrentPlanet )
	{
		global $lang, $reslist, $resource;

		#####################################################################################################
		// SOME DEFAULT VALUES
		#####################################################################################################
		// QUERYS
		$count				= doquery ( "SELECT
											(SELECT COUNT(fleet_owner) AS `actcnt` 
												FROM {{table}}fleets 
												WHERE `fleet_owner` = '" . intval ( $CurrentUser['id'] ) . "') AS max_fleet,
											(SELECT COUNT(fleet_owner) AS `expedi` 
												FROM {{table}}fleets 
													WHERE `fleet_owner` = '" . intval ( $CurrentUser['id'] ) . "' 
														AND `fleet_mission` = '15') AS max_expeditions" , '' , TRUE);



		// LOAD TEMPLATES REQUIRED
		$inputs_template			= gettemplate ( 'fleet/fleet_inputs' );
		$ships_row_template			= gettemplate ( 'fleet/fleet_row_ships' );

		// LANGUAGE
		$parse 						= $lang;

		$MaxFlyingFleets    	= $count['max_fleet'];
		$MaxExpedition      	= $CurrentUser[$resource[124]];

		if ($MaxExpedition >= 1)
		{
			$ExpeditionEnCours  = $count['max_expeditions'];
			$EnvoiMaxExpedition = Fleets::get_max_expeditions ( $MaxExpedition );
		}
		else
		{
			$ExpeditionEnCours 	= 0;
			$EnvoiMaxExpedition = 0;
		}

		$MaxFlottes		= Fleets::get_max_fleets ( $CurrentUser[$resource[108]] , $CurrentUser['rpg_amiral'] );
		$missiontype	= Fleets::get_missions();
		$galaxy         = intval($_GET['galaxy']);
		$system         = intval($_GET['system']);
		$planet         = intval($_GET['planet']);
		$planettype     = intval($_GET['planettype']);
		$target_mission = intval($_GET['target_mission']);
		$ShipData       = "";

		if (!$galaxy)
			$galaxy = $CurrentPlanet['galaxy'];
		if (!$system)
			$system = $CurrentPlanet['system'];
		if (!$planet)
			$planet = $CurrentPlanet['planet'];
		if (!$planettype)
			$planettype = $CurrentPlanet['planet_type'];

		$parse['flyingfleets']			= $MaxFlyingFleets;
		$parse['maxfleets']				= $MaxFlottes;
		$parse['currentexpeditions']	= $ExpeditionEnCours;
		$parse['maxexpeditions']		= $EnvoiMaxExpedition;

		if ( $count['max_fleet'] <> 0 or $MaxExpedition <> 0 )
		{

			$fq = doquery("SELECT * FROM {{table}} WHERE fleet_owner='".intval($CurrentUser[id])."'", "fleets");
			$i  = 0;

			while ( $f = mysql_fetch_array ( $fq ) )
			{
				$i++;

				$parse['num']				=	$i;
				$parse['fleet_mission']		=	$missiontype[$f[fleet_mission]];

				if (($f['fleet_start_time'] + 1) == $f['fleet_end_time'])
				{
					$parse['tooltip']		=	$lang['fl_returning'];
					$parse['title']			=	$lang['fl_r'];
				}
				else
				{
					$parse['tooltip']		=	$lang['fl_onway'];
					$parse['title']			=	$lang['fl_a'];
				}

				$fleet 						= 	explode ( ";" , $f['fleet_array'] );
				$e 							= 	0;

				foreach ( $fleet as $a => $b )
				{
					if ( $b != '' )
					{
						$e++;
						$a 					= explode(",", $b);
						$parse['fleet']    .= $lang['tech'][$a[0]]. ":". $a[1] ."\n";

						if ($e > 1)
						{
							$parse['fleet'].= "\t";
						}
					}
				}

				$parse['fleet_amount']		=	Format::pretty_number ( $f[fleet_amount] );
				$parse['fleet_start']		=	"[".$f[fleet_start_galaxy].":".$f[fleet_start_system].":".$f[fleet_start_planet]."]";
				$parse['fleet_start_time']	=	date ( "d M Y H:i:s" , $f['fleet_start_time'] );
				$parse['fleet_end']			=	"[".$f[fleet_end_galaxy].":".$f[fleet_end_system].":".$f[fleet_end_planet]."]";
				$parse['fleet_end_time']	=	date ( "d M Y H:i:s" , $f['fleet_end_time'] );
				$parse['fleet_arrival']		=	Format::pretty_time ( floor ( $f['fleet_end_time'] + 1 - time() ) );

				//now we can view the call back button for ships in maintaing position (2)
				if ($f['fleet_mess'] == 0 or $f['fleet_mess'] == 2)
				{
					$parse['inputs']  = "<form action=\"SendFleetBack.php\" method=\"post\">";
					$parse['inputs'] .= "<input name=\"fleetid\" value=\"". $f['fleet_id'] ."\" type=\"hidden\">";
					$parse['inputs'] .= "<input value=\"".$lang['fl_send_back']."\" type=\"submit\" name=\"send\">";
					$parse['inputs'] .= "</form>";

					if ($f[fleet_mission] == 1)
					{
						$parse['inputs'] .= "<form action=\"game.php?page=fleetACS\" method=\"post\">";
						$parse['inputs'] .= "<input name=\"fleetid\" value=\"". $f['fleet_id'] ."\" type=\"hidden\">";
						$parse['inputs'] .= "<input value=\"".$lang['fl_acs']."\" type=\"submit\">";
						$parse['inputs'] .= "</form>";
					}
				}
				else
				{
					$parse['inputs'] = "&nbsp;-&nbsp;";
				}

				$flying_fleets	.= parsetemplate ( gettemplate ( 'fleet/fleet_row_fleets' ) , $parse );
			}
		}

		if ( $i == 0 )
		{
			$parse['num']				=	'-';
			$parse['fleet_mission']		=	'-';
			$parse['title']				=	'';
			$parse['fleet_amount']		=	'-';
			$parse['fleet_start']		=	'-';
			$parse['fleet_start_time']	=	'-';
			$parse['fleet_end']			=	'-';
			$parse['fleet_end_time']	=	'-';
			$parse['fleet_arrival']		=	'-';
			$parse['inputs']			=	'-';

			$flying_fleets	.= parsetemplate ( gettemplate ( 'fleet/fleet_row_fleets' ) , $parse );
		}

		$parse['fleetpagerow'] = $flying_fleets;

		if ($MaxFlottes == $MaxFlyingFleets)
		{
			$parse['message_nofreeslot'] .= parsetemplate ( gettemplate ( 'fleet/fleet_noslots_row' ) , $parse );
		}

		if (!$CurrentPlanet)
		{
			header("location:game.php?page=fleet");
		}

		$ships							=	$lang;

		foreach ($reslist['fleet'] as $n => $i)
		{
			if ($CurrentPlanet[$resource[$i]] > 0)
			{
				if ( $i == 212 )
				{
					$ships['fleet_max_speed'] 	= 	'-';
				}
				else
				{
					$ships['fleet_max_speed']	= 	Fleets::fleet_max_speed ( "" , $i , $CurrentUser );
				}

				$ships['ship']					= 	$lang['tech'][$i];
				$ships['amount']				= 	Format::pretty_number ( $CurrentPlanet[$resource[$i]] );
				$inputs['i']					=	$i;
				$inputs['maxship']				=	$CurrentPlanet[$resource[$i]];
				$inputs['consumption']			=	Fleets::ship_consumption ( $i, $CurrentUser );
				$inputs['speed']				=	Fleets::fleet_max_speed ("", $i, $CurrentUser );
				$inputs['capacity']				=	$pricelist[$i]['capacity'];

				if ($i == 212)
				{
					$ships['max_ships']			=	'';
					$ships['set_ships']			=	'';
				}
				else
				{
					$ships['max_ships'] 	   	= "<a href=\"javascript:maxShip('ship". $i ."'); shortInfo();\">".$lang['fl_max']."</a>";
					$ships['set_ships'] 		= "<input name=\"ship". $i ."\" size=\"10\" value=\"0\" onfocus=\"javascript:if(this.value == '0') this.value='';\" onblur=\"javascript:if(this.value == '') this.value='0';\" alt=\"". $lang['tech'][$i] . $CurrentPlanet[$resource[$i]] ."\" onChange=\"shortInfo()\" onKeyUp=\"shortInfo()\" />";
				}

				$ship_inputs	.=	parsetemplate ( $inputs_template , $inputs );
				$ships_row		.= 	parsetemplate ( $ships_row_template , $ships );
			}
			$have_ships = TRUE;
		}

		if (!$have_ships)
		{
			$parse['noships_row']	=	parsetemplate ( gettemplate ( 'fleet/fleet_noships_row' ) , $lang );
		}
		else
		{
			if ( $MaxFlottes > $MaxFlyingFleets )
			{
				$parse['none_max_selector']	=	parsetemplate ( gettemplate ( 'fleet/fleet_selectors' ) , $lang );
				$parse['continue_button']	=	parsetemplate ( gettemplate ( 'fleet/fleet_button' ) , $lang );
			}
		}

		$parse['body'] 					= $ships_row;
		$parse['shipdata'] 				= $ship_inputs;
		$parse['galaxy']				= $galaxy;
		$parse['system']				= $system;
		$parse['planet']				= $planet;
		$parse['planettype']			= $planettype;
		$parse['target_mission']		= $target_mission;
		$parse['envoimaxexpedition']	= $EnvoiMaxExpedition;
		$parse['expeditionencours']		= $ExpeditionEnCours;
		$parse['target_mission']		= $target_mission;

		display(parsetemplate(gettemplate('fleet/fleet_table'), $parse));
	}
}
?>