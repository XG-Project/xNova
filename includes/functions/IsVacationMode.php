<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

function IsVacationMode ( $CurrentUser )
{
	if ( $CurrentUser['urlaubs_modus'] == 1 )
	{
		doquery("UPDATE {{table}} SET
		metal_perhour = '".intval(read_config ( 'metal_basic_income' ))."',
		crystal_perhour = '".intval(read_config ( 'crystal_basic_income' ))."',
		deuterium_perhour = '".intval(read_config ( 'deuterium_basic_income' ))."',
		metal_mine_porcent = '0',
		crystal_mine_porcent = '0',
		deuterium_sintetizer_porcent = '0',
		solar_plant_porcent = '0',
		fusion_plant_porcent = '0',
		solar_satelit_porcent = '0'
		WHERE id_owner = '".intval($CurrentUser['id'])."' AND `planet_type` = '1' ", 'planets');
		return TRUE;
	}
	return FALSE;
}
?>