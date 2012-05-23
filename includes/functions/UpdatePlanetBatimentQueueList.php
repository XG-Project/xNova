<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

function UpdatePlanetBatimentQueueList ( &$CurrentPlanet, &$CurrentUser ) {

	$RetValue = FALSE;
	if ( $CurrentPlanet['b_building_id'] != 0 )
	{
		while ( $CurrentPlanet['b_building_id'] != 0 )
		{
			if ( $CurrentPlanet['b_building'] <= time() )
			{
				PlanetResourceUpdate ( $CurrentUser, $CurrentPlanet, $CurrentPlanet['b_building'], FALSE );
				$IsDone = CheckPlanetBuildingQueue( $CurrentPlanet, $CurrentUser );
				if ( $IsDone == TRUE )
					SetNextQueueElementOnTop ( $CurrentPlanet, $CurrentUser );
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