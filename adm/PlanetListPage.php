<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);
define('XGP_ROOT', './../');

include(XGP_ROOT . 'global.php');
include('AdminFunctions/Autorization.php');

if ($Observation != 1) die();

	$parse	= $lang;
	$query 	= doquery("SELECT * FROM {{table}} WHERE planet_type='1' ORDER BY `id` ASC", "planets");
	$i 		= 0;

	while ($u = mysql_fetch_array($query))
	{
		$parse['lista_planetas'] .= "<tr>"
		. "<th>" . $u[0] . "</th>"
		. "<th>" . $u[1] . "</th>"
		. "<th>" . $u[4] . "</th>"
		. "<th>" . $u[5] . "</th>"
		. "<th>" . $u[6] . "</th>"
		. "</tr>";
		$i++;
	}

	if ($i == 1)
		$parse['lista_planetas'] .= "<tr><th class=b colspan=5>".$lang['pl_only_one_planet']."</th></tr>";
	else
		$parse['lista_planetas'] .= "<tr><th class=b colspan=5>". $lang['pl_there_are'] . $i . $lang['pl_planets'] ."</th></tr>";

	display(parsetemplate(gettemplate('adm/PlanetListBody'), $parse), FALSE, '', TRUE, FALSE);

?>