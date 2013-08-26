<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

/**
 * Modded version by Think for xgproyect.
 * Versión mejorada del archivo que además permite la configuración de recursos
 */
    function PlanetResourceUpdate ( $CurrentUser, &$CurrentPlanet, $UpdateTime, $Simul = FALSE )
    {
        global $ProdGrid, $resource, $reslist;

        //Multiplicador de recursos y tiempo de producción
        $game_resource_multiplier        =    read_config ( 'resource_multiplier' );
        $ProductionTime               = ($UpdateTime - $CurrentPlanet['last_update']);
        $CurrentPlanet['last_update'] = $UpdateTime;
        
        $rList = array("metal"=>array("warehouse_num"=>22),
                       "crystal"=>array("warehouse_num"=>23),
                       "deuterium"=>array("warehouse_num"=>24));

        $BuildTemp    = $CurrentPlanet[ 'temp_max' ];
        $post_porcent =    Production::max_production ( $CurrentPlanet['energy_max'] , $CurrentPlanet['energy_used'] );

        $EnergyLevel = $CurrentUser['energy_tech'];
        
        //Data init: Set vars on zero so they don't start madly increasing.
        foreach($rList as $rname => $rdata) {
            $CurrentPlanet[$rname.'_perhour'] = 0;
        }
        $CurrentPlanet['energy_max'] = $CurrentPlanet['energy_used'] = 0;
        
        if ($CurrentPlanet['planet_type'] != 3)
        {
            foreach ( $reslist['prod'] as $ProdID)
            {
                $BuildLevelFactor            = $CurrentPlanet[ $resource[$ProdID] ."_porcent" ];
                $BuildLevel                    = $CurrentPlanet[ $resource[$ProdID] ];
                                
                // BOOST
                $geologe_boost                = 1 + ( $CurrentUser['rpg_geologue']  * GEOLOGUE );
                $engineer_boost                = 1 + ( $CurrentUser['rpg_ingenieur'] * ENGINEER_ENERGY );
                
                //Standard resources
                foreach($rList as $rname => $rdata)
                {
                    $cProd = eval ( $ProdGrid[$ProdID]['formule'][$rname] );  // PRODUCTION FORMULAS
                    $CurrentPlanet[$rname.'_perhour']        += Production::current_production ( Production::production_amount ( $cProd , $geologe_boost ) , $post_porcent); // PRODUCTION
                }
                
                //Energy
                $cProd = eval ( $ProdGrid[$ProdID]['formule']["energy"] );
                $energy_prod = Production::current_production ( Production::production_amount ( $cProd , $geologe_boost ) , $post_porcent);
                
                if( $ProdID >= 4 )
                {                    
                    if ( $ProdID == 12 && $CurrentPlanet['deuterium'] == 0 ) {
                        continue;
                    }
                                            
                    $CurrentPlanet['energy_max']        +=  Production::production_amount ( $energy_prod , $engineer_boost );
                }
                else 
                {
                    $CurrentPlanet['energy_used']    += Production::production_amount ( $energy_prod , 1 );
                }
            }
        }
        
        if($CurrentPlanet['energy_max'] == 0) //Zero production in no energy planets
        {
            foreach($rList as $rname => $rdata) {
                $CurrentPlanet[$rname."_perhour"] = 0;
            }
        }

        $production_level =    Production::max_production ( $CurrentPlanet['energy_max'] , $CurrentPlanet['energy_used'] );

        $rUpd = "";
        foreach($rList as $rname => $rdata)
        {
            if ($CurrentPlanet['planet_type'] != 3)
                $b_income[$rname] = read_config ( $rname.'_basic_income' );
            else
                $b_income[$rname] = 0;
            
            $CurrentPlanet[$rname.'_max'] = Production::max_storable ( $CurrentPlanet[ $resource[$rdata['warehouse_num'] ]]);
            
            if ( $CurrentPlanet[$rname] <= $CurrentPlanet[$rname.'_max'] )
            {
                $prod = (($ProductionTime * ($CurrentPlanet[$rname.'_perhour'] / 3600))) * (0.01 * $production_level);
                $base_prod = (($ProductionTime * ($b_income[$rname] / 3600 )));
                $theorical  = $CurrentPlanet[$rname] + $prod  +  $base_prod;
                if ( $theorical <= $CurrentPlanet[$rname.'_max'] ) {
                    $CurrentPlanet[$rname]  = $theorical;
                }
                else {
                    $CurrentPlanet[$rname]  = $CurrentPlanet[$rname.'_max'];
                }
            }
            
            if( $CurrentPlanet[$rname] < 0 ) {
                $CurrentPlanet[$rname]  = 0;
            }
            
            $rUpd .= "`$rname` = '" . $CurrentPlanet[$rname] ."', ";
            $rUpd .= "`".$rname."_perhour` = '" . $CurrentPlanet[$rname.'_perhour'] ."', ";
        }

        if ($Simul == FALSE)
        {
            $Built          = HandleElementBuildingQueue ( $CurrentUser, $CurrentPlanet, $ProductionTime );

            $QryUpdatePlanet  = "UPDATE {{table}} SET ";
            $QryUpdatePlanet .= $rUpd;
            $QryUpdatePlanet .= "`last_update` = '"      . $CurrentPlanet['last_update']       ."', ";
            $QryUpdatePlanet .= "`b_hangar_id` = '"      . $CurrentPlanet['b_hangar_id']       ."', ";
            $QryUpdatePlanet .= "`energy_used` = '"      . $CurrentPlanet['energy_used']       ."', ";
            $QryUpdatePlanet .= "`energy_max` = '"       . $CurrentPlanet['energy_max']        ."', ";
            if ( $Built != '' )
            {
                foreach ( $Built as $Element => $Count )
                {
                    if ($Element AND is_numeric($Element))
                        $QryUpdatePlanet .= "`". $resource[$Element] ."` = '". $CurrentPlanet[$resource[$Element]] ."', ";
                }
            }
            $QryUpdatePlanet .= "`b_hangar` = '". $CurrentPlanet['b_hangar'] ."' ";
            $QryUpdatePlanet .= "WHERE ";
            $QryUpdatePlanet .= "`id` = '". $CurrentPlanet['id'] ."';";
            doquery($QryUpdatePlanet, 'planets');
        }
    }
?>