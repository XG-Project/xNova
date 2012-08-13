<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

define('INSIDE' , TRUE);
define('INSTALL', FALSE);
define('IN_ADMIN', TRUE);
define('XN_ROOT', './../');

include(XN_ROOT.'global.php');
include('AdminFunctions/Autorization.php');

if ($Observation != 1) die();

	$parse	= $lang;
	$query 	= doquery("SELECT * FROM `{{table}}` WHERE planet_type='1' ORDER BY `id` ASC", "planets");
	$i 		= 0;

	while ($u = $query->fetch_array())
	{
		$parse['lista_planetas'] .= "<tr>"
		."<th>".$u[0]."</th>"
		."<th>".$u[1]."</th>"
		."<th>".$u[4]."</th>"
		."<th>".$u[5]."</th>"
		."<th>".$u[6]."</th>"
		."</tr>";
		$i++;
	}

	if ($i == 1)
		$parse['lista_planetas'] .= "<tr><th class=b colspan=5>".$lang['pl_only_one_planet']."</th></tr>";
	else
		$parse['lista_planetas'] .= "<tr><th class=b colspan=5>".$lang['pl_there_are'].$i.$lang['pl_planets']."</th></tr>";

	display(parsetemplate(gettemplate('adm/PlanetListBody'), $parse), FALSE, '', TRUE, FALSE);

?>