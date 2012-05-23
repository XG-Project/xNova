<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowResearchPage
{
	private function CheckLabSettingsInQueue ($CurrentPlanet)
	{
		if ($CurrentPlanet['b_building_id'] != 0)
		{
			$CurrentQueue = $CurrentPlanet['b_building_id'];
			if (strpos ($CurrentQueue, ";"))
			{
				// FIX BY LUCKY - IF THE LAB IS IN QUEUE THE USER CANT RESEARCH ANYTHING...
				$QueueArray		= explode (";", $CurrentQueue);

				for($i = 0; $i < MAX_BUILDING_QUEUE_SIZE; $i++)
				{
					$ListIDArray	= explode (",", $QueueArray[$i]);
					$Element		= $ListIDArray[0];

					if($Element == 31)
						break;
				}
				// END - FIX
			}
			else
			{
				$CurrentBuilding = $CurrentQueue;
			}

			if ($CurrentBuilding == 31 or $Element == 31) // ADDED (or $Element == 31) BY LUCKY
			{
				$return = FALSE;
			}
			else
			{
				$return = TRUE;
			}
		}
		else
		{
			$return = TRUE;
		}

		return $return;
	}

	public function __construct (&$CurrentPlanet, $CurrentUser, $InResearch, $ThePlanet)
	{
		global $lang, $resource, $reslist, $_GET;

		include_once(XGP_ROOT . 'includes/functions/IsTechnologieAccessible.php');
		include_once(XGP_ROOT . 'includes/functions/GetElementPrice.php');

		$PageParse			= $lang;
		$NoResearchMessage 	= "";
		$bContinue         	= TRUE;
		$intergal_lab 		= $CurrentUser[$resource[123]];
		$limite 			= $intergal_lab+1;
		$inves 				= doquery("SELECT laboratory FROM {{table}} WHERE id_owner='".intval($CurrentUser['id'])."' ORDER BY laboratory DESC LIMIT ".$limite."", 'planets');
		$lablevel 			= 0;

		while (	$row = mysql_fetch_array ( $inves ) )
		{
			$lablevel 	   += $row['laboratory'];
		}

		if ($CurrentPlanet[$resource[31]] == 0)
			message($lang['bd_lab_required'], '', '', TRUE);

		if (!$this->CheckLabSettingsInQueue ($CurrentPlanet))
		{
			$NoResearchMessage = $lang['bd_building_lab'];
			$bContinue         = FALSE;
		}

		if (isset($_GET['cmd']))
		{
			$TheCommand 	= $_GET['cmd'];
			$Techno     	= intval($_GET['tech']);

			if ( isset ($Techno) )
			{
				if (!strstr ( $Techno, ",") && !strchr ( $Techno, " ") &&
					!strchr ( $Techno, "+") && !strchr ( $Techno, "*") &&
					!strchr ( $Techno, "~") && !strchr ( $Techno, "=") &&
					!strchr ( $Techno, ";") && !strchr ( $Techno, "'") &&
					!strchr ( $Techno, "#") && !strchr ( $Techno, "-") &&
					!strchr ( $Techno, "_") && !strchr ( $Techno, "[") &&
					!strchr ( $Techno, "]") && !strchr ( $Techno, ".") &&
					!strchr ( $Techno, ":"))
				{
					if ( in_array($Techno, $reslist['tech']) )
					{
						if ( is_array ($ThePlanet) )
						{
							$WorkingPlanet = $ThePlanet;
						}
						else
						{
							$WorkingPlanet = $CurrentPlanet;
						}

						switch($TheCommand)
						{
							case 'cancel':
								if ($ThePlanet['b_tech_id'] == $Techno)
								{
									$costs                        = GetBuildingPrice($CurrentUser, $WorkingPlanet, $Techno);
									$WorkingPlanet['metal']      += $costs['metal'];
									$WorkingPlanet['crystal']    += $costs['crystal'];
									$WorkingPlanet['deuterium']  += $costs['deuterium'];
									$WorkingPlanet['b_tech_id']   = 0;
									$WorkingPlanet["b_tech"]      = 0;
									$CurrentUser['b_tech_planet'] = 0;
									$UpdateData                   = TRUE;
									$InResearch                   = FALSE;
								}
								break;
							case 'search':
								if (IsTechnologieAccessible($CurrentUser, $WorkingPlanet, $Techno) && IsElementBuyable($CurrentUser, $WorkingPlanet, $Techno))
								{
									$costs                        = GetBuildingPrice($CurrentUser, $WorkingPlanet, $Techno);
									$WorkingPlanet['metal']      -= $costs['metal'];
									$WorkingPlanet['crystal']    -= $costs['crystal'];
									$WorkingPlanet['deuterium']  -= $costs['deuterium'];
									$WorkingPlanet["b_tech_id"]   = $Techno;
									$WorkingPlanet["b_tech"]      = time() + GetBuildingTime($CurrentUser, $WorkingPlanet, $Techno, FALSE, $lablevel);
									$CurrentUser["b_tech_planet"] = $WorkingPlanet["id"];
									$UpdateData                   = TRUE;
									$InResearch                   = TRUE;
								}
								break;
						}
						if ($UpdateData == TRUE)
						{
							$QryUpdatePlanet  = "UPDATE {{table}} SET ";
							$QryUpdatePlanet .= "`b_tech_id` = '".   $WorkingPlanet['b_tech_id']   ."', ";
							$QryUpdatePlanet .= "`b_tech` = '".      $WorkingPlanet['b_tech']      ."', ";
							$QryUpdatePlanet .= "`metal` = '".       $WorkingPlanet['metal']       ."', ";
							$QryUpdatePlanet .= "`crystal` = '".     $WorkingPlanet['crystal']     ."', ";
							$QryUpdatePlanet .= "`deuterium` = '".   $WorkingPlanet['deuterium']   ."' ";
							$QryUpdatePlanet .= "WHERE ";
							$QryUpdatePlanet .= "`id` = '".          $WorkingPlanet['id']          ."';";
							doquery( $QryUpdatePlanet, 'planets');

							$QryUpdateUser  = "UPDATE {{table}} SET ";
							$QryUpdateUser .= "`b_tech_planet` = '". $CurrentUser['b_tech_planet'] ."' ";
							$QryUpdateUser .= "WHERE ";
							$QryUpdateUser .= "`id` = '".            $CurrentUser['id']            ."';";
							doquery( $QryUpdateUser, 'users');
						}

						$CurrentPlanet = $WorkingPlanet;
						if (is_array ($ThePlanet))
						{
							$ThePlanet     = $WorkingPlanet;
						}
						else
						{
							$CurrentPlanet = $WorkingPlanet;
							if ($TheCommand == 'search')

							{
								$ThePlanet = $CurrentPlanet;
							}
						}
					}
				}
				else
					die(header("location:game.php?page=buildings&mode=research"));
			}
			else
			{
				$bContinue = FALSE;
			}

			header ("Location: game.php?page=buildings&mode=research");

		}

		$TechRowTPL 	= gettemplate('buildings/buildings_research_row');
		$TechScrTPL 	= gettemplate('buildings/buildings_research_script');

		foreach($lang['tech'] as $Tech => $TechName)
		{
			if ($Tech > 105 && $Tech <= 199)
			{
				if ( IsTechnologieAccessible($CurrentUser, $CurrentPlanet, $Tech))
				{
					$RowParse['dpath']       = DPATH;
					$RowParse['tech_id']     = $Tech;
					$building_level          = $CurrentUser[$resource[$Tech]];

					if($Tech == 106)
					{
						$RowParse['tech_level']  = ($building_level == 0 ) ? "" : "(". $lang['bd_lvl'] . " ".$building_level .")" ;
						$RowParse['tech_level']  .= ($CurrentUser['rpg_technocrate'] == 0) ? "" : "<strong><font color=\"lime\"> +" . ($CurrentUser['rpg_technocrate'] * TECHNOCRATE_SPY) . $lang['bd_spy']	. "</font></strong>";
					}
					elseif($Tech == 108)
					{
						$RowParse['tech_level']  = ($building_level == 0) ? "" : "(". $lang['bd_lvl'] . " ".$building_level .")";
						$RowParse['tech_level']  .= ($CurrentUser['rpg_amiral'] == 0) ? "" : "<strong><font color=\"lime\"> +" . ($CurrentUser['rpg_amiral'] * AMIRAL) . $lang['bd_commander'] . "</font></strong>";
					}
					else
						$RowParse['tech_level']  = ($building_level == 0) ? "" : "(". $lang['bd_lvl'] . " ".$building_level.")";

					$RowParse['tech_name']   = $TechName;
					$RowParse['tech_descr']  = $lang['res']['descriptions'][$Tech];
					$RowParse['tech_price']  = GetElementPrice($CurrentUser, $CurrentPlanet, $Tech);
					$SearchTime              = GetBuildingTime($CurrentUser, $CurrentPlanet, $Tech, FALSE, $lablevel);
					$RowParse['search_time'] = ShowBuildTime($SearchTime);
					$CanBeDone               = IsElementBuyable($CurrentUser, $CurrentPlanet, $Tech);

					if (!$InResearch)
					{
						$LevelToDo = 1 + $CurrentUser[$resource[$Tech]];
						if ($CanBeDone)
						{
							if (!$this->CheckLabSettingsInQueue ( $CurrentPlanet ))
							{
								if ($LevelToDo == 1)
									$TechnoLink  = "<font color=#FF0000>".$lang['bd_research']."</font>";
								else
									$TechnoLink  = "<font color=#FF0000>".$lang['bd_research']."<br>".$lang['bd_lvl']." ".$LevelToDo."</font>";

							}
							else
							{
								$TechnoLink  = "<a href=\"game.php?page=buildings&mode=research&cmd=search&tech=".$Tech."\">";
								if ($LevelToDo == 1)
									$TechnoLink .= "<font color=#00FF00>".$lang['bd_research']."</font>";
								else
									$TechnoLink .= "<font color=#00FF00>".$lang['bd_research']."<br>".$lang['bd_lvl']." ".$LevelToDo."</font>";

								$TechnoLink  .= "</a>";
							}
						}
						else
						{
							if ($LevelToDo == 1)
								$TechnoLink  = "<font color=#FF0000>".$lang['bd_research']."</font>";
							else
								$TechnoLink  = "<font color=#FF0000>".$lang['bd_research']."<br>".$lang['bd_lvl']." ".$LevelToDo."</font>";
						}
					}
					else
					{
						if ($ThePlanet["b_tech_id"] == $Tech)
						{
							$bloc       = $lang;
							if ($ThePlanet['id'] != $CurrentPlanet['id'])
							{
								$bloc['tech_time']  = $ThePlanet["b_tech"] - time();
								$bloc['tech_name']  = "de<br>". $ThePlanet["name"];
								$bloc['tech_home']  = $ThePlanet["id"];
								$bloc['tech_id']    = $ThePlanet["b_tech_id"];
							}
							else
							{
								$bloc['tech_time']  = $CurrentPlanet["b_tech"] - time();
								$bloc['tech_name']  = "";
								$bloc['tech_home']  = $CurrentPlanet["id"];
								$bloc['tech_id']    = $CurrentPlanet["b_tech_id"];
							}
							$TechnoLink  = parsetemplate($TechScrTPL, $bloc);
						}
						else
						{
							$TechnoLink  = "<center>-</center>";
						}
					}
					$RowParse['tech_link']  = $TechnoLink;
					$TechnoList            .= parsetemplate($TechRowTPL, $RowParse);
				}
			}
		}

		$PageParse['noresearch']  = $NoResearchMessage;
		$PageParse['technolist']  = $TechnoList;
		$Page                    .= parsetemplate(gettemplate('buildings/buildings_research'), $PageParse);

		display($Page);
	}
}
?>