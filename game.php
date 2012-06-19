<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('XGP_ROOT',	'./');

include(XGP_ROOT . 'global.php');

include(XGP_ROOT . 'includes/functions/CheckPlanetBuildingQueue.php');
include(XGP_ROOT . 'includes/functions/GetBuildingPrice.php');
include(XGP_ROOT . 'includes/functions/IsElementBuyable.php');
include(XGP_ROOT . 'includes/functions/SetNextQueueElementOnTop.php');
include(XGP_ROOT . 'includes/functions/SortUserPlanets.php');
include(XGP_ROOT . 'includes/functions/UpdatePlanetBatimentQueueList.php');

switch($_GET[page])
{
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'changelog':
		include_once(XGP_ROOT . 'includes/pages/class.ShowChangelogPage.php');
		new ShowChangelogPage();
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'overview':
		include_once(XGP_ROOT . 'includes/pages/class.ShowOverviewPage.php');
		new ShowOverviewPage ( $user , $planetrow );
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'galaxy':
		include_once(XGP_ROOT . 'includes/pages/class.ShowGalaxyPage.php');
		new ShowGalaxyPage ( $user , $planetrow );
	break;
	case'phalanx':
		include_once(XGP_ROOT . 'includes/pages/class.ShowPhalanxPage.php');
		new ShowPhalanxPage ( $user , $planetrow );
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'imperium':
		include_once(XGP_ROOT . 'includes/pages/class.ShowImperiumPage.php');
		new ShowImperiumPage ( $user );
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'fleet':
		include_once(XGP_ROOT . 'includes/pages/class.ShowFleetPage.php');
		new ShowFleetPage ( $user , $planetrow );
	break;
	case'fleet1':
		include_once(XGP_ROOT . 'includes/pages/class.ShowFleet1Page.php');
		new ShowFleet1Page ( $user , $planetrow );
	break;
	case'fleet2':
		include_once(XGP_ROOT . 'includes/pages/class.ShowFleet2Page.php');
		new ShowFleet2Page ( $user , $planetrow );
	break;
	case'fleet3':
		include_once(XGP_ROOT . 'includes/pages/class.ShowFleet3Page.php');
		new ShowFleet3Page ( $user , $planetrow );
	break;
	case'fleetACS':
		include_once(XGP_ROOT . 'includes/pages/class.ShowFleetACSPage.php');
		new ShowFleetACSPage ( $user , $planetrow );
	break;
	case'shortcuts':
		include_once(XGP_ROOT . 'includes/pages/class.ShowFleetShortcuts.php');
		new ShowFleetShortcuts ( $user );
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'buildings':
		include_once(XGP_ROOT . 'includes/functions/HandleTechnologieBuild.php');
		UpdatePlanetBatimentQueueList ($planetrow, $user);
		$IsWorking = HandleTechnologieBuild($planetrow, $user);
		switch ($_GET['mode'])
		{
			case 'research':
				include_once(XGP_ROOT . 'includes/pages/class.ShowResearchPage.php');
				new ShowResearchPage($planetrow, $user, $IsWorking['OnWork'], $IsWorking['WorkOn']);
			break;
			case 'fleet':
				include_once(XGP_ROOT . 'includes/pages/class.ShowShipyardPage.php');
				$FleetBuildingPage = new ShowShipyardPage();
				$FleetBuildingPage->FleetBuildingPage ($planetrow, $user);
			break;
			case 'defense':
				include_once(XGP_ROOT . 'includes/pages/class.ShowShipyardPage.php');
				$DefensesBuildingPage = new ShowShipyardPage();
				$DefensesBuildingPage->DefensesBuildingPage ($planetrow, $user);
			break;
			default:
				include_once(XGP_ROOT . 'includes/pages/class.ShowBuildingsPage.php');
				new ShowBuildingsPage($planetrow, $user);
			break;
		}
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'resources':
		include_once(XGP_ROOT . 'includes/pages/class.ShowResourcesPage.php');
		new ShowResourcesPage($user, $planetrow);
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'officier':
		include_once(XGP_ROOT . 'includes/pages/class.ShowOfficierPage.php');
		new ShowOfficierPage($user);
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'trader':
		include_once(XGP_ROOT . 'includes/pages/class.ShowTraderPage.php');
		new ShowTraderPage ( $user , $planetrow );
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'techtree':
		include_once(XGP_ROOT . 'includes/pages/class.ShowTechTreePage.php');
		new ShowTechTreePage($user, $planetrow);
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'infos':
		include_once(XGP_ROOT . 'includes/pages/class.ShowInfosPage.php');
		new ShowInfosPage($user, $planetrow, $_GET['gid']);
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'messages':
		include_once(XGP_ROOT . 'includes/pages/class.ShowMessagesPage.php');
		new ShowMessagesPage($user);
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'alliance':
		include_once(XGP_ROOT . 'includes/pages/class.ShowAlliancePage.php');
		new ShowAlliancePage($user);
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'buddy':
		include_once(XGP_ROOT . 'includes/pages/class.ShowBuddyPage.php');
		new ShowBuddyPage($user);
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'notes':
		include_once(XGP_ROOT . 'includes/pages/class.ShowNotesPage.php');
		new ShowNotesPage($user);
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'statistics':
		include_once(XGP_ROOT . 'includes/pages/class.ShowStatisticsPage.php');
		new ShowStatisticsPage($user);
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'search':
		include_once(XGP_ROOT . 'includes/pages/class.ShowSearchPage.php');
		new ShowSearchPage();
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'options':
		include_once(XGP_ROOT . 'includes/pages/class.ShowOptionsPage.php');
		new ShowOptionsPage($user);
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'banned':
		include_once(XGP_ROOT . 'includes/pages/class.ShowBannedPage.php');
		new ShowBannedPage();
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	case'logout':
		setcookie(read_config ( 'cookie_name' ), "", time()-100000, "/", "", 0);
		message($lang['see_you_soon'], XGP_ROOT, 1, FALSE, FALSE);
	break;
// ----------------------------------------------------------------------------------------------------------------------------------------------//
	default:
		die(message($lang['page_doesnt_exist']));
// ----------------------------------------------------------------------------------------------------------------------------------------------//
}
?>