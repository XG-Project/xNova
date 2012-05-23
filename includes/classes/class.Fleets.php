<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class Fleets
{
	/**
	 * method ship_consumption
	 * param1 $ship
	 * param2 $user
	 * return the ship consumption
	 */
	public static function ship_consumption ( $ship, $user )
	{
		global $pricelist;
	
		if ( $user['impulse_motor_tech'] >= 5 )
		{
			$consumption	= $pricelist[$ship]['consumption2'];
		}	
		else
		{
			$consumption	= $pricelist[$ship]['consumption'];
		}
			
	
		return $consumption;
	}
	
	/**
	 * method target_distance
	 * param1 $orig_galaxy
	 * param2 $dest_galaxy
	 * param3 $orig_system
	 * param4 $dest_system	
	 * param5 $orig_planet
	 * param6 $dest_planet
	 * return the distance to the target
	 */
	public static function target_distance ( $orig_galaxy , $dest_galaxy , $orig_system , $dest_system , $orig_planet , $dest_planet )
	{
		$distance = 0;
	
		if ( ( $orig_galaxy - $dest_galaxy ) != 0 )
		{
			$distance = abs ( $orig_galaxy - $dest_galaxy ) * 20000;
		}
		elseif ( ( $orig_system - $dest_system ) != 0)
		{
			$distance = abs ( $orig_system - $dest_system ) * 5 * 19 + 2700;
		}	
		elseif ( ( $orig_planet - $dest_planet ) != 0)
		{
			$distance = abs ( $orig_planet - $dest_planet ) * 5 + 1000;
		}	
		else
		{
			$distance = 5;
		}
			
		return $distance;
	}
	
	/**
	 * method mission_duration
	 * param1 $game_speed
	 * param2 $max_fleet_speed
	 * param3 $distance
	 * param4 $speed_factor	
	 * return the mission duration
	 */
	public static function mission_duration ( $game_speed , $max_fleet_speed , $distance , $speed_factor )
	{
		$duration = 0;
		$duration = round ( ( ( 35000 / $game_speed * sqrt ( $distance * 10 / $max_fleet_speed ) + 10 ) / $speed_factor ) );
		return $duration;
	}
	
	/**
	 * method fleet_max_speed
	 * param1 $fleet_array
	 * param2 $fleet
	 * param3 $user
	 * return the fleet maximum speed
	 */
	public static function fleet_max_speed ( $fleet_array , $fleet , $user )
	{
		global $reslist, $pricelist;
	
		if ( $fleet != 0 )
		{
			$fleet_array[$fleet] =  1;
		}	
			
		foreach ( $fleet_array as $ship => $count )
		{
			if ( $ship == 202 )
			{
				if ( $user['impulse_motor_tech'] >= 5 )
				{
					$speed_all[$ship]	= $pricelist[$ship]['speed2'] + ( ( $pricelist[$ship]['speed'] * $user['impulse_motor_tech'] ) * 0.2 );
				} 	
				else
				{
					$speed_all[$ship]	= $pricelist[$ship]['speed']  + ( ( $pricelist[$ship]['speed'] * $user['combustion_tech'] ) * 0.1 );
				}
					
			}
			
			if ( $ship == 203 or $ship == 204 or $ship == 209 or $ship == 210 )
			{
				$speed_all[$ship] 		= $pricelist[$ship]['speed'] + ( ( $pricelist[$ship]['speed'] * $user['combustion_tech'] ) * 0.1 );
			}
				
	
			if ( $ship == 205 or $ship == 206 or $ship == 208 )
			{
				$speed_all[$ship] 		= $pricelist[$ship]['speed'] + ( ( $pricelist[$ship]['speed'] * $user['impulse_motor_tech'] ) * 0.2 );
			}
	
			if ( $ship == 211 )
			{
				if ( $user['hyperspace_motor_tech'] >= 8 )
				{
					$speed_all[$ship] 	= $pricelist[$ship]['speed2'] + ( ( $pricelist[$ship]['speed'] * $user['hyperspace_motor_tech'] ) * 0.3 );

				}
				else
				{
					$speed_all[$ship] 	= $pricelist[$ship]['speed2'] + ( ( $pricelist[$ship]['speed'] * $user['hyperspace_motor_tech'] ) * 0.3 );
				}
 			}
	
			if ( $ship == 207 or $ship == 213 or $ship == 214 or $ship == 215 )
			{
				$speed_all[$ship] 		= $pricelist[$ship]['speed'] + ( ( $pricelist[$ship]['speed'] * $user['hyperspace_motor_tech'] ) * 0.3 );
			}
		}
	
		if ( $fleet != 0 )
		{
			$ship_speed	= $speed_all[$ship];
			$speed_all	= $ship_speed;
		}
	
		return $speed_all;
	}
	
	/**
	 * method fleet_consumption
	 * param1 $fleet_array
	 * param2 $speed_factor
	 * param3 $mission_duration
	 * param4 $mission_distance	
	 * param5 $fleet_max_speed
	 * param6 $user
	 * return the final fleet consumption based on all the data obtained
	 */
	public static function fleet_consumption ( $fleet_array , $speed_factor , $mission_duration , $mission_distance , $fleet_max_speed , $user )
	{
		$consumption 		= 0;
		$basic_consumption 	= 0;
	
		foreach ( $fleet_array as $ship => $count )
		{
			if ( $ship > 0 )
			{
				$ship_speed         = self::fleet_max_speed ( "" , $ship , $user );
				$ship_consumption   = self::ship_consumption ( $ship , $user );
				$spd              	= 35000 / ( $mission_duration * $speed_factor - 10 ) * sqrt ( $mission_distance * 10 / $ship_speed );
				$basic_consumption	= $ship_consumption * $count;
				$consumption       += $basic_consumption * $mission_distance / 35000 * ( ( $spd / 10 ) + 1 ) * ( ( $spd / 10 ) + 1 );
			}
		}
	
		return ( round ( $consumption ) + 1 );
	}
	
	/**
	 * method get_max_fleets
	 * param1 $computer_tech
	 * param2 $amiral_level
	 * return the max quantity of fleets that an user can send
	 */
	public static function get_max_fleets ( $computer_tech , $amiral_level )
	{
		return ( 1 + $computer_tech + ( $amiral_level * AMIRAL ) );
	}
}

?>