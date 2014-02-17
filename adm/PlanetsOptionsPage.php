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

if ($EditUsers != 1) die();

$parse	=	$lang;


$mode      = $_POST['mode'];

if ($mode == 'agregar')
{
   	$id            = $_POST['id'];
    $galaxy        = $_POST['galaxy'];
    $system        = $_POST['system'];
    $planet        = $_POST['planet'];

	$i	=	0;
	$QueryS	=	doquery("SELECT * FROM {{table}} WHERE `galaxy` = '".$galaxy."' AND `system` = '".$system."' AND `planet` = '".$planet."'", "galaxy", TRUE);
	$QueryS2	=	doquery("SELECT * FROM {{table}} WHERE `id` = '".$id."'", "users", TRUE);
	if (is_numeric($_POST['id']) && isset($_POST['id']) && !$QueryS && $QueryS2)
	{
    	if ($galaxy < 1 or $system < 1 or $planet < 1 or !is_numeric($galaxy) or !is_numeric($system) or !is_numeric($planet)){
    		$Error	.=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_all'].'</font></th></tr>';
			$i++;}


		if ($galaxy > MAX_GALAXY_IN_WORLD or $system > MAX_SYSTEM_IN_GALAXY or $planet > MAX_PLANET_IN_SYSTEM){
			$Error	.=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_all2'].'</font></th></tr>';
			$i++;}

		if ($i	==	0)
		{
			CreateOnePlanetRecord ($galaxy, $system, $planet, $id, '', '', FALSE) ;
			$QueryS3	=	doquery("SELECT * FROM {{table}} WHERE `id_owner` = '".$id."'", "planets", TRUE);
			doquery("UPDATE {{table}} SET `id_level` = '".$QueryS3['id_level']."' WHERE
			`galaxy` = '".$galaxy."' AND `system` = '".$system."' AND `planet` = '".$planet."' AND `planet_type` = '1'", "planets");
    		$parse['display']	=	'<tr><th colspan="2"><font color=lime>'.$lang['po_complete_succes'].'</font></th></tr>';
		}
		else
		{
			$parse['display']	=	$Error;
		}
	}
	else
	{
		$parse['display']	=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_all'].'</font></th></tr>';
	}
}
elseif ($mode == 'borrar')
{
	$id	=	$_POST['id'];
	if (is_numeric($id) && isset($id))
	{
		$QueryS	=	doquery("SELECT * FROM {{table}} WHERE `id` = '".$id."'", "planets", TRUE);

		if ($QueryS)
		{
			if ($QueryS['planet_type'] == '1')
			{
				$QueryS2	=	doquery("SELECT * FROM {{table}} WHERE `id_planet` = '".$id."'", "galaxy", TRUE);
				if ($QueryS2['id_luna'] > 0)
				{
					doquery("DELETE FROM {{table}} WHERE `galaxy` = '".$QueryS['galaxy']."' AND `system` = '".$QueryS['system']."' AND
						`planet` = '".$QueryS['planet']."' AND `planet_type` = '3'", "planets");
				}
				doquery("DELETE FROM {{table}} WHERE `id` = '".$id."'", 'planets');
    			doquery("DELETE FROM {{table}} WHERE `id_planet` ='".$id."'", 'galaxy');
				$Error	.=	'<tr><th colspan="2"><font color=lime>'.$lang['po_complete_succes2'].'</font></th></tr>';
			}
			else
			{
				$Error	.=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_invalid3'].'</font></th></tr>';
			}
		}
		else
		{
			$Error	.=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_invalid2'].'</font></th></tr>';
		}
	}
	else
	{
		$Error	.=	'<tr><th colspan="2"><font color=red>'.$lang['po_complete_invalid'].'</font></th></tr>';
	}

	$parse['display2']	=	$Error;
}


display (parsetemplate(gettemplate('adm/PlanetOptionsBody'),  $parse), FALSE, '', TRUE, FALSE);
?>