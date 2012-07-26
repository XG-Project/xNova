<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);
define('XN_ROOT', './../');

include(XN_ROOT . 'global.php');
include(XN_ROOT . 'includes/classes/class.FlyingFleetsTable.php');
include('AdminFunctions/Autorization.php');

if ($Observation != 1) die();

$parse				= $lang;
$FlyingFleetsTable 	= new FlyingFleetsTable();
$parse['flt_table'] = $FlyingFleetsTable->BuildFlyingFleetTable();

display(parsetemplate(gettemplate('adm/fleet_body'), $parse), FALSE, '', TRUE, FALSE);
?>