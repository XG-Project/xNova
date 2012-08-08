<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location:../../"));

function UpdatePlanetBatimentQueueList (&$CurrentPlanet, &$CurrentUser) {

	$RetValue = FALSE;
	if ($CurrentPlanet['b_building_id'])
	{
		while ($CurrentPlanet['b_building_id'])
		{
			if ($CurrentPlanet['b_building'] <= time())
			{
				PlanetResourceUpdate($CurrentUser, $CurrentPlanet, $CurrentPlanet['b_building'], FALSE);
				$IsDone = CheckPlanetBuildingQueue($CurrentPlanet, $CurrentUser);
				if ($IsDone)
					SetNextQueueElementOnTop ($CurrentPlanet, $CurrentUser);
			}
			else
			{
				$RetValue = TRUE;
				break;
			}
		}
	}
	return $RetValue;
}

?>