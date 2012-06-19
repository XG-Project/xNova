<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

	//function GetBuildingTime ($user, $planet, $Element) OLD CODE
	function GetBuildingTime ($user, $planet, $Element, $level = FALSE, $total_lab_level = 0)
	{
		global $pricelist, $resource, $reslist;

		// IF ROUTINE FIX BY JSTAR
		if($level === FALSE)
		{
			$level = ($planet[$resource[$Element]]) ? $planet[$resource[$Element]] : $user[$resource[$Element]];//ORIGINAL LINE
		}

		if (in_array($Element, $reslist['build']))
		{
			$cost_metal   = floor($pricelist[$Element]['metal']   * pow($pricelist[$Element]['factor'], $level));
			$cost_crystal = floor($pricelist[$Element]['crystal'] * pow($pricelist[$Element]['factor'], $level));
			$time         = ((($cost_crystal) + ($cost_metal)) / read_config ( 'game_speed' )) * (1 / ($planet[$resource['14']] + 1)) * pow(0.5, $planet[$resource['15']]);
			$time         = floor(($time * 60 * 60));
		}
		elseif (in_array($Element, $reslist['tech']))
		{
			$cost_metal   = floor($pricelist[$Element]['metal']   * pow($pricelist[$Element]['factor'], $level));
			$cost_crystal = floor($pricelist[$Element]['crystal'] * pow($pricelist[$Element]['factor'], $level));
			$intergal_lab = $user[$resource[123]];

			if($intergal_lab < 1)
			{
				$lablevel 	= $planet[$resource['31']];
			}
			else
			{
				$lablevel	=	$total_lab_level;
		//		$limite = $intergal_lab+1;
		//		$inves = doquery("SELECT laboratory FROM {{table}} WHERE id_owner='".intval($user['id'])."' ORDER BY laboratory DESC LIMIT ".$limite."", 'planets');
		//		$lablevel = 0;

		//		while (	$row = mysql_fetch_array ( $inves ) )
		//		{
		//			$lablevel += $row['laboratory'];
		//		}
			}

			$time         = (($cost_metal + $cost_crystal) / read_config ( 'game_speed' )) / (($lablevel + 1) * 2);
			$time         = floor(($time * 60 * 60) * (1 - (($user['rpg_technocrate']) * TECHNOCRATE_SPEED)));
		}
		elseif (in_array($Element, $reslist['defense']))
		{
			$time         = (($pricelist[$Element]['metal'] + $pricelist[$Element]['crystal']) / read_config ( 'game_speed' )) * (1 / ($planet[$resource['21']] + 1)) * pow(1 / 2, $planet[$resource['15']]);
			$time         = floor(($time * 60 * 60));
		}
		elseif (in_array($Element, $reslist['fleet']))
		{
			$time         = (($pricelist[$Element]['metal'] + $pricelist[$Element]['crystal']) / read_config ( 'game_speed' )) * (1 / ($planet[$resource['21']] + 1)) * pow(1 / 2, $planet[$resource['15']]);
			$time         = floor(($time * 60 * 60));
		}

		if ($time < 0)
		{
			$time = 0;
		}

		return $time;
	}

?>