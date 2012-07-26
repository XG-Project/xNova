<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

define('INSIDE', TRUE);
define('INSTALL', FALSE);
define('IN_ADMIN', TRUE);
define('XN_ROOT', realpath('./').'/');

require_once(XN_ROOT.'global.php');
if ($user['authlevel'] < 1) die(message($lang['404_page']));

require_once(XN_ROOT.'includes/functions/adm/Autorization.php');

$page	= isset($_GET['page']) ? $_GET['page'] : NULL;

switch($page)
{
//====================================================================================================//
	case'query':
		require_once(XN_ROOT.'includes/pages/adm/class.ShowQueriesPage.php');
		new ShowQueriesPage();
	break;
//====================================================================================================//
	case'reset':
		require_once(XN_ROOT.'includes/pages/adm/class.ShowResetPage.php');
		new ShowResetPage();
	break;
//====================================================================================================//
	case'moderate':
		require_once(XN_ROOT.'includes/pages/adm/class.ShowModerationPage.php');
		new ShowModerationPage(isset($_GET['moderation']) ? (int) $_GET['moderation'] : NULL);
	break;
//====================================================================================================//

	default:
		require_once(XN_ROOT.'includes/pages/adm/class.ShowOverviewPage.php');
		new ShowOverviewPage();
//====================================================================================================//
}


/* End of file admin.php */
/* Location: ./admin.php */