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

$parse 			= $lang;
$ActivePlanets 	= doquery("SELECT `name`,`galaxy`,`system`,`planet`,`points`,`last_update` FROM `{{table}}` WHERE `last_update` >= '".(time()-15 * 60)."' ORDER BY `id` ASC", 'planets');
$Count          = 0;

while ($ActivPlanet = $ActivePlanets->fetch_array())
{
	$parse['online_list'] .= "<tr>";
	$parse['online_list'] .= "<th><center><b>".$ActivPlanet['name']."</b></center></th>";
	$parse['online_list'] .= "<th><center><b>[".$ActivPlanet['galaxy'].":".$ActivPlanet['system'].":".$ActivPlanet['planet']."]</b></center></th>";
	$parse['online_list'] .= "<th><center><b>".Format::pretty_number($ActivPlanet['points'] / 1000)."</b></center></th>";
	$parse['online_list'] .= "<th><center><b>".Format::pretty_time(time() - $ActivPlanet['last_update'])."</b></center></th>";
	$parse['online_list'] .= "</tr>";
	$Count++;
}

$parse['online_list'] .= "<tr>";
$parse['online_list'] .= "<th colspan=\"4\">".$lang['ap_there_are'].$Count.$lang['ap_with_activity']."</th>";
$parse['online_list'] .= "</tr>";

display(parsetemplate(gettemplate('adm/ActivePlanetsBody')	, $parse), FALSE, '', TRUE, FALSE);

?>