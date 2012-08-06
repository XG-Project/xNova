<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowResourcesPage
{
	function __construct ( $CurrentUser , $CurrentPlanet )
	{
		global $lang, $ProdGrid, $resource, $reslist;

		$parse 							= 	$lang;
		$game_metal_basic_income		=	read_config ( 'metal_basic_income' );
		$game_crystal_basic_income		=	read_config ( 'crystal_basic_income' );
		$game_deuterium_basic_income	=	read_config ( 'deuterium_basic_income' );
		$game_energy_basic_income		=	read_config ( 'energy_basic_income' );
		$game_resource_multiplier		=	read_config ( 'resource_multiplier' );

		if ($CurrentPlanet['planet_type'] == 3)
		{
			$game_metal_basic_income    	= 0;
			$game_crystal_basic_income   	= 0;
			$game_deuterium_basic_income 	= 0;
		}

		$CurrentPlanet['metal_max']			 = Production::max_storable ( $CurrentPlanet[ $resource[22] ]);
		$CurrentPlanet['crystal_max']		 = Production::max_storable ( $CurrentPlanet[ $resource[23] ]);
		$CurrentPlanet['deuterium_max']	     = Production::max_storable ( $CurrentPlanet[ $resource[24] ]);

		$parse['production_level'] 			 = 100;
		$post_porcent 						 = Production::max_production ( $CurrentPlanet['energy_max'] , $CurrentPlanet['energy_used'] );
		
		
		$parse['resource_row']               = "";
		$CurrentPlanet['metal_perhour']      = 0;
		$CurrentPlanet['crystal_perhour']    = 0;
		$CurrentPlanet['deuterium_perhour']  = 0;
		$CurrentPlanet['energy_max']         = 0;
		$CurrentPlanet['energy_used']        = 0;
		$BuildTemp                           = $CurrentPlanet[ 'temp_max' ];
		$ResourcesRowTPL					 = gettemplate('resources/resources_row');
		foreach($reslist['prod'] as $ProdID)
		{
			if ($CurrentPlanet[$resource[$ProdID]] > 0 && isset($ProdGrid[$ProdID]))
			{
				$BuildLevelFactor	= $CurrentPlanet[ $resource[$ProdID]."_porcent" ];
				$BuildLevel			= $CurrentPlanet[ $resource[$ProdID] ];
				$EnergyLevel        = $CurrentUser["energy_tech"];
				
				// BOOST
				$geologe_boost		= 1 + ( $CurrentUser['rpg_geologue']  * GEOLOGUE );
				$engineer_boost		= 1 + ( $CurrentUser['rpg_ingenieur'] * ENGINEER_ENERGY );
				
				// PRODUCTION FORMULAS
				$metal_prod			= eval ( $ProdGrid[$ProdID]['formule']['metal'] );
				$crystal_prod		= eval ( $ProdGrid[$ProdID]['formule']['crystal'] );
				$deuterium_prod		= eval ( $ProdGrid[$ProdID]['formule']['deuterium'] );
				$energy_prod		= eval ( $ProdGrid[$ProdID]['formule']['energy'] );
				
				// PRODUCTION
				$metal				= Production::production_amount ( $metal_prod , $geologe_boost );
				$crystal			= Production::production_amount ( $crystal_prod , $geologe_boost );
				$deuterium			= Production::production_amount ( $deuterium_prod , $geologe_boost );
				
				if ( $ProdID >= 4 )
				{							
					$energy			= Production::production_amount ( $energy_prod , $engineer_boost );
				}
				else 
				{
					$energy			= Production::production_amount ( $energy_prod , 1 );
				}
				
				if ( $energy > 0 )
				{
					$CurrentPlanet['energy_max']    += $energy;
				}
				else
				{
					$CurrentPlanet['energy_used']   += $energy;
				}

				$CurrentPlanet['metal_perhour']     += $metal;
				$CurrentPlanet['crystal_perhour']   += $crystal;
				$CurrentPlanet['deuterium_perhour'] += $deuterium;

				$metal                               = Production::current_production ( $metal , $post_porcent );
				$crystal                             = Production::current_production ( $crystal , $post_porcent );
				$deuterium                           = Production::current_production ( $deuterium , $post_porcent );
				$energy                              = Production::current_production ( $energy , $post_porcent );
				$Field                               = $resource[$ProdID] ."_porcent";
				$CurrRow                             = array();
				$CurrRow['name']                     = $resource[$ProdID];
				$CurrRow['porcent']                  = $CurrentPlanet[$Field];				
				$CurrRow['option']					 = $this->build_options ( $CurrRow['porcent'] );
				$CurrRow['type']                     = $lang['tech'][$ProdID];
				$CurrRow['level']                    = ($ProdID > 200) ? $lang['rs_amount'] : $lang['rs_lvl'];
				$CurrRow['level_type']               = $CurrentPlanet[ $resource[$ProdID] ];
				$CurrRow['metal_type']               = Format::pretty_number ( $metal     );
				$CurrRow['crystal_type']             = Format::pretty_number ( $crystal   );
				$CurrRow['deuterium_type']           = Format::pretty_number ( $deuterium );
				$CurrRow['energy_type']              = Format::pretty_number ( $energy    );
				$CurrRow['metal_type']               = Format::color_number ( $CurrRow['metal_type']     );
				$CurrRow['crystal_type']             = Format::color_number ( $CurrRow['crystal_type']   );
				$CurrRow['deuterium_type']           = Format::color_number ( $CurrRow['deuterium_type'] );
				$CurrRow['energy_type']              = Format::color_number ( $CurrRow['energy_type']    );
				$parse['resource_row']              .= parsetemplate ($ResourcesRowTPL , $CurrRow );
			}
		}

		$parse['Production_of_resources_in_the_planet'] = str_replace('%s', $CurrentPlanet['name'], $lang['rs_production_on_planet']);
		
		$parse['production_level']		 = $this->prod_level ( $CurrentPlanet['energy_used'] , $CurrentPlanet['energy_max'] );
		$parse['metal_basic_income']     = $game_metal_basic_income;
		$parse['crystal_basic_income']   = $game_crystal_basic_income;
		$parse['deuterium_basic_income'] = $game_deuterium_basic_income;
		$parse['energy_basic_income']    = $game_energy_basic_income;
		$parse['metal_max']             .= $this->resource_color ( $CurrentPlanet['metal'] , $CurrentPlanet['metal_max'] );
		$parse['crystal_max']           .= $this->resource_color ( $CurrentPlanet['crystal'] , $CurrentPlanet['crystal_max'] );
		$parse['deuterium_max']         .= $this->resource_color ( $CurrentPlanet['deuterium'] , $CurrentPlanet['deuterium_max'] );

		$parse['metal_total']           = Format::color_number( Format::pretty_number( floor( ( ($CurrentPlanet['metal_perhour']     * 0.01 * $parse['production_level'] ) + $parse['metal_basic_income']))));
		$parse['crystal_total']         = Format::color_number( Format::pretty_number( floor( ( ($CurrentPlanet['crystal_perhour']   * 0.01 * $parse['production_level'] ) + $parse['crystal_basic_income']))));
		$parse['deuterium_total']       = Format::color_number( Format::pretty_number( floor( ( ($CurrentPlanet['deuterium_perhour'] * 0.01 * $parse['production_level'] ) + $parse['deuterium_basic_income']))));
		$parse['energy_total']          = Format::color_number( Format::pretty_number( floor( ( $CurrentPlanet['energy_max'] + $parse['energy_basic_income']    ) + $CurrentPlanet['energy_used'] ) ) );
			
			
		$parse['daily_metal']			= $this->calculate_daily ( $CurrentPlanet['metal_perhour'] , $parse['production_level'] , $parse['metal_basic_income'] );	
		$parse['weekly_metal']			= $this->calculate_weekly ( $CurrentPlanet['metal_perhour'] , $parse['production_level'] , $parse['metal_basic_income'] );	
		
		
		$parse['daily_crystal']			= $this->calculate_daily ( $CurrentPlanet['crystal_perhour'] , $parse['production_level'] , $parse['crystal_basic_income'] );
		$parse['weekly_crystal']		= $this->calculate_weekly ( $CurrentPlanet['crystal_perhour'] , $parse['production_level'] , $parse['crystal_basic_income'] );		
			
		
		$parse['daily_deuterium']		= $this->calculate_daily ( $CurrentPlanet['deuterium_perhour'] , $parse['production_level'] , $parse['deuterium_basic_income'] );
		$parse['weekly_deuterium']		= $this->calculate_weekly ( $CurrentPlanet['deuterium_perhour'] , $parse['production_level'] , $parse['deuterium_basic_income'] );


		$parse['daily_metal']           = Format::color_number(Format::pretty_number($parse['daily_metal']));
		$parse['weekly_metal']          = Format::color_number(Format::pretty_number($parse['weekly_metal']));

		$parse['daily_crystal']         = Format::color_number(Format::pretty_number($parse['daily_crystal']));
		$parse['weekly_crystal']        = Format::color_number(Format::pretty_number($parse['weekly_crystal']));

		$parse['daily_deuterium']       = Format::color_number(Format::pretty_number($parse['daily_deuterium']));
		$parse['weekly_deuterium']      = Format::color_number(Format::pretty_number($parse['weekly_deuterium']));


		$ValidList['percent'] = array (  0,  10,  20,  30,  40,  50,  60,  70,  80,  90, 100 );
		$SubQry               = "";

		if ($_POST)
		{
			foreach($_POST as $Field => $Value)
			{
				$FieldName = $Field."_porcent";
				if ( isset( $CurrentPlanet[ $FieldName ] ) )
				{
					if ( ! in_array( $Value, $ValidList['percent']) )
					{
						header("Location: game.php?page=resources");
						exit;
					}

					$Value                        = $Value / 10;
					$CurrentPlanet[ $FieldName ]  = $Value;
					$SubQry                      .= ", `".$FieldName."` = '".$Value."'";
				}
			}
				
			$QryUpdatePlanet  = "UPDATE {{table}} SET ";
			$QryUpdatePlanet .= "`id` = '". $CurrentPlanet['id'] ."' ";
			$QryUpdatePlanet .= $SubQry;
			$QryUpdatePlanet .= "WHERE ";
			$QryUpdatePlanet .= "`id` = '". $CurrentPlanet['id'] ."';";
			doquery( $QryUpdatePlanet, 'planets');
			
			header("Location: game.php?page=resources");
		}

		return display(parsetemplate( gettemplate('resources/resources'), $parse));
	}
	
	/**
	 * method build_options
	 * param $current_porcentage
	 * return porcentage options for the select element
	 */
	private function build_options ( $current_porcentage )
	{
		for ( $option = 10 ; $option >= 0 ; $option-- )
		{
			$opt_value			= $option * 10;
			
			if ( $option == $current_porcentage )
			{
				$opt_selected	= " selected=selected";
			}
			else
			{
				$opt_selected	= "";
			}
			
			$option_row .= "<option value=\"" . $opt_value . "\"" . $opt_selected . ">" . $opt_value . "%</option>";
		}
		
		return $option_row;
	}
	
	/**
	 * method calculate_daily
	 * param1 $prod_per_hour
	 * param2 $prod_level
	 * param3 $basic_income
	 * return production per day
	 */
	private function calculate_daily ( $prod_per_hour , $prod_level , $basic_income )
	{	
		return floor ( ( $basic_income + ( $prod_per_hour * 0.01 * $prod_level ) ) * 24 );		
	}
	
	/**
	 * method calculate_weekly
	 * param1 $prod_per_hour
	 * param2 $prod_level
	 * param3 $basic_income
	 * return production per week
	 */
	private function calculate_weekly ( $prod_per_hour , $prod_level , $basic_income )
	{		
		return floor ( ( $basic_income + ( $prod_per_hour * 0.01 * $prod_level ) ) * 24 * 7 );
	}
	
	/**
	 * method resource_color
	 * param1 $current_amount
	 * param2 $max_amount
	 * return color depending on the current storage capacity
	 */
	private function resource_color ( $current_amount , $max_amount )
	{
		if ( $max_amount < $current_amount )
		{
			return ( Format::color_red ( Format::pretty_number ( $max_amount / 1000 ) . 'k' ) );
		}
		else
		{
			return ( Format::color_green ( Format::pretty_number ( $max_amount / 1000 ) . 'k' ) );
		}
	}
	
	/**
	 * method prod_level
	 * param1 $energy_used
	 * param2 $energy_max
	 * return the production level based on the energy consumption
	 */
	private function prod_level ( $energy_used , $energy_max )
	{
		if ( $energy_max == 0 && $energy_used > 0 )
		{
			$prod_level	= 0;
		}
		elseif ( $energy_max > 0 && abs ( $energy_used ) > $energy_max )
		{
			$prod_level	= floor ( ( $energy_max ) / ( $energy_used * -1 ) * 100 );
		}
		elseif ($energy_max == 0 && abs ( $energy_used ) > $energy_max )
		{
			$prod_level = 0;
		}
		else
		{
			$prod_level = 100;
		}

		if ( $prod_level > 100 )
		{
			$prod_level	= 100;
		}
		
		return $prod_level;
	}
}
?>