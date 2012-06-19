<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

	function IsElementBuyable ($CurrentUser, $CurrentPlanet, $Element, $Incremental = TRUE, $ForDestroy = FALSE)
	{
		global $pricelist, $resource;

		include_once(XGP_ROOT . 'includes/functions/IsVacationMode.php');

	    if (IsVacationMode($CurrentUser))
	       return FALSE;

		if ($Incremental)
			$level  = ($CurrentPlanet[$resource[$Element]]) ? $CurrentPlanet[$resource[$Element]] : $CurrentUser[$resource[$Element]];

		$RetValue = TRUE;
		$array    = array('metal', 'crystal', 'deuterium', 'energy_max');

		foreach ($array as $ResType)
		{
			if ($pricelist[$Element][$ResType] != 0)
			{
				if ($Incremental)
					$cost[$ResType]  = floor($pricelist[$Element][$ResType] * pow($pricelist[$Element]['factor'], $level));
				else
					$cost[$ResType]  = floor($pricelist[$Element][$ResType]);

				if ($ForDestroy)
					$cost[$ResType]  = floor($cost[$ResType] / 4);

				if ($cost[$ResType] > $CurrentPlanet[$ResType])
					$RetValue = FALSE;
			}
		}
		return $RetValue;
	}

?>