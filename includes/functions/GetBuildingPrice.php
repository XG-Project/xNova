<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location:../../"));

	function GetBuildingPrice($CurrentUser, $CurrentPlanet, $Element, $Incremental = TRUE, $ForDestroy = FALSE)
	{
		global $pricelist, $resource;

		if ($Incremental)
			$level = ($CurrentPlanet[$resource[$Element]]) ? $CurrentPlanet[$resource[$Element]] : $CurrentUser[$resource[$Element]];

		$array = array('metal', 'crystal', 'deuterium', 'energy_max');
		foreach ($array as $ResType)
		{
			if ($Incremental)
				$cost[$ResType] = floor($pricelist[$Element][$ResType] * pow($pricelist[$Element]['factor'], $level));
			else
				$cost[$ResType] = floor($pricelist[$Element][$ResType]);

			if ($ForDestroy)
			{
				$cost[$ResType]  = floor($cost[$ResType]) / 2;
				$cost[$ResType] /= 2;
			}
		}

		return $cost;
	}
?>