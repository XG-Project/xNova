<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowBuildingsPage
{
	private function BuildingSavePlanetRecord ($CurrentPlanet)
	{
		$QryUpdatePlanet  = "UPDATE {{table}} SET ";
		$QryUpdatePlanet .= "`b_building_id` = '". $CurrentPlanet['b_building_id'] ."', ";
		$QryUpdatePlanet .= "`b_building` = '".    $CurrentPlanet['b_building']    ."' ";
		$QryUpdatePlanet .= "WHERE ";
		$QryUpdatePlanet .= "`id` = '".            $CurrentPlanet['id']            ."';";
		doquery( $QryUpdatePlanet, 'planets');

		return;
	}

	private function CancelBuildingFromQueue (&$CurrentPlanet, &$CurrentUser)
	{
		$CurrentQueue  = $CurrentPlanet['b_building_id'];
		if ($CurrentQueue != 0)
		{
			$QueueArray          = explode ( ";", $CurrentQueue );
			$ActualCount         = count ( $QueueArray );
			$CanceledIDArray     = explode ( ",", $QueueArray[0] );
			$Element             = $CanceledIDArray[0];
			$BuildMode           = $CanceledIDArray[4];

			if ($ActualCount > 1)
			{
				array_shift( $QueueArray );
				$NewCount        = count( $QueueArray );
				$BuildEndTime    = time();
				for ($ID = 0; $ID < $NewCount ; $ID++ )
				{
					$ListIDArray          = explode ( ",", $QueueArray[$ID] );
					if($ListIDArray[0] == $Element)
						$ListIDArray[1] -= 1;

					$BuildEndTime        += $ListIDArray[2];
					$ListIDArray[3]       = $BuildEndTime;
					$QueueArray[$ID]      = implode ( ",", $ListIDArray );
				}
				$NewQueue        = implode(";", $QueueArray );
				$ReturnValue     = TRUE;
				$BuildEndTime    = '0';
			}
			else
			{
				$NewQueue        = '0';
				$ReturnValue     = FALSE;
				$BuildEndTime    = '0';
			}

			if ($BuildMode == 'destroy')
			{
				$ForDestroy = TRUE;
			}
			else
			{
				$ForDestroy = FALSE;
			}

			if ( $Element != FALSE ) {
			$Needed                        = GetBuildingPrice ($CurrentUser, $CurrentPlanet, $Element, TRUE, $ForDestroy);
			$CurrentPlanet['metal']       += $Needed['metal'];
			$CurrentPlanet['crystal']     += $Needed['crystal'];
			$CurrentPlanet['deuterium']   += $Needed['deuterium'];
			}

		}
		else
		{
			$NewQueue          = '0';
			$BuildEndTime      = '0';
			$ReturnValue       = FALSE;
		}

		$CurrentPlanet['b_building_id']  = $NewQueue;
		$CurrentPlanet['b_building']     = $BuildEndTime;

		return $ReturnValue;
	}

	private function RemoveBuildingFromQueue ( &$CurrentPlanet, $CurrentUser, $QueueID )
	{
		if ($QueueID > 1)
		{
			$CurrentQueue  = $CurrentPlanet['b_building_id'];

			if (!empty($CurrentQueue))
            {
                $QueueArray    = explode ( ";", $CurrentQueue );
                $ActualCount   = count ( $QueueArray );
                if ($ActualCount< 2)
                   die(header("location:game.php?page=buildings"));

				//  finding the buildings time
				$ListIDArrayToDelete   = explode ( ",", $QueueArray[$QueueID - 1] );
				$lastB	= $ListIDArrayToDelete;
				$lastID	= $QueueID-1;

				//search for biggest element
				for ( $ID = $QueueID; $ID < $ActualCount; $ID++ )
				{
					//next buildings
					$nextListIDArray     = explode ( ",", $QueueArray[$ID] );
					//if same type of element
					if($nextListIDArray[0] == $ListIDArrayToDelete[0])
					{
						$lastB=$nextListIDArray;
						$lastID=$ID;
					}
				}

				// update the rest of buildings queue
				for( $ID=$lastID; $ID < $ActualCount-1; $ID++ )
				{

					$nextListIDArray		= explode ( ",", $QueueArray[$ID+1] );
					$nextBuildEndTime    	= $nextListIDArray[3]-$lastB[2];
					$nextListIDArray[3]  	= $nextBuildEndTime;
					$QueueArray[$ID] 		= implode ( ",", $nextListIDArray );
				}

				unset ($QueueArray[$ActualCount - 1]);
				$NewQueue     = implode ( ";", $QueueArray );
			}

			$CurrentPlanet['b_building_id'] = $NewQueue;

		}

		return $QueueID;

	}

	private function AddBuildingToQueue (&$CurrentPlanet, $CurrentUser, $Element, $AddMode = TRUE)
	{
		global $resource;

		$CurrentQueue  = $CurrentPlanet['b_building_id'];

		$Queue 				= $this->ShowBuildingQueue($CurrentPlanet, $CurrentUser);
		$CurrentMaxFields  	= CalculateMaxPlanetFields($CurrentPlanet);

		if ($CurrentPlanet["field_current"] >= ($CurrentMaxFields - $Queue['lenght']) && $_GET['cmd'] != 'destroy')
			die(header("location:game.php?page=buildings"));

		if ($CurrentQueue != 0)
		{
			$QueueArray    = explode ( ";", $CurrentQueue );
			$ActualCount   = count ( $QueueArray );
		}
		else
		{
			$QueueArray    = "";
			$ActualCount   = 0;
		}

		if ($AddMode == TRUE)
		{
			$BuildMode = 'build';
		}
		else
		{
			$BuildMode = 'destroy';
		}

		if ( $ActualCount < MAX_BUILDING_QUEUE_SIZE)
		{
			$QueueID      = $ActualCount + 1;
		}
		else
		{
			$QueueID      = FALSE;
		}

		if ( $QueueID != FALSE && IsElementBuyable ($CurrentUser, $CurrentPlanet, $Element, TRUE, FALSE) && IsTechnologieAccessible($CurrentUser, $CurrentPlanet, $Element) )
		{
			if ($QueueID > 1)
			{
				$InArray = 0;
				for ( $QueueElement = 0; $QueueElement < $ActualCount; $QueueElement++ )
				{
					$QueueSubArray = explode ( ",", $QueueArray[$QueueElement] );
					if ($QueueSubArray[0] == $Element)
					{
						$InArray++;
					}
				}
			}
			else
			{
				$InArray = 0;
			}

			if ($InArray != 0)
			{
				$ActualLevel  = $CurrentPlanet[$resource[$Element]];
				if ($AddMode == TRUE)
				{
					$BuildLevel   = $ActualLevel + 1 + $InArray;
					$CurrentPlanet[$resource[$Element]] += $InArray;
					$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
					$CurrentPlanet[$resource[$Element]] -= $InArray;
				}
				else
				{
					$BuildLevel   = $ActualLevel - 1 - $InArray;
					$CurrentPlanet[$resource[$Element]] -= $InArray;
					$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element) / 2;
					$CurrentPlanet[$resource[$Element]] += $InArray;
				}
			}
			else
			{
				$ActualLevel  = $CurrentPlanet[$resource[$Element]];
				if ($AddMode == TRUE)
				{
					$BuildLevel   = $ActualLevel + 1;
					$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
				}
				else
				{
					$BuildLevel   = $ActualLevel - 1;
					$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element) / 2;
				}
			}

			if ($QueueID == 1)
			{
				$BuildEndTime = time() + $BuildTime;
			}
			else
			{
				$PrevBuild = explode (",", $QueueArray[$ActualCount - 1]);
				$BuildEndTime = $PrevBuild[3] + $BuildTime;
			}

			$QueueArray[$ActualCount]       = $Element .",". $BuildLevel .",". $BuildTime .",". $BuildEndTime .",". $BuildMode;
			$NewQueue                       = implode ( ";", $QueueArray );
			$CurrentPlanet['b_building_id'] = $NewQueue;
		}
		return $QueueID;
	}

	private function ShowBuildingQueue ( $CurrentPlanet, $CurrentUser, &$Sprice = FALSE )
	{
		global $lang;

		$CurrentQueue  = $CurrentPlanet['b_building_id'];
		$QueueID       = 0;
		if ($CurrentQueue != 0)
		{
			$QueueArray    = explode ( ";", $CurrentQueue );
			$ActualCount   = count ( $QueueArray );
		}
		else
		{
			$QueueArray    = "0";
			$ActualCount   = 0;
		}

		$ListIDRow    = "";

		if ($ActualCount != 0)
		{
			$PlanetID     = $CurrentPlanet['id'];
			for ($QueueID = 0; $QueueID < $ActualCount; $QueueID++)
			{
				$BuildArray   = explode (",", $QueueArray[$QueueID]);
				$BuildEndTime = floor($BuildArray[3]);
				$CurrentTime  = floor(time());
				if ($BuildEndTime >= $CurrentTime)
				{
					$ListID       = $QueueID + 1;
					$Element      = $BuildArray[0];
					$BuildLevel   = $BuildArray[1];
					$BuildMode    = $BuildArray[4];
					$BuildTime    = $BuildEndTime - time();
					$ElementTitle = $lang['tech'][$Element];
					// START FIX BY JSTAR
					if ( $Sprice !== FALSE && $BuildLevel > $Sprice[$Element] )
						$Sprice[$Element]	=	$BuildLevel;
					// END FIX BY JSTAR

					if ($ListID > 0)
					{
						$ListIDRow .= "<tr>";
						if ($BuildMode == 'build')
						{
							$ListIDRow .= "	<td class=\"l\" colspan=\"2\">". $ListID .".: ". $ElementTitle ." ". $BuildLevel ."</td>";
						}
						else
						{
							$ListIDRow .= "	<td class=\"l\" colspan=\"2\">". $ListID .".: ". $ElementTitle ." ". $BuildLevel . " " . $lang['bd_dismantle']."</td>";
						}
						$ListIDRow .= "	<td class=\"k\">";

						if ($ListID == 1)
						{
							$ListIDRow .= "		<div id=\"blc\" class=\"z\">". $BuildTime ."<br>";
							$ListIDRow .= "		<a href=\"game.php?page=buildings&listid=". $ListID ."&amp;cmd=cancel&amp;planet=". $PlanetID ."\">".$lang['bd_interrupt']."</a></div>";
							$ListIDRow .= "		<script language=\"JavaScript\">";
							$ListIDRow .= "			pp = \"". $BuildTime ."\";\n";
							$ListIDRow .= "			pk = \"". $ListID ."\";\n";
							$ListIDRow .= "			pm = \"cancel\";\n";
							$ListIDRow .= "			pl = \"". $PlanetID ."\";\n";
							$ListIDRow .= "			t();\n";
							$ListIDRow .= "		</script>";
							$ListIDRow .= "		<strong color=\"lime\"><br><font color=\"lime\">". date("j/m H:i:s" ,$BuildEndTime) ."</font></strong>";
						}
						else



						{
							$ListIDRow .= "		<font color=\"red\">";
							$ListIDRow .= "		<a href=\"game.php?page=buildings&listid=". $ListID ."&amp;cmd=remove&amp;planet=". $PlanetID ."\">".$lang['bd_cancel']."</a></font>";
						}
						$ListIDRow .= "	</td>";
						$ListIDRow .= "</tr>";
					}
				}
			}
		}

		$RetValue['lenght']    = $ActualCount;
		$RetValue['buildlist'] = $ListIDRow;

		return $RetValue;
	}

	public function __construct (&$CurrentPlanet, $CurrentUser)
	{
		global $ProdGrid, $lang, $resource, $reslist, $_GET;

		include_once(XGP_ROOT . 'includes/functions/IsTechnologieAccessible.php');
		include_once(XGP_ROOT . 'includes/functions/GetElementPrice.php');
		include_once(XGP_ROOT . 'includes/functions/CheckPlanetUsedFields.php');

		CheckPlanetUsedFields ( $CurrentPlanet );

		$parse			= $lang;
		$Allowed['1'] 	= array(  1,  2,  3,  4, 12, 14, 15, 21, 22, 23, 24, 31, 33, 34, 44);
		$Allowed['3'] 	= array( 12, 14, 21, 22, 23, 24, 34, 41, 42, 43);


		if (isset($_GET['cmd']))
		{
			$bDoItNow 	= FALSE;
			$TheCommand = $_GET['cmd'];
			$Element 	= $_GET['building'];
			$ListID 	= $_GET['listid'];

			if (!in_array( trim($Element), $Allowed[$CurrentPlanet['planet_type']]))
			{
				unset($Element);
			}

			if( isset ( $Element ))
			{
				if ( !strchr ( $Element, ",") && !strchr ( $Element, " ") &&
					 !strchr ( $Element, "+") && !strchr ( $Element, "*") &&
					 !strchr ( $Element, "~") && !strchr ( $Element, "=") &&
					 !strchr ( $Element, ";") && !strchr ( $Element, "'") &&
					 !strchr ( $Element, "#") && !strchr ( $Element, "-") &&
					 !strchr ( $Element, "_") && !strchr ( $Element, "[") &&
					 !strchr ( $Element, "]") && !strchr ( $Element, ".") &&
					 !strchr ( $Element, ":"))
				{
					if (in_array( trim($Element), $Allowed[$CurrentPlanet['planet_type']]))
					{
						$bDoItNow = TRUE;
					}
				}
				else
				{
					header("location:game.php?page=buildings");
				}
			}
			elseif ( isset ( $ListID ))
			{
				$bDoItNow = TRUE;
			}

			if ($Element == 31 && $CurrentUser["b_tech_planet"] != 0)
			{
				$bDoItNow = FALSE;
			}

			if ( ( $Element == 21 or $Element == 14 or $Element == 15 ) && $CurrentPlanet["b_hangar"] != 0)
			{
				$bDoItNow = FALSE;
			}

			if ($bDoItNow == TRUE)
			{
				switch($TheCommand)
				{
					case 'cancel':
						$this->CancelBuildingFromQueue ($CurrentPlanet, $CurrentUser);
					break;
					case 'remove':
						$this->RemoveBuildingFromQueue ($CurrentPlanet, $CurrentUser, $ListID);
					break;
					case 'insert':
						$this->AddBuildingToQueue ($CurrentPlanet, $CurrentUser, $Element, TRUE);
					break;
					case 'destroy':
						$this->AddBuildingToQueue ($CurrentPlanet, $CurrentUser, $Element, FALSE);
					break;
				}
			}

			if ( $_GET['r'] == 'overview' )
			{
				header('location:game.php?page=overview');
			}
			else
			{
				header ("Location: game.php?page=buildings&mode=buildings");
			}
		}

		SetNextQueueElementOnTop($CurrentPlanet, $CurrentUser);
		// $Queue = $this->ShowBuildingQueue($CurrentPlanet, $CurrentUser); // OLD CODE
		// START FIX BY JSTAR
		$Sprice	=	array();
		$Queue 	= 	$this->ShowBuildingQueue($CurrentPlanet, $CurrentUser, $Sprice);
		// END FIX BY JSTAR
		$this->BuildingSavePlanetRecord($CurrentPlanet);

		if ($Queue['lenght'] < (MAX_BUILDING_QUEUE_SIZE))
		{
			$CanBuildElement = TRUE;
		}
		else
		{
			$CanBuildElement = FALSE;
		}

		$BuildingPage        = "";
		$zaehler         	 = 1;

		foreach($lang['tech'] as $Element => $ElementName)
		{
			if (in_array($Element, $Allowed[$CurrentPlanet['planet_type']]))
			{
				$CurrentMaxFields      = CalculateMaxPlanetFields($CurrentPlanet);
				if ($CurrentPlanet["field_current"] < ($CurrentMaxFields - $Queue['lenght']))
				{
					$RoomIsOk = TRUE;
				}
				else
				{
					$RoomIsOk = FALSE;
				}

				if (IsTechnologieAccessible($CurrentUser, $CurrentPlanet, $Element))
				{

					$HaveRessources        	= IsElementBuyable ($CurrentUser, $CurrentPlanet, $Element, TRUE, FALSE);
					$parse                 	= array();
					$parse 					= $lang;
					$parse['dpath']        	= DPATH;
					$parse['i']            	= $Element;
					$BuildingLevel         	= $CurrentPlanet[$resource[$Element]];
					$parse['nivel']        	= ($BuildingLevel == 0) ? "" : " (". $lang['bd_lvl'] . " " . $BuildingLevel .")";
					$parse['n']            	= $ElementName;
					$parse['descriptions'] 	= $lang['res']['descriptions'][$Element];
/* OLD CODE ---------------------------------------------------- OLD CODE ------------------------------------- //
					$ElementBuildTime      	= GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
					$parse['time']         	= ShowBuildTime($ElementBuildTime);
					$parse['price']        	= GetElementPrice($CurrentUser, $CurrentPlanet, $Element);
   OLD CODE ---------------------------------------------------- OLD CODE ------------------------------------- //
*/
					// START FIX BY JSTAR
					$really_lvl 			= ( isset ( $Sprice[$Element] ) ) ? $Sprice[$Element]:$BuildingLevel;
					$ElementBuildTime 		= GetBuildingTime ( $CurrentUser , $CurrentPlanet , $Element , $really_lvl );
					$parse['price'] 		= GetElementPrice ( $CurrentUser , $CurrentPlanet , $Element , TRUE , $really_lvl );
					$parse['time'] 			= ShowBuildTime ( $ElementBuildTime );
					// END FIX BY JSTAR

					$parse['click']        	= '';
					$NextBuildLevel        	= $CurrentPlanet[$resource[$Element]] + 1;

					if ($RoomIsOk && $CanBuildElement)
					{
						if ($Queue['lenght'] == 0)
						{
							if ($NextBuildLevel == 1)
							{
								if ( $HaveRessources == TRUE )
									$parse['click'] = "<a href=\"game.php?page=buildings&cmd=insert&building=". $Element ."\"><font color=#00FF00>".$lang['bd_build']."</font></a>";
								else
									$parse['click'] = "<font color=#FF0000>".$lang['bd_build']."</font>";
							}
							else
							{
								if ( $HaveRessources == TRUE )
									$parse['click'] = "<a href=\"game.php?page=buildings&cmd=insert&building=". $Element ."\"><font color=#00FF00>". $lang['bd_build_next_level'] . $NextBuildLevel ."</font></a>";
								else
									$parse['click'] = "<font color=#FF0000>". $lang['bd_build_next_level'] . $NextBuildLevel ."</font>";
							}
						}
						else
						{
							$parse['click'] = "<a href=\"game.php?page=buildings&cmd=insert&building=". $Element ."\"><font color=#00FF00>".$lang['bd_add_to_list']."</font></a>";
						}
					}
					elseif ($RoomIsOk && !$CanBuildElement)
					{
						if ($NextBuildLevel == 1)
							$parse['click'] = "<font color=#FF0000>".$lang['bd_build']."</font>";
						else
							$parse['click'] = "<font color=#FF0000>". $lang['bd_build_next_level'] . $NextBuildLevel ."</font>";
					}
					else
						$parse['click'] = "<font color=#FF0000>".$lang['bd_no_more_fields']."</font>";

					if ($Element == 31 && $CurrentUser["b_tech_planet"] != 0)
					{
						$parse['click'] = "<font color=#FF0000>".$lang['bd_working']."</font>";
					}

					if ( ( $Element == 21 or $Element == 14 or $Element == 15 ) && $CurrentPlanet["b_hangar"] != 0)
					{
						$parse['click'] = "<font color=#FF0000>".$lang['bd_working']."</font>";
					}

					$BuildingPage .= parsetemplate(gettemplate('buildings/buildings_builds_row'), $parse);
				}
			}
		}

		if ($Queue['lenght'] > 0)
		{
			include(XGP_ROOT . 'includes/functions/InsertBuildListScript.php');

			$parse['BuildListScript']  = InsertBuildListScript ("buildings");
			$parse['BuildList']        = $Queue['buildlist'];
		}
		else
		{
			$parse['BuildListScript']  = "";
			$parse['BuildList']        = "";
		}

		$parse['BuildingsList']        = $BuildingPage;

		display(parsetemplate(gettemplate('buildings/buildings_builds'), $parse));
	}
}
?>