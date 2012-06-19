<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

	function DeleteSelectedUser($UserID)
	{
		$TheUser = doquery ( "SELECT * FROM {{table}} WHERE `id` = '" . $UserID . "';", 'users', TRUE );

		if ( $TheUser['ally_id'] != 0 )
		{
			$TheAlly = doquery ( "SELECT * FROM {{table}} WHERE `id` = '" . $TheUser['ally_id'] . "';", 'alliance', TRUE );
			$TheAlly['ally_members'] -= 1;

			if ($TheAlly['ally_members'] > 0)
			{
				doquery ( "UPDATE {{table}} SET `ally_members` = '" . $TheAlly['ally_members'] . "' WHERE `id` = '" . $TheAlly['id'] . "';", 'alliance' );
			}
			else
			{
				doquery ( "DELETE FROM {{table}} WHERE `id` = '" . $TheAlly['id'] . "';", 'alliance' );
				doquery ( "DELETE FROM {{table}} WHERE `stat_type` = '2' AND `id_owner` = '" . $TheAlly['id'] . "';", 'statpoints' );
			}
		}

		doquery ( "DELETE FROM {{table}} WHERE `stat_type` = '1' AND `id_owner` = '" . $UserID . "';", 'statpoints' );

		$ThePlanets = doquery ( "SELECT * FROM {{table}} WHERE `id_owner` = '" . $UserID . "';", 'planets' );

		while ( $OnePlanet = mysql_fetch_assoc ( $ThePlanets ) )
		{
			if ( $OnePlanet['planet_type'] == 1 )
				doquery ( "DELETE FROM {{table}} WHERE `galaxy` = '" . $OnePlanet['galaxy'] . "' AND `system` = '" . $OnePlanet['system'] . "' AND `planet` = '" . $OnePlanet['planet'] . "';", 'galaxy' );
			elseif ( $OnePlanet['planet_type'] == 3 )
			doquery ( "DELETE FROM {{table}} WHERE `id` = '" . $OnePlanet['id'] . "';", 'planets' );
		}

		doquery ( "DELETE FROM {{table}} WHERE `id_owner` = '" . $UserID . "';", 'planets' );
		doquery ( "DELETE FROM {{table}} WHERE `message_sender` = '" . $UserID . "';", 'messages' );
		doquery ( "DELETE FROM {{table}} WHERE `message_owner` = '" . $UserID . "';", 'messages' );
		doquery ( "DELETE FROM {{table}} WHERE `owner` = '" . $UserID . "';", 'notes' );
		doquery ( "DELETE FROM {{table}} WHERE `fleet_owner` = '" . $UserID . "';", 'fleets' );
		doquery ( "DELETE FROM {{table}} WHERE `sender` = '" . $UserID . "';", 'buddy' );
		doquery ( "DELETE FROM {{table}} WHERE `owner` = '" . $UserID . "';", 'buddy' );
		doquery ( "DELETE FROM {{table}} WHERE `id` = '" . $UserID . "';", 'users' );

		update_config ( 'users_amount' , read_config ( 'users_amount' ) - 1 );
	}

	function DeleteSelectedPlanet ($ID)
	{
		global $lang;

		$QueryPlanet = doquery ("SELECT galaxy,planet,system,planet_type FROM {{table}} WHERE id = '".$ID."'", 'planets', TRUE );

		if ($QueryPlanet['planet_type'] == '3')
		{
			doquery("DELETE FROM {{table}} WHERE id = '".$ID."'", "planets");
			doquery("UPDATE {{table}} SET id_luna = 0 WHERE id_luna = '".$ID."'", "galaxy");
		}
		else
		{
			doquery("DELETE FROM {{table}} WHERE galaxy = '".$QueryPlanet['galaxy']."' AND system = '".$QueryPlanet['system']."' AND planet = '".$QueryPlanet['planet']."'", "planets");
			doquery("DELETE FROM {{table}} WHERE id_planet = '".$ID."'", "galaxy");
		}
	}
?>