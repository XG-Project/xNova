<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class GalaxyRows
{
	private function GetMissileRange ()
	{
		global $resource, $user;

		if ($user[$resource[117]] > 0)
		{
			$MissileRange = ($user[$resource[117]] * 2) - 1;
		}
		elseif($user[$resource[117]] == 0)
		{
			$MissileRange = 0;
		}
		return $MissileRange;
	}

	public function GetPhalanxRange($PhalanxLevel)
	{
		$PhalanxRange = 0;

		if ($PhalanxLevel > 1)
		{
			$PhalanxRange = pow($PhalanxLevel, 2) - 1;
		}
		elseif($PhalanxLevel == 1)
		{
			$PhalanxRange = 1;
		}

		return $PhalanxRange;
	}

	public function CheckAbandonMoonState($lunarow)
	{
		if (($lunarow['destruyed_moon'] + 172800) <= time() && $lunarow['destruyed_moon'] != 0)
		{
			$QryUpdateGalaxy  = "UPDATE {{table}} SET `id_luna` = '0' WHERE `galaxy` = '". intval($lunarow['galaxy']) ."' AND `system` = '". intval($lunarow['system']) ."' AND `planet` = '". intval($lunarow['planet']) ."' LIMIT 1;";
		}

		doquery( $QryUpdateGalaxy , 'galaxy');
		doquery("DELETE FROM {{table}} WHERE `id` = ".intval($lunarow['id'])."", 'planets');
	}

	public function CheckAbandonPlanetState(&$planet)
    {
        if ($planet['destruyed'] <= time())
        {
            doquery("DELETE FROM {{table}} WHERE `id_planet` = '".$planet['id_planet']."' LIMIT 1;" , 'galaxy');
            doquery("DELETE FROM {{table}} WHERE `id` = '".$planet['id_planet']."'", 'planets');
        }
    }

	public function GalaxyRowActions($GalaxyInfo, $Galaxy, $System, $Planet, $CurrentGalaxy, $CurrentSystem, $CurrentMIP)
	{
		global $user, $lang;

		if ($GalaxyInfo['id'] != $user['id'])
		{
			if ($CurrentMIP <> 0)
			{
				if ($GalaxyInfo['id'] != $user['id'])
				{
					if ($GalaxyInfo["galaxy"] == $CurrentGalaxy)
					{
						$Range = $this->GetMissileRange();
						$SystemLimitMin = $CurrentSystem - $Range;
						if ($SystemLimitMin < 1)
						{
							$SystemLimitMin = 1;
						}
						$SystemLimitMax = $CurrentSystem + $Range;

						if ($System <= $SystemLimitMax)
						{
							if ($System >= $SystemLimitMin)
							{
								$MissileBtn = TRUE;
							}
							else
							{
								$MissileBtn = FALSE;
							}
						}
						else
						{
							$MissileBtn = FALSE;
						}
					}
					else
					{
						$MissileBtn = FALSE;
					}
				}
				else
				{
					$MissileBtn = FALSE;
				}
			}
			else
			{
				$MissileBtn = FALSE;
			}

			if ($GalaxyInfo && $GalaxyInfo["destruyed"] == 0)
			{
				if ($user["settings_esp"] == "1" && $GalaxyInfo['id'])
				{
					$links .= "<a href=# onclick=\"javascript:doit(6, ".$Galaxy.", ".$System.", ".$Planet.", 1, ".$user["spio_anz"].");\" >";
					$links .= "<img src=". DPATH ."img/e.gif title=\"".$lang['gl_spy']."\" border=0></a>";
					$links .= "&nbsp;";
				}
				if ($user["settings_wri"] == "1" && $GalaxyInfo['id'])
				{
					$links .= "<a href=game.php?page=messages&mode=write&id=".$GalaxyInfo["id"].">";
					$links .= "<img src=". DPATH ."img/m.gif title=\"".$lang['write_message']."\" border=0></a>";
					$links .= "&nbsp;";
				}
				if ($user["settings_bud"] == "1" && $GalaxyInfo['id'])
				{
					$links .= "<a href=game.php?page=buddy&mode=2&u=".$GalaxyInfo['id']." >";
					$links .= "<img src=". DPATH ."img/b.gif title=\"".$lang['gl_buddy_request']."\" border=0></a>";
					$links .= "&nbsp;";
				}
				if ($user["settings_mis"] == "1" && $MissileBtn == TRUE && $GalaxyInfo['id'])
				{
					$links .= "<a href=game.php?page=galaxy&mode=2&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&current=".$user['current_planet']." >";
					$links .= "<img src=". DPATH ."img/r.gif title=\"".$lang['gl_missile_attack']."\" border=0></a>";
				}
			}
		}
		return $links;
	}

	public function GalaxyRowAlly($GalaxyInfo, $Galaxy, $System, $Planet)
	{
		global $user, $lang;

		if ($GalaxyInfo['ally_id'] && $GalaxyInfo['ally_id'] != 0)
		{
			if ($GalaxyInfo['ally_members'] > 1)
			{
				$add = $lang['gl_member_add'];
			}
			else
			{
				$add = "";
			}

			$parse					=	$lang;
			$parse['ally_name']		=	$GalaxyInfo['ally_name'];
			$parse['ally_members']	=	$GalaxyInfo['ally_members'];
			$parse['add']			=	$add;
			$parse['ally_id']		=	$GalaxyInfo['ally_id'];

			if ($GalaxyInfo["ally_web"] != "")
			{
				$parse['web'] 	   = "</tr><tr>";
				$parse['web']     .= "<td><a href=". $GalaxyInfo["ally_web"] ." target=_new>".$lang['gl_alliance_web_page']."</td>";
			}

			if ($user['ally_id'] == $GalaxyInfo['ally_id'])
			{
				$parse['tag']		= "<span class=\"allymember\">". $GalaxyInfo['ally_tag'] ."</span>";
			}
			elseif ($GalaxyInfo['ally_id'] == $user['ally_id'])
			{
				$parse['tag']  		= "<font color=lime>".$GalaxyInfo['ally_tag'] ."</font>";
			}
			else
			{
				$parse['tag']  		= $GalaxyInfo['ally_tag'];
			}
		}
		return parsetemplate(gettemplate('galaxy/galaxy_alliance_block'), $parse);
	}

	public function GalaxyRowDebris($GalaxyInfo, $Galaxy, $System, $Planet, $PlanetType, $CurrentRC)
	{
		global $user, $pricelist, $lang;

		if ($GalaxyInfo)
		{
			if ($GalaxyInfo["metal"]+$GalaxyInfo["crystal"] >= DEBRIS_MIN_VISIBLE_SIZE)
			{
				$RecNeeded = ceil(($GalaxyInfo["metal"] + $GalaxyInfo["crystal"]) / $pricelist[209]['capacity']);

				if ($RecNeeded < $CurrentRC)
				{
					$RecSended = $RecNeeded;
				}
				elseif ($RecNeeded >= $CurrentRC)
				{
					$RecSended = $CurrentRC;
				}
				else
				{
					$RecSended = $RecyclerCount;
				}

				$parse						=	$lang;
				$parse['dpath']				=	DPATH;
				$parse['galaxy']			=	$Galaxy;
				$parse['system']			=	$System;
				$parse['planet']			=	$Planet;
				$parse['planettype']		=	$PlanetType;
				$parse['recsended']			=	$RecSended;
				$parse['debris_metal']		=	number_format( $GalaxyInfo['metal'], 0, '', '.');
				$parse['debris_crystal']	=	number_format( $GalaxyInfo['crystal'], 0, '', '.');

				return parsetemplate(gettemplate('galaxy/galaxy_debris_block'), $parse);
			}
		}
		return '';
	}

	public function GalaxyRowMoon($GalaxyInfo, $Galaxy, $System, $Planet, $PlanetType)
	{
		global $user, $CanDestroy, $lang;

		if ($GalaxyInfo['id'] != $user['id'])
			$MissionType6Link = "<a href=# onclick=&#039javascript:doit(6, ".$Galaxy.", ".$System.", ".$Planet.", ".$PlanetType.", ".$user["spio_anz"].");&#039 >".$lang['type_mission'][6]."</a><br /><br />";
		elseif ($GalaxyInfo['id'] == $user['id'])
			$MissionType6Link = "";

		if ($GalaxyInfo['id'] != $user['id'])
			$MissionType1Link = "<a href=game.php?page=fleet&galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&amp;planettype=".$PlanetType."&amp;target_mission=1>".$lang['type_mission'][1]."</a><br />";
		elseif ($GalaxyInfo['id'] == $user['id'])
			$MissionType1Link = "";

		if ($GalaxyInfo['id'] != $user['id'])
			$MissionType5Link = "<a href=game.php?page=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=5>".$lang['type_mission'][5]."</a><br />";
		elseif ($GalaxyInfo['id'] == $user['id'])
			$MissionType5Link = "";

		if ($GalaxyInfo['id'] == $user['id'])
			$MissionType4Link = "<a href=game.php?page=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=4>".$lang['type_mission'][4]."</a><br />";
		elseif ($GalaxyInfo['id'] != $user['id'])
			$MissionType4Link = "";

		if ($GalaxyInfo['id'] != $user['id'])
			if ($CanDestroy > 0)
				$MissionType9Link = "<a href=game.php?page=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=9>".$lang['type_mission'][9]."</a>";
		else
			$MissionType9Link = "";
		elseif ($GalaxyInfo['id'] == $user['id'])
			$MissionType9Link = "";

		$MissionType3Link = "<a href=game.php?page=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=3>".$lang['type_mission'][3]."</a><br />";

		if ($GalaxyInfo && $GalaxyInfo["destruyed_moon"] == 0 && $GalaxyInfo["id_luna"] != 0)
		{
			$parse					=	$lang;
			$parse['dpath']			= 	DPATH;
			$parse['name_moon']		=	$GalaxyInfo["name_moon"];
			$parse['galaxy']		=	$Galaxy;
			$parse['system']		=	$System;
			$parse['planet']		=	$Planet;
			$parse['diameter']		=	number_format($GalaxyInfo['diameter'], 0, '', '.');
			$parse['temperature']	=	number_format($GalaxyInfo['temp_min'], 0, '', '.');
			$parse['links'] 		= $MissionType6Link;
			$parse['links'] 	   .= $MissionType3Link;
			$parse['links'] 	   .= $MissionType4Link;
			$parse['links'] 	   .= $MissionType1Link;
			$parse['links'] 	   .= $MissionType5Link;
			$parse['links'] 	   .= $MissionType9Link;

			return parsetemplate(gettemplate('galaxy/galaxy_moon_block'), $parse);

		}
		return '';
	}

	public function GalaxyRowPlanet($GalaxyInfo, $Galaxy, $System, $Planet, $PlanetType, $HavePhalanx, $CurrentGalaxy, $CurrentSystem)
	{
		global $user, $CurrentMIP, $CurrentSystem, $lang;

		if ($GalaxyInfo && $GalaxyInfo["destruyed"] == 0 && $GalaxyInfo["id_planet"] != 0)
		{
			if ($HavePhalanx <> 0)
			{
				if ($GalaxyInfo['id'] != $user['id'])
				{
					if ($GalaxyInfo["galaxy"] == $CurrentGalaxy)
					{
						$PhRange = $this->GetPhalanxRange ( $HavePhalanx );
						$SystemLimitMin = $CurrentSystem - $PhRange;
						if ($SystemLimitMin < 1)
							$SystemLimitMin = 1;

						$SystemLimitMax = $CurrentSystem + $PhRange;
						if ($System <= $SystemLimitMax)
						{
							if ($System >= $SystemLimitMin)
								$PhalanxTypeLink = "<a href=# onclick=fenster(&#039;game.php?page=phalanx&galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&amp;planettype=".$PlanetType."&#039;) >".$lang['gl_phalanx']."</a><br />";
							else
								$PhalanxTypeLink = "";
						}
						else
						{
							$PhalanxTypeLink = "";
						}
					}
					else
					{
						$PhalanxTypeLink = "";
					}
				}
				else
				{
					$PhalanxTypeLink = "";
				}
			}
			else
			{
				$PhalanxTypeLink = "";
			}

			if ($CurrentMIP <> 0)
			{
				if ($GalaxyInfo['id'] != $user['id'])
				{
					if ($GalaxyInfo["galaxy"] == $CurrentGalaxy)
					{
						$MiRange = $this->GetMissileRange();
						$SystemLimitMin = $CurrentSystem - $MiRange;
						if ($SystemLimitMin < 1)
							$SystemLimitMin = 1;

						$SystemLimitMax = $CurrentSystem + $MiRange;

						if ($System <= $SystemLimitMax)
						{
							if ($System >= $SystemLimitMin)
								$MissileBtn = TRUE;
							else
								$MissileBtn = FALSE;
						}
						else
						{
							$MissileBtn = FALSE;
						}
					}
					else
					{
						$MissileBtn = FALSE;
					}
				}
				else
				{
					$MissileBtn = FALSE;
				}
			}
			else
			{
				$MissileBtn = FALSE;
			}

			if ($GalaxyInfo['id'] != $user['id'])
				$MissionType6Link = "<a href=# onclick=&#039javascript:doit(6, ".$Galaxy.", ".$System.", ".$Planet.", ".$PlanetType.", ".$user["spio_anz"].");&#039 >".$lang['type_mission'][6]."</a><br /><br />";
			elseif ($GalaxyInfo['id'] == $user['id'])
				$MissionType6Link = "";

			if ($GalaxyInfo['id'] != $user['id'])
				$MissionType1Link = "<a href=game.php?page=fleet&galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&amp;planettype=".$PlanetType."&amp;target_mission=1>".$lang['type_mission'][1]."</a><br />";
			elseif ($GalaxyInfo['id'] == $user['id'])
				$MissionType1Link = "";

			if ($GalaxyInfo['id'] == $user['id'])
				$MissionType5Link = "<a href=game.php?page=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=5>".$lang['type_mission'][5]."</a><br />";
			elseif ($GalaxyInfo['id'] == $user['id'])
				$MissionType5Link = "";

			if ($GalaxyInfo['id'] == $user['id'])
				$MissionType4Link = "<a href=game.php?page=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=4>".$lang['type_mission'][4]."</a><br />";
			elseif ($GalaxyInfo['id'] != $user['id'])
				$MissionType4Link = "";

			if ($user["settings_mis"] == "1" AND $MissileBtn == TRUE && $GalaxyInfo['id'])
				$MissionType10Link = "<a href=game.php?page=galaxy&mode=2&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&current=".$user['current_planet']." >".$lang['gl_missile_attack']."</a><br />";
			elseif ($GalaxyInfo['id'] != $user['id'])
				$MissionType10Link = "";

			$MissionType3Link = "<a href=game.php?page=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=3>".$lang['type_mission'][3]."</a><br />";

			$parse				=	$lang;
			$parse['dpath']		=	DPATH;
			$parse['name']		=	$GalaxyInfo["name"];
			$parse['galaxy']	=	$Galaxy;
			$parse['system']	=	$System;
			$parse['planet']	=	$Planet;
			$parse['image']		=	$GalaxyInfo["image"];
			$parse['links'] 	= $MissionType6Link;
			$parse['links']    .= $PhalanxTypeLink;
			$parse['links']    .= $MissionType1Link;
			$parse['links']    .= $MissionType5Link;
			$parse['links']    .= $MissionType4Link;
			$parse['links']    .= $MissionType3Link;
			$parse['links']    .= $MissionType10Link;

		}
		return parsetemplate(gettemplate('galaxy/galaxy_planet_block'), $parse);
	}

	public function GalaxyRowPlanetName($GalaxyInfo, $Galaxy, $System, $Planet, $PlanetType, $HavePhalanx, $CurrentGalaxy, $CurrentSystem)
	{
		global $user, $lang;

		if ($GalaxyInfo['last_update'] > (time()-59 * 60) && $GalaxyInfo['id'] != $user['id'])
		{
			$Inactivity = Format::pretty_time_hour(time() - $GalaxyInfo['last_update']);
		}

		if ($GalaxyInfo && $GalaxyInfo["destruyed"] == 0)
		{
			if ($HavePhalanx <> 0)
			{
				if ($GalaxyInfo["galaxy"] == $CurrentGalaxy)
				{
					$Range = $this->GetPhalanxRange ( $HavePhalanx );
					if ($CurrentGalaxy + $Range <= $CurrentSystem && $CurrentSystem >= $CurrentGalaxy - $Range)
						$PhalanxTypeLink = "<a href=# onclick=fenster('game.php?page=phalanx&galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&amp;planettype=".$PlanetType."')  title=\"Phalanx\">".$GalaxyInfo['name']."</a><br />";
					else
						$PhalanxTypeLink = stripslashes($GalaxyInfo['name']);
				}
				else
				{
					$PhalanxTypeLink = stripslashes($GalaxyInfo['name']);
				}
			}
			else
			{
				$PhalanxTypeLink = stripslashes($GalaxyInfo['name']);
			}

			$planetname .= $TextColor . $PhalanxTypeLink . $EndColor;

			if ($GalaxyInfo['last_update']  > (time()-59 * 60) && $GalaxyInfo['id'] != $user['id'])
			{
				if ($GalaxyInfo['last_update']  > (time()-10 * 60) && $GalaxyInfo['id'] != $user['id'])
				{
					$planetname .= "(*)";
				}
				else
				{
					$planetname .= " (".$Inactivity.")";
				}
			}
		}
		elseif($GalaxyInfo["destruyed"] != 0)
		{
			$planetname .= $lang['gl_planet_destroyed'];
		}

		return $planetname;
	}

	public function GalaxyRowUser($GalaxyInfo, $Galaxy, $System, $Planet)
	{
		global $user, $lang;

		if ($GalaxyInfo && $GalaxyInfo["destruyed"] == 0)
		{
			$MyGameLevel		= $user['total_points'];
			$HeGameLevel		= $GalaxyInfo['total_points'];

			if ($GalaxyInfo['bana'] == 1 && $GalaxyInfo['urlaubs_modus'] == 1)
			{
				$Systemtatus2 	= "v <a href=\"game.php?page=banned\"><span class=\"banned\">".$lang['gl_b']."</span></a>";
				$Systemtatus 	= "<span class=\"vacation\">";
			}
			elseif ($GalaxyInfo['bana'] == 1)
			{
				$Systemtatus2 	= "<a href=\"game.php?page=banned\"><span class=\"banned\">".$lang['gl_b']."</span></a>";
				$Systemtatus 	= "";
			}
			elseif ($GalaxyInfo['urlaubs_modus'] == 1)
			{
				$Systemtatus2 	= "<span class=\"vacation\">".$lang['gl_v']."</span>";
				$Systemtatus 	= "<span class=\"vacation\">";
			}
			elseif ($GalaxyInfo['onlinetime'] < (time()-60 * 60 * 24 * 7) && $GalaxyInfo['onlinetime'] > (time()-60 * 60 * 24 * 28))
			{
				$Systemtatus2 	= "<span class=\"inactive\">".$lang['gl_i']."</span>";
				$Systemtatus 	= "<span class=\"inactive\">";
			}
			elseif ($GalaxyInfo['onlinetime'] < (time()-60 * 60 * 24 * 28))
			{
				$Systemtatus2 	= "<span class=\"inactive\">".$lang['gl_i']."</span><span class=\"longinactive\">".$lang['gl_I']."</span>";
				$Systemtatus 	= "<span class=\"longinactive\">";
			}
			elseif ( is_weak ( $MyGameLevel , $HeGameLevel ) && $GalaxyInfo['id'] != $user['id'] )
			{
				$Systemtatus2 	= "<span class=\"noob\">".$lang['gl_w']."</span>";
				$Systemtatus 	= "<span class=\"noob\">";
			}
			elseif ( is_strong ( $MyGameLevel , $HeGameLevel ) && $GalaxyInfo['id'] != $user['id'] )
			{
				$Systemtatus2 	= $lang['gl_s'];
				$Systemtatus 	= "<span class=\"strong\">";
			}
			else
			{
				$Systemtatus2 	= "";
				$Systemtatus 	= "";
			}
			$Systemtatus4 		= $GalaxyInfo['total_rank'];

			if ($Systemtatus2 != '')
			{
				$Systemtatus6 	= "<font color=\"white\">(</font>";
				$Systemtatus7 	= "<font color=\"white\">)</font>";
			}
			if ($Systemtatus2 == '')
			{
				$Systemtatus6 	= "";
				$Systemtatus7 	= "";
			}

			$Systemtart = $GalaxyInfo['total_rank'];

			if (strlen($Systemtart) < 3)
				$Systemtart = 1;
			else
				$Systemtart = (floor( $GalaxyInfo['total_rank'] / 100 ) * 100) + 1;

			$parse					=	$lang;
			$parse['username']		=	$GalaxyInfo['username'];
			$parse['systemtatus4']	=	$Systemtatus4;
			$parse['systemtart']	=	$Systemtart;

			if ($GalaxyInfo['id'] != $user['id'])
			{
				$parse['actions'] 	= "<td><a href=game.php?page=messages&mode=write&id=".$GalaxyInfo['id'].">".$lang['write_message']."</a></td>";
				$parse['actions']  .= "</tr><tr>";
				$parse['actions']  .= "<td><a href=game.php?page=buddy&mode=2&u=".$GalaxyInfo['id'].">".$lang['gl_buddy_request']."</a></td>";
				$parse['actions']  .= "</tr><tr>";
			}

			$parse['status'] 	 	= $Systemtatus;
			$parse['status'] 	   .= $GalaxyInfo["username"]."</span>";
			$parse['status'] 	   .= $Systemtatus6;
			$parse['status'] 	   .= $Systemtatus;
			$parse['status'] 	   .= $Systemtatus2;
			$parse['status'] 	   .= $Systemtatus7." ".$admin;
		}
		return parsetemplate(gettemplate('galaxy/galaxy_username_block'), $parse);
	}
}
?>