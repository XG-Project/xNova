<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

	function ShowTopNavigationBar ($CurrentUser, $CurrentPlanet)
	{
		global $lang;

		if($CurrentUser['urlaubs_modus'] == 0)
			PlanetResourceUpdate($CurrentUser, $CurrentPlanet, time());
		else
			doquery("UPDATE {{table}} SET `deuterium_sintetizer_porcent` = 0, `metal_mine_porcent` = 0, `crystal_mine_porcent` = 0 WHERE id_owner = ".intval($CurrentUser['id']),"planets");

		$parse				 			= $lang;
		$parse['dpath']      			= DPATH;
		$parse['image']      			= $CurrentPlanet['image'];


		if($CurrentUser['urlaubs_modus'] && $CurrentUser['db_deaktjava'])
		{
		$parse['show_umod_notice']      	.= $CurrentUser['db_deaktjava'] ? '<table width="100%" style="border: 2px solid red; text-align:center;background:transparent;"><tr style="background:transparent;"><td style="background:transparent;">' . $lang['tn_delete_mode'] . date('d.m.Y h:i:s',$CurrentUser['db_deaktjava'] + (60 * 60 * 24 * 7)).'</td></tr></table>' : '';
	}
		else
		{
			$parse['show_umod_notice']       = $CurrentUser['urlaubs_modus'] ? '<table width="100%" style="border: 2px solid #1DF0F0; text-align:center;background:transparent;"><tr style="background:transparent;"><td style="background:transparent;">' . $lang['tn_vacation_mode'] . date('d.m.Y h:i:s',$CurrentUser['urlaubs_until']).'</td></tr></table><br>' : '';
			$parse['show_umod_notice']      .= $CurrentUser['db_deaktjava'] ? '<table width="100%" style="border: 2px solid red; text-align:center;background:transparent;"><tr style="background:transparent;"><td style="background:transparent;">' . $lang['tn_delete_mode'] . date('d.m.Y h:i:s',$CurrentUser['db_deaktjava'] + (60 * 60 * 24 * 7)).'</td></tr></table>' : '';
		}

		$parse['planetlist'] 			= '';
		$ThisUsersPlanets    			= SortUserPlanets ( $CurrentUser );

		while ($CurPlanet = mysql_fetch_array($ThisUsersPlanets))
		{
			if ($CurPlanet["destruyed"] == 0)
			{
				$parse['planetlist'] .= "\n<option ";
				if ($CurPlanet['id'] == $CurrentUser['current_planet'])
					$parse['planetlist'] .= "selected=\"selected\" ";
				$parse['planetlist'] .= "value=\"game.php?page=$_GET[page]&gid=$_GET[gid]&cp=".$CurPlanet['id']."";
				$parse['planetlist'] .= "&amp;mode=".$_GET['mode'];
				$parse['planetlist'] .= "&amp;re=0\">";
				if($CurPlanet['planet_type'] != 3)
					$parse['planetlist'] .= "".$CurPlanet['name'];
				else
					$parse['planetlist'] .= "".$CurPlanet['name'] . " (" . $lang['fcm_moon'] . ")";
				$parse['planetlist'] .= "&nbsp;[".$CurPlanet['galaxy'].":";
				$parse['planetlist'] .= "".$CurPlanet['system'].":";
				$parse['planetlist'] .= "".$CurPlanet['planet'];
				$parse['planetlist'] .= "]&nbsp;&nbsp;</option>";
			}
		}

		$energy = Format::pretty_number($CurrentPlanet["energy_max"] + $CurrentPlanet["energy_used"]) . "/" . Format::pretty_number($CurrentPlanet["energy_max"]);
		// Energie
		if (($CurrentPlanet["energy_max"] + $CurrentPlanet["energy_used"]) < 0) {
			$parse['energy'] = Format::color_red($energy);
		} else {
			$parse['energy'] = $energy;
		}
		// Metal
		$metal = Format::pretty_number($CurrentPlanet["metal"]);
		if (($CurrentPlanet["metal"] >= $CurrentPlanet["metal_max"])) {
			$parse['metal'] = Format::color_red($metal);
		} else {
			$parse['metal'] = $metal;
		}
		// Cristal
		$crystal = Format::pretty_number($CurrentPlanet["crystal"]);
		if (($CurrentPlanet["crystal"] >= $CurrentPlanet["crystal_max"])) {
			$parse['crystal'] = Format::color_red($crystal);
		} else {
			$parse['crystal'] = $crystal;
		}
		// Deuterium
		$deuterium = Format::pretty_number($CurrentPlanet["deuterium"]);
		if (($CurrentPlanet["deuterium"] >= $CurrentPlanet["deuterium_max"])) {
			$parse['deuterium'] = Format::color_red($deuterium);
		} else {
			$parse['deuterium'] = $deuterium;
		}
		$parse['darkmatter'] 		= Format::pretty_number($CurrentUser["darkmatter"]);
		$TopBar 			 		= parsetemplate(gettemplate('general/topnav'), $parse);

		return $TopBar;
	}
?>