<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowShipyardPage
{
	//optimized by alivan & jstar
	private function GetMaxConstructibleElements ($Element, $Ressources)
    {
        global $pricelist;

        $Buildable=array();
        if ($pricelist[$Element]['metal'] != 0)
            $Buildable['metal']     = floor($Ressources["metal"] / $pricelist[$Element]['metal']);

        if ($pricelist[$Element]['crystal'] != 0)
            $Buildable['crystal']   = floor($Ressources["crystal"] / $pricelist[$Element]['crystal']);

        if ($pricelist[$Element]['deuterium'] != 0)
            $Buildable['deuterium'] = floor($Ressources["deuterium"] / $pricelist[$Element]['deuterium']);

        if ($pricelist[$Element]['energy'] != 0)
            $Buildable['energy']    = floor($Ressources["energy_max"] / $pricelist[$Element]['energy']);

        return max(min($Buildable),0);
    }

	private function GetElementRessources($Element, $Count)
	{
		global $pricelist;

		$ResType['metal']     = ($pricelist[$Element]['metal']     * $Count);
		$ResType['crystal']   = ($pricelist[$Element]['crystal']   * $Count);
		$ResType['deuterium'] = ($pricelist[$Element]['deuterium'] * $Count);

		return $ResType;
	}

	private function ElementBuildListBox ( $CurrentUser, $CurrentPlanet )
	{
		global $lang, $pricelist;

		$ElementQueue = explode(';', $CurrentPlanet['b_hangar_id']);
		$NbrePerType  = "";
		$NamePerType  = "";
		$TimePerType  = "";

		foreach($ElementQueue as $ElementLine => $Element)
		{
			if ($Element != '')
			{
				$Element 		= explode(',', $Element);
				$ElementTime  	= GetBuildingTime( $CurrentUser, $CurrentPlanet, $Element[0] );
				$QueueTime   	+= $ElementTime * $Element[1];
				$TimePerType 	.= "".$ElementTime.",";
				$NamePerType 	.= "'". html_entity_decode ( $lang['tech'][$Element[0]], ENT_COMPAT , "utf-8" ) ."',";
				$NbrePerType 	.= "".$Element[1].",";
			}
		}

		$parse 							= $lang;
		$parse['a'] 					= $NbrePerType;
		$parse['b'] 					= $NamePerType;
		$parse['c'] 					= $TimePerType;
		$parse['b_hangar_id_plus'] 		= $CurrentPlanet['b_hangar'];
		$parse['pretty_time_b_hangar'] 	= Format::pretty_time($QueueTime - $CurrentPlanet['b_hangar']);
		$text .= parsetemplate(gettemplate('buildings/buildings_script'), $parse);

		return $text;
	}

	public function FleetBuildingPage ( &$CurrentPlanet, $CurrentUser )
	{
		global $lang, $resource;

		include_once(XGP_ROOT . 'includes/functions/IsTechnologieAccessible.php');
		include_once(XGP_ROOT . 'includes/functions/GetElementPrice.php');

		$parse = $lang;

		if (isset($_POST['fmenge']))
		{
			$AddedInQueue = FALSE;

			foreach($_POST['fmenge'] as $Element => $Count)
			{
				if($Element < 200 OR $Element > 300)
				{
					continue;
				}

				$Element = intval($Element);
				$Count   = intval($Count);

				if ($Count > MAX_FLEET_OR_DEFS_PER_ROW)
				{
					$Count = MAX_FLEET_OR_DEFS_PER_ROW;
				}

				if ($Count != 0)
				{
					if ( IsTechnologieAccessible ($CurrentUser, $CurrentPlanet, $Element) )
					{
						$MaxElements   = $this->GetMaxConstructibleElements ( $Element, $CurrentPlanet );

						if ($Count > $MaxElements)
							$Count = $MaxElements;

						$Ressource = $this->GetElementRessources ( $Element, $Count );

						if ($Count >= 1)
						{
							$CurrentPlanet['metal']          -= $Ressource['metal'];
							$CurrentPlanet['crystal']        -= $Ressource['crystal'];
							$CurrentPlanet['deuterium']      -= $Ressource['deuterium'];
							$CurrentPlanet['b_hangar_id']    .= "". $Element .",". $Count .";";
						}
					}
				}
			}

			header ("Location: game.php?page=buildings&mode=fleet");

		}

		if ($CurrentPlanet[$resource[21]] == 0)
			message($lang['bd_shipyard_required'], '', '', TRUE);

		$NotBuilding = TRUE;

		if ($CurrentPlanet['b_building_id'] != 0)
		{
			$CurrentQueue = $CurrentPlanet['b_building_id'];
			if (strpos ($CurrentQueue, ";"))
			{
				// FIX BY LUCKY - IF THE SHIPYARD IS IN QUEUE THE USER CANT RESEARCH ANYTHING...
				$QueueArray		= explode (";", $CurrentQueue);

				for($i = 0; $i < MAX_BUILDING_QUEUE_SIZE; $i++)
				{
					$ListIDArray	= explode (",", $QueueArray[$i]);
					$Element		= $ListIDArray[0];

					if ( ($Element == 21 ) or ( $Element == 14 ) or ( $Element == 15 ) )
					{
						break;
					}
				}
				// END - FIX
			}
			else
			{
				$CurrentBuilding = $CurrentQueue;
			}

			if ( ( ( $CurrentBuilding == 21 ) or ( $CurrentBuilding == 14 ) or ( $CurrentBuilding == 15 ) ) or  (($Element == 21 ) or ( $Element == 14 ) or ( $Element == 15 )) ) // ADDED (or $Element == 21) BY LUCKY
			{
				$parse[message] = "<font color=\"red\">".$lang['bd_building_shipyard']."</font>";
				$NotBuilding = FALSE;
			}
		}

		$TabIndex = 0;
		foreach($lang['tech'] as $Element => $ElementName)
		{
			if ($Element > 201 && $Element <= 399)
			{
				if (IsTechnologieAccessible($CurrentUser, $CurrentPlanet, $Element))
				{
					$CanBuildOne         			= IsElementBuyable($CurrentUser, $CurrentPlanet, $Element, FALSE);
					$BuildOneElementTime 			= GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
					$ElementCount        			= $CurrentPlanet[$resource[$Element]];
					$ElementNbre         			= ($ElementCount == 0) ? "" : " (". $lang['bd_available'] . Format::pretty_number($ElementCount) . ")";

					$parse['dpath']					= DPATH;
					$parse['add_element']			= '';
					$parse['element']				= $Element;
					$parse['element_name']			= $ElementName;
					$parse['element_description']	= $lang['res']['descriptions'][$Element];
					$parse['element_price']			= GetElementPrice($CurrentUser, $CurrentPlanet, $Element, FALSE);
					$parse['building_time']			= ShowBuildTime($BuildOneElementTime);
					$parse['element_nbre']			= $ElementNbre;

					if ($CanBuildOne && $NotBuilding)
					{
						$TabIndex++;
						$parse['add_element'] 	= "<input type=text name=fmenge[".$Element."] alt='".$lang['tech'][$Element]."' size=6 maxlength=6 value=0 tabindex=".$TabIndex.">";
					}

					if($NotBuilding)
					{
						$parse[build_fleet] 	= "<tr><td class=\"c\" colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"".$lang['bd_build_ships']."\"></td></tr>";
					}

					$PageTable .= parsetemplate(gettemplate('buildings/buildings_fleet_row'), $parse);
				}
			}
		}

		if ($CurrentPlanet['b_hangar_id'] != '')
			$BuildQueue .= $this->ElementBuildListBox( $CurrentUser, $CurrentPlanet );

		$parse['buildlist']    	= $PageTable;
		$parse['buildinglist'] 	= $BuildQueue;
		display(parsetemplate(gettemplate('buildings/buildings_fleet'), $parse));
	}

	public function DefensesBuildingPage ( &$CurrentPlanet, $CurrentUser )
	{
		global $lang, $resource, $_POST;

		include_once(XGP_ROOT . 'includes/functions/IsTechnologieAccessible.php');
		include_once(XGP_ROOT . 'includes/functions/GetElementPrice.php');

		$parse = $lang;

		if (isset($_POST['fmenge']))
		{
			$Missiles[502] = $CurrentPlanet[ $resource[502] ];
			$Missiles[503] = $CurrentPlanet[ $resource[503] ];
			$SiloSize      = $CurrentPlanet[ $resource[44] ];
			$MaxMissiles   = $SiloSize * 10;
			$BuildQueue    = $CurrentPlanet['b_hangar_id'];
			$BuildArray    = explode (";", $BuildQueue);

			for ($QElement = 0; $QElement < count($BuildArray); $QElement++)
			{
				$ElmentArray = explode (",", $BuildArray[$QElement] );
				if($ElmentArray[0] == 502)
				{
					$Missiles[502] += $ElmentArray[1];
				}
				elseif($ElmentArray[0] == 503)
				{
					$Missiles[503] += $ElmentArray[1];
				}
			}

			foreach($_POST['fmenge'] as $Element => $Count)
			{
				if($Element < 300 OR $Element > 550)
				{
					continue;
				}

				$Element = intval($Element);
				$Count   = intval($Count);

				if ($Count > MAX_FLEET_OR_DEFS_PER_ROW)
				{
					$Count = MAX_FLEET_OR_DEFS_PER_ROW;
				}

				if ($Count != 0)
				{
					$InQueue = strpos ( $CurrentPlanet['b_hangar_id'], $Element.",");
					$IsBuildp = ($CurrentPlanet[$resource[407]] >= 1) ? TRUE : FALSE;
					$IsBuildg = ($CurrentPlanet[$resource[408]] >= 1) ? TRUE : FALSE;

					if ( $Element == 407 && !$IsBuildp && $InQueue === FALSE )
					{
						$Count = 1;
					}

					if ( $Element == 408 && !$IsBuildg && $InQueue === FALSE )
					{
						$Count = 1;
					}

					if (IsTechnologieAccessible($CurrentUser, $CurrentPlanet, $Element))
					{
						$MaxElements = $this->GetMaxConstructibleElements ( $Element, $CurrentPlanet );

						if ($Element == 502 || $Element == 503)
						{
							$ActuMissiles  = $Missiles[502] + ( 2 * $Missiles[503] );
							$MissilesSpace = $MaxMissiles - $ActuMissiles;
							if ($Element == 502)
							{
								if ( $Count > $MissilesSpace )
								{
									$Count = $MissilesSpace;
								}

							}
							else
							{
								if ( $Count > floor( $MissilesSpace / 2 ) )
								{
									$Count = floor( $MissilesSpace / 2 );
								}
							}

							if ($Count > $MaxElements)
							{
								$Count = $MaxElements;
							}


							$Missiles[$Element] += $Count;
						}
						else
						{
							if ($Count > $MaxElements)
							{
								$Count = $MaxElements;
							}

						}

						$Ressource = $this->GetElementRessources ( $Element, $Count );

						if ($Count >= 1)
						{
							$CurrentPlanet['metal']           -= $Ressource['metal'];
							$CurrentPlanet['crystal']         -= $Ressource['crystal'];
							$CurrentPlanet['deuterium']       -= $Ressource['deuterium'];
							$CurrentPlanet['b_hangar_id']     .= "". $Element .",". $Count .";";
						}
					}
				}
			}

			header ("Location: game.php?page=buildings&mode=defense");

		}

		if ($CurrentPlanet[$resource[21]] == 0)
			message($lang['bd_shipyard_required'], '', '', TRUE);

		$NotBuilding = TRUE;

		if ($CurrentPlanet['b_building_id'] != 0)
		{
			$CurrentQueue = $CurrentPlanet['b_building_id'];
			if (strpos ($CurrentQueue, ";"))
			{
				// FIX BY LUCKY - IF THE SHIPYARD IS IN QUEUE THE USER CANT RESEARCH ANYTHING...
				$QueueArray		= explode (";", $CurrentQueue);

				for($i = 0; $i < MAX_BUILDING_QUEUE_SIZE; $i++)
				{
					$ListIDArray	= explode (",", $QueueArray[$i]);
					$Element		= $ListIDArray[0];

					if ( ($Element == 21 ) or ( $Element == 14 ) or ( $Element == 15 ) )
					{
						break;
					}
				}
				// END - FIX
			}
			else
			{
				$CurrentBuilding = $CurrentQueue;
			}

			if ( ( ( $CurrentBuilding == 21 ) or ( $CurrentBuilding == 14 ) or ( $CurrentBuilding == 15 ) ) or  (($Element == 21 ) or ( $Element == 14 ) or ( $Element == 15 )) ) // ADDED (or $Element == 21) BY LUCKY
			{
				$parse[message] = "<font color=\"red\">".$lang['bd_building_shipyard']."</font>";
				$NotBuilding = FALSE;
			}


		}

		$TabIndex  = 0;
		$PageTable = "";
		foreach($lang['tech'] as $Element => $ElementName)
		{
			if ($Element > 400 && $Element <= 599)
			{
				if (IsTechnologieAccessible($CurrentUser, $CurrentPlanet, $Element))
				{
					$CanBuildOne         			= IsElementBuyable($CurrentUser, $CurrentPlanet, $Element, FALSE);
					$BuildOneElementTime 			= GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
					$ElementCount        			= $CurrentPlanet[$resource[$Element]];
					$ElementNbre         			= ($ElementCount == 0) ? "" : " (". $lang['bd_available'] . Format::pretty_number($ElementCount) . ")";

					$parse['add_element']			= '';
					$parse['dpath']					= DPATH;
					$parse['element']				= $Element;
					$parse['element_name']			= $ElementName;
					$parse['element_description']	= $lang['res']['descriptions'][$Element];
					$parse['element_price']			= GetElementPrice($CurrentUser, $CurrentPlanet, $Element, FALSE);
					$parse['building_time']			= ShowBuildTime($BuildOneElementTime);
					$parse['element_nbre']			= $ElementNbre;

					if ($CanBuildOne)
					{
						$InQueue = strpos ( $CurrentPlanet['b_hangar_id'], $Element.",");
						$IsBuildp = ($CurrentPlanet[$resource[407]] >= 1) ? TRUE : FALSE;
						$IsBuildg = ($CurrentPlanet[$resource[408]] >= 1) ? TRUE : FALSE;
						$BuildIt = TRUE;

						if ($Element == 407 || $Element == 408 )
						{
							$BuildIt = FALSE;

							if ( $Element == 407 && !$IsBuildp && $InQueue === FALSE )
								$BuildIt = TRUE;

							if ( $Element == 408 && !$IsBuildg && $InQueue === FALSE )
								$BuildIt = TRUE;

						}

						if (!$BuildIt)
						{
							$parse['add_element'] = "<font color=\"red\">".$lang['bd_protection_shield_only_one']."</font>";
						}
						elseif($NotBuilding)
						{
							$TabIndex++;
							$parse['add_element'] = "<input type=text name=fmenge[".$Element."] alt='".$lang['tech'][$Element]."' size=6 maxlength=6 value=0 tabindex=".$TabIndex.">";
						}

						if($NotBuilding)
						{
							$parse[build_defenses] = "<tr><td class=\"c\" colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"".$lang['bd_build_defenses']."\"></td></tr>";
						}
					}

					$PageTable .= parsetemplate(gettemplate('buildings/buildings_fleet_row'), $parse);
				}
			}
		}

		if ($CurrentPlanet['b_hangar_id'] != '')
			$BuildQueue .= $this->ElementBuildListBox( $CurrentUser, $CurrentPlanet );

		$parse['buildlist']    	= $PageTable;
		$parse['buildinglist'] 	= $BuildQueue;
		display(parsetemplate(gettemplate('buildings/buildings_defense'), $parse));
	}
}
?>