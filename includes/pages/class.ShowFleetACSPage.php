<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowFleetACSPage
{
	function __construct ( $CurrentUser , $CurrentPlanet )
	{
		global $resource, $pricelist, $reslist, $lang;

		#####################################################################################################
		// SOME DEFAULT VALUES
		#####################################################################################################
		// ARRAYS
		$missiontype		= Fleets::get_missions();

		$speed				= array (10 => 100,9 => 90,8 => 80,7 => 70,6 => 60,5 => 50,4 => 40,3 => 30,2 => 20,1 => 10 );

		// TEMPLATES
		$options_template	= gettemplate ( 'fleet/fleet_options' );

		// QUERY
		$count				= doquery("SELECT
										(SELECT COUNT(fleet_owner) AS `actcnt` FROM {{table}}fleets WHERE `fleet_owner` = '".intval($CurrentUser['id'])."') AS max_fleet,
										(SELECT COUNT(fleet_owner) AS `expedi` FROM {{table}}fleets WHERE `fleet_owner` = '".intval($CurrentUser['id'])."' AND `fleet_mission` = '15') AS max_expeditions" , '' , TRUE);

		// LOAD TEMPLATES REQUIRED
		$options_template	= gettemplate ( 'fleet/fleet_options' );

		// LANGUAGE
		$parse 				= $lang;

		// COORDS
		$galaxy 			= ( ( $_GET['galaxy'] == '' ) ? $CurrentPlanet['galaxy'] : $_GET['galaxy'] );
		$system 			= ( ( $_GET['system'] == '' ) ? $CurrentPlanet['system'] : $_GET['system'] );
		$planet 			= ( ( $_GET['planet'] == '' ) ? $CurrentPlanet['planet'] : $_GET['planet'] );
		$planettype 		= ( ( $_GET['planet_type'] == '' ) ? $CurrentPlanet['planet_type'] : $_GET['planet_type'] );


		// OTHER VALUES
		$fleetid 			= $_POST['fleetid'];
		$MaxFlyingFleets    = $count['max_fleet'];
		$MaxExpedition      = $CurrentUser[$resource[124]];

		if ($MaxExpedition >= 1)
		{
			$ExpeditionEnCours  = $count['max_expeditions'];
			$EnvoiMaxExpedition = 1 + floor( $MaxExpedition / 3 );
		}
		else
		{
			$ExpeditionEnCours 	= 0;
			$EnvoiMaxExpedition = 0;
		}

		$MaxFlottes 		= Fleets::get_max_fleets ( $CurrentUser[$resource[108]] , $CurrentUser['rpg_amiral'] );

		if ( !is_numeric ( $fleetid ) or empty ( $fleetid ) )
		{
			exit ( header ( "Location: game.php?page=fleet" ) );
		}

		if ( isset ( $_POST['add_member_to_acs'] ) && !empty ( $_POST['add_member_to_acs'] ) )
		{
			$added_user_id 	= 0;
			$member_qry 		= doquery("SELECT `id` FROM {{table}} WHERE `username` ='".mysql_escape_value($_POST['addtogroup'])."' ;",'users');

			while ( $row = mysql_fetch_array ( $member_qry ) )
			{
				$added_user_id .= $row['id'];
			}

			if ( $added_user_id > 0 )
			{
				$new_eingeladen_mr = mysql_escape_value($_POST['acs_invited']).','.$added_user_id;
				doquery("UPDATE {{table}} SET `eingeladen` = '".$new_eingeladen_mr."' ;",'aks');
				$acs_user_message = "<font color=\"lime\">".$lang['fl_player']." ".$_POST['addtogroup']." ". $lang['fl_add_to_attack'];
			}
			else
			{
				$acs_user_message = "<font color=\"red\">".$lang['fl_player']." ".$_POST['addtogroup']." ".$lang['fl_dont_exist']."";
			}

			$invite_message = $lang['fl_player'] . $CurrentUser['username'] . $lang['fl_acs_invitation_message'];
			SendSimpleMessage ($added_user_id, $CurrentUser['id'], time(), 1, $CurrentUser['username'], $lang['fl_acs_invitation_title'], $invite_message);
		}

		$query = doquery("SELECT * FROM {{table}} WHERE fleet_id = '" . intval($fleetid) . "'", 'fleets');

		if ( mysql_num_rows ( $query ) != 1 )
		{
			exit ( header ( "Location: game.php?page=fleet" ) );
		}

		$daten = mysql_fetch_array ( $query );

		if ( $daten['fleet_start_time'] <= time() or
			 $daten['fleet_end_time'] < time() or
			 $daten['fleet_mess'] == 1 )
		{
			exit ( header ( "Location: game.php?page=fleet" ) );
		}

		if ( !isset ( $_POST['send'] ) )
		{
			$fleet 				= doquery("SELECT * FROM {{table}} WHERE fleet_id = '" . intval($fleetid) . "'", 'fleets', TRUE);

			if ( empty ( $fleet['fleet_group'] ) )
			{
				$rand 			= mt_rand ( 100000 , 999999999 );
				$acs_code 		= "AG" . $rand;
				$acs_invited 	= intval ( $CurrentUser['id'] );

				doquery ( "INSERT INTO {{table}}
							SET
								`name` = '" . $acs_code . "',
								`teilnehmer` = '" . $CurrentUser['id'] . "',
								`flotten` = '" . $fleetid . "',
								`ankunft` = '" . $fleet['fleet_start_time'] . "',
								`galaxy` = '" . $fleet['fleet_end_galaxy'] . "',
								`system` = '" . $fleet['fleet_end_system'] . "',
								`planet` = '" . $fleet['fleet_end_planet'] . "',
								`planet_type` = '" . $fleet['fleet_end_type'] . "',
								`eingeladen` = '" . $acs_invited . "'" , 'aks' );

				$acs = doquery ( "SELECT `id`
									FROM {{table}}
									WHERE `name` = '" . $acs_code . "' AND
											`teilnehmer` = '" . $CurrentUser['id'] . "' AND
											`flotten` = '" . $fleetid . "' AND
											`ankunft` = '" . $fleet['fleet_start_time'] . "' AND
											`galaxy` = '" . $fleet['fleet_end_galaxy'] . "' AND
											`system` = '" . $fleet['fleet_end_system'] . "' AND
											`planet` = '" . $fleet['fleet_end_planet'] . "' AND
											`eingeladen` = '" . intval($CurrentUser['id']) . "'
											" , 'aks' , TRUE);

				$acs_madnessred = doquery ( "SELECT *
												FROM {{table}}
												WHERE `name` = '" . $acs_code . "' AND
														`teilnehmer` = '" . $CurrentUser['id'] . "' AND
														`flotten` = '" . $fleetid . "' AND
														`ankunft` = '" . $fleet['fleet_start_time'] . "' AND
														`galaxy` = '" . $fleet['fleet_end_galaxy'] . "' AND
														`system` = '" . $fleet['fleet_end_system'] . "' AND
														`planet` = '" . $fleet['fleet_end_planet'] . "' AND
														`eingeladen` = '" . intval($CurrentUser['id']) . "'
														" , 'aks' );

				doquery("UPDATE {{table}}
							SET fleet_group = '" . intval ( $acs['id'] ) . "'
							WHERE fleet_id = '" . intval ( $fleetid ) . "'" , 'fleets');
			}
			else
			{
				if ( $_POST['txt_name_acs'] != "" )
				{
					doquery ( "UPDATE {{table}}
								SET name = '" . mysql_escape_value($_POST['txt_name_acs']) . "'
								WHERE teilnehmer = '" . intval($CurrentUser['id']) . "'", 'aks');
				}

				$acs 			= doquery("SELECT COUNT(`id`) FROM {{table}} WHERE id = '" . intval($fleet['fleet_group']) . "'" , 'aks' , TRUE );
				$acs_madnessred = doquery("SELECT * FROM {{table}} WHERE id = '" . intval($fleet['fleet_group']) . "'", 'aks');

				if ( $acs[0] != 1 )
				{
					exit ( header ( "Location: game.php?page=fleet" ) );
				}
			}

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

			$parse['fleetpagerow']	=	$flying_fleets;

			while ( $row = mysql_fetch_array ( $acs_madnessred ) )
			{
				$acs_code  			.= $row['name'];
				$acs_invited 		.= $row['eingeladen'];
			}

			$parse['acs_code']		= $acs_code;
			$members 				= explode ( "," , $acs_invited );

			foreach ( $members as $a => $b )
			{
				if ( $b != '' )
				{
					$member_qry 	= doquery("SELECT `username` FROM {{table}} WHERE `id` ='".intval($b)."' ;",'users');

					while ( $row = mysql_fetch_array ( $member_qry ) )
					{
						$members_option['value']	= '';
						$members_option['selected']	= '';
						$members_option['title']	= $row['username'];
						$members_row    			.= parsetemplate ( $options_template , $members_option );
					}
				}
			}

			$parse['invited_members']		= $members_row;
			$parse['fleetid']				= $_POST[fleetid];
			$parse['acs_invited']			= $acs_invited;
			$parse['add_user_message']		= $acs_user_message;

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
						$ships['fleet_max_speed']	=  	Fleets::fleet_max_speed ( "" , $i , $CurrentUser );
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

					$ship_inputs	.=	parsetemplate ( gettemplate ( 'fleet/fleet_inputs' ) , $inputs );
					$ships_row		.= 	parsetemplate ( gettemplate ( 'fleet/fleet_row_ships' ) , $ships );
				}

				$have_ships = TRUE;

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
			}

			$parse['acs_members']			= parsetemplate ( gettemplate ( 'fleet/fleetACS_table' ) , $parse );
			$parse['body']					= $ships_row;
			$parse['shipdata'] 				= $ship_inputs;
			$parse['galaxy']				= $galaxy;
			$parse['system']				= $system;
			$parse['planet']				= $planet;
			$parse['planettype']			= $planettype;
			$parse['target_mission']		= $target_mission;
			$parse['flyingfleets']			= $MaxFlyingFleets;
			$parse['maxfleets']				= $MaxFlottes;
			$parse['currentexpeditions']	= $ExpeditionEnCours;
			$parse['maxexpeditions']		= $EnvoiMaxExpedition;
		}
		display ( parsetemplate ( gettemplate ( 'fleet/fleet_table' ) , $parse ) );
	}
}
?>