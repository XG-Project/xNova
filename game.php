<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com
 * @author	Razican <admin@razican.com>
 */

define('INSIDE', TRUE);
define('INSTALL', FALSE);
define('XN_ROOT', realpath('./').'/');

require(XN_ROOT.'global.php');

require_once(XN_ROOT.'includes/functions/CheckPlanetBuildingQueue.php');
require_once(XN_ROOT.'includes/functions/GetBuildingPrice.php');
require_once(XN_ROOT.'includes/functions/IsElementBuyable.php');
require_once(XN_ROOT.'includes/functions/SetNextQueueElementOnTop.php');
require_once(XN_ROOT.'includes/functions/SortUserPlanets.php');
require_once(XN_ROOT.'includes/functions/UpdatePlanetBatimentQueueList.php');

$page		= isset($_GET['page']) ? $_GET['page'] : NULL;
$planetrow	= isset($planetrow) ? $planetrow : NULL;

switch ($page)
{
//====================================================================================================//
	case'changelog':
		require_once(XN_ROOT.'includes/pages/class.ShowChangelogPage.php');
		new ShowChangelogPage();
	break;
//====================================================================================================//
	case'overview':
		require_once(XN_ROOT.'includes/pages/class.ShowOverviewPage.php');
		new ShowOverviewPage($user, $planetrow);
	break;
//====================================================================================================//
	case'galaxy':
		require_once(XN_ROOT.'includes/pages/class.ShowGalaxyPage.php');
		new ShowGalaxyPage($user, $planetrow);
	break;
	case'phalanx':
		require_once(XN_ROOT.'includes/pages/class.ShowPhalanxPage.php');
		new ShowPhalanxPage($user, $planetrow);
	break;
//====================================================================================================//
	case'imperium':
		require_once(XN_ROOT.'includes/pages/class.ShowImperiumPage.php');
		new ShowImperiumPage($user);
	break;
//====================================================================================================//
	case'fleet':
		require_once(XN_ROOT.'includes/pages/class.ShowFleetPage.php');
		new ShowFleetPage($user, $planetrow);
	break;
	case'fleet1':
		require_once(XN_ROOT.'includes/pages/class.ShowFleet1Page.php');
		new ShowFleet1Page($user, $planetrow);
	break;
	case'fleet2':
		require_once(XN_ROOT.'includes/pages/class.ShowFleet2Page.php');
		new ShowFleet2Page($user, $planetrow);
	break;
	case'fleet3':
		require_once(XN_ROOT.'includes/pages/class.ShowFleet3Page.php');
		new ShowFleet3Page($user, $planetrow);
	break;
	case'fleetACS':
		require_once(XN_ROOT.'includes/pages/class.ShowFleetACSPage.php');
		new ShowFleetACSPage($user, $planetrow);
	break;
	case'shortcuts':
		require_once(XN_ROOT.'includes/pages/class.ShowFleetShortcuts.php');
		new ShowFleetShortcuts($user);
	break;
//====================================================================================================//
	case'buildings':
		require_once(XN_ROOT.'includes/functions/HandleTechnologieBuild.php');
		UpdatePlanetBatimentQueueList($planetrow, $user);
		$IsWorking = HandleTechnologieBuild($planetrow, $user);
		$mode	= isset($_GET['mode']) ? $_GET['mode'] : NULL;

		switch ($mode)
		{
			case 'research':
				require_once(XN_ROOT.'includes/pages/class.ShowResearchPage.php');
				new ShowResearchPage($planetrow, $user, $IsWorking['OnWork'], $IsWorking['WorkOn']);
			break;
			case 'fleet':
				require_once(XN_ROOT.'includes/pages/class.ShowShipyardPage.php');
				$FleetBuildingPage = new ShowShipyardPage();
				$FleetBuildingPage->FleetBuildingPage($planetrow, $user);
			break;
			case 'defense':
				require_once(XN_ROOT.'includes/pages/class.ShowShipyardPage.php');
				$DefensesBuildingPage = new ShowShipyardPage();
				$DefensesBuildingPage->DefensesBuildingPage($planetrow, $user);
			break;
			default:
				require_once(XN_ROOT.'includes/pages/class.ShowBuildingsPage.php');
				new ShowBuildingsPage($planetrow, $user);
			break;
		}
	break;
//====================================================================================================//
	case'resources':
		require_once(XN_ROOT.'includes/pages/class.ShowResourcesPage.php');
		new ShowResourcesPage($user, $planetrow);
	break;
//====================================================================================================//
	case'officer':
		require_once(XN_ROOT.'includes/pages/class.ShowOfficerPage.php');
		new ShowOfficerPage($user);
	break;
//====================================================================================================//
	case'trader':
		require_once(XN_ROOT.'includes/pages/class.ShowTraderPage.php');
		new ShowTraderPage($user, $planetrow);
	break;
//====================================================================================================//
	case'techtree':
		require_once(XN_ROOT.'includes/pages/class.ShowTechTreePage.php');
		new ShowTechTreePage($user, $planetrow);
	break;
//====================================================================================================//
	case'infos':
		require_once(XN_ROOT.'includes/pages/class.ShowInfosPage.php');
		new ShowInfosPage($user, $planetrow, isset($_GET['gid']) ? (int) $_GET['gid'] : NULL);
	break;
//====================================================================================================//
	case'messages':
		require_once(XN_ROOT.'includes/pages/class.ShowMessagesPage.php');
		new ShowMessagesPage($user);
	break;
//====================================================================================================//
	case'alliance':
		require_once(XN_ROOT.'includes/pages/class.ShowAlliancePage.php');
		new ShowAlliancePage($user);
	break;
//====================================================================================================//
	case'buddy':
		require_once(XN_ROOT.'includes/pages/class.ShowBuddyPage.php');
		new ShowBuddyPage($user);
	break;
//====================================================================================================//
	case'notes':
		require_once(XN_ROOT.'includes/pages/class.ShowNotesPage.php');
		new ShowNotesPage($user);
	break;
//====================================================================================================//
	case'statistics':
		require_once(XN_ROOT.'includes/pages/class.ShowStatisticsPage.php');
		new ShowStatisticsPage($user);
	break;
//====================================================================================================//
	case'search':
		require_once(XN_ROOT.'includes/pages/class.ShowSearchPage.php');
		new ShowSearchPage();
	break;
//====================================================================================================//
	case'options':
		require_once(XN_ROOT.'includes/pages/class.ShowOptionsPage.php');
		new ShowOptionsPage($user);
	break;
//====================================================================================================//
	case'banned':
		require_once(XN_ROOT.'includes/pages/class.ShowBannedPage.php');
		new ShowBannedPage();
	break;
//====================================================================================================//
	case'logout':
		setcookie(read_config('cookie_name'), "", time()-100000, "/", "", FALSE, TRUE);
		message($lang['see_you_soon'], GAMEURL, 1, FALSE, FALSE);
	break;
//====================================================================================================//
	default:
		die(message($lang['page_doesnt_exist']));
//====================================================================================================//
}


/* End of file game.php */
/* Location: ./game.php */