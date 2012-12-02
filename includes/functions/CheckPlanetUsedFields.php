<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location:../../"));

	function CheckPlanetUsedFields(&$planet)
	{
		global $resource;

		$cfc  = $planet[$resource[1]]  + $planet[$resource[2]]  + $planet[$resource[3]] ;
		$cfc += $planet[$resource[4]]  + $planet[$resource[12]] + $planet[$resource[14]];
		$cfc += $planet[$resource[15]] + $planet[$resource[21]] + $planet[$resource[22]];
		$cfc += $planet[$resource[23]] + $planet[$resource[24]] + $planet[$resource[31]];
		$cfc += $planet[$resource[33]] + $planet[$resource[34]] + $planet[$resource[44]];

		if ($planet['planet_type'] == '3')
		{
			$cfc += $planet[$resource[41]] + $planet[$resource[42]] + $planet[$resource[43]];
		}

		if ($planet['field_current'] != $cfc)
		{
			$planet['field_current'] = $cfc;
			doquery("UPDATE `{{table}}` SET `field_current`= ".$cfc." WHERE `id` = ".$planet['id']."", 'planets');
		}
	}

?>