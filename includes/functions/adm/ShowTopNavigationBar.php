<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

function ShowTopNavigationBar()
{
	global $lang, $user;
	$parse	=	$lang;

	if (AUTHLEVEL === 3)
		$parse['admin'] = parsetemplate(gettemplate('adm/menu/topnav_admin'), $lang);

	return parsetemplate(gettemplate('adm/menu/topnav'), $parse);
}


/* End of file ShowTopNavigationBar.php */
/* Location: ./includes/functions/adm/ShowTopNavigationBar.php */