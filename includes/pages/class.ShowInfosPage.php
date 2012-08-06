<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowInfosPage
{
    private function GetNextJumpWaitTime($CurMoon)
    {
        global $resource;

        $JumpGateLevel  = $CurMoon[$resource[43]];
        $LastJumpTime   = $CurMoon['last_jump_time'];
        if ($JumpGateLevel > 0)
        {
            $WaitBetweenJmp = (60 * 60) * (1 / $JumpGateLevel);
            $NextJumpTime   = $LastJumpTime + $WaitBetweenJmp;
            if ($NextJumpTime >= time())
            {
                $RestWait   = $NextJumpTime - time();
                $RestString = " ". Format::pretty_time($RestWait);
            }
            else
            {
                $RestWait   = 0;
                $RestString = "";
            }
        }
        else
        {
            $RestWait   = 0;
            $RestString = "";
        }
        $RetValue['string'] = $RestString;
        $RetValue['value']  = $RestWait;

        return $RetValue;
    }

    private function DoFleetJump ($CurrentUser, $CurrentPlanet)
    {
        global $resource, $lang;

        if ($_POST)
        {
            $RestString   = $this->GetNextJumpWaitTime ($CurrentPlanet);
            $NextJumpTime = $RestString['value'];
            $JumpTime     = time();

            if ( $NextJumpTime == 0 )
            {
                $TargetPlanet = $_POST['jmpto'];
                $TargetGate   = doquery ( "SELECT `id`, `sprungtor`, `last_jump_time` FROM {{table}} WHERE `id` = '". $TargetPlanet ."';", 'planets', true);

                if ($TargetGate['sprungtor'] > 0)
                {
                    $RestString   = $this->GetNextJumpWaitTime ( $TargetGate );
                    $NextDestTime = $RestString['value'];

                    if ( $NextDestTime == 0 )
                    {
                        $ShipArray   = array();
                        $SubQueryOri = "";
                        $SubQueryDes = "";

                        for ( $Ship = 200; $Ship < 300; $Ship++ )
                        {
                            $ShipLabel = "c". $Ship;
                            $gemi_kontrol    =    $_POST[ $ShipLabel ];
                            
                            if (is_numeric($gemi_kontrol))
                            {
                                if ( $gemi_kontrol > $CurrentPlanet[ $resource[ $Ship ] ])
                                {
                                    $ShipArray[ $Ship ] = $CurrentPlanet[ $resource[ $Ship ] ];
                                }
                                else
                                {
                                    $ShipArray[ $Ship ] = $gemi_kontrol;
                                }
                            
                                
                                if ($ShipArray[ $Ship ] > 0)
                                {
                                    $SubQueryOri .= "`". $resource[ $Ship ] ."` = `". $resource[ $Ship ] ."` - '". $ShipArray[ $Ship ] ."', ";
                                    $SubQueryDes .= "`". $resource[ $Ship ] ."` = `". $resource[ $Ship ] ."` + '". $ShipArray[ $Ship ] ."', ";
                                }
                            }
                        }
                        if ($SubQueryOri != "")
                        {
                            $QryUpdateOri  = "UPDATE {{table}} SET ";
                            $QryUpdateOri .= $SubQueryOri;
                            $QryUpdateOri .= "`last_jump_time` = '". $JumpTime ."' ";
                            $QryUpdateOri .= "WHERE ";
                            $QryUpdateOri .= "`id` = '". $CurrentPlanet['id'] ."';";
                            doquery ( $QryUpdateOri, 'planets');

                            $QryUpdateDes  = "UPDATE {{table}} SET ";
                            $QryUpdateDes .= $SubQueryDes;
                            $QryUpdateDes .= "`last_jump_time` = '". $JumpTime ."' ";
                            $QryUpdateDes .= "WHERE ";
                            $QryUpdateDes .= "`id` = '". $TargetGate['id'] ."';";
                            doquery ( $QryUpdateDes, 'planets');

                            $QryUpdateUsr  = "UPDATE {{table}} SET ";
                            $QryUpdateUsr .= "`current_planet` = '". $TargetGate['id'] ."' ";
                            $QryUpdateUsr .= "WHERE ";
                            $QryUpdateUsr .= "`id` = '". $CurrentUser['id'] ."';";
                            doquery ( $QryUpdateUsr, 'users');

                            $CurrentPlanet['last_jump_time'] = $JumpTime;
                            $RestString    = $this->GetNextJumpWaitTime ( $CurrentPlanet );
                            $RetMessage    = $lang['in_jump_gate_done'] . $RestString['string'];
                        }
                        else
                        {
                            $RetMessage = $lang['in_jump_gate_error_data'];
                        }
                    }
                    else
                    {
                        $RetMessage = $lang['in_jump_gate_not_ready_target'] . $RestString['string'];
                    }
                }
                else
                {
                    $RetMessage = $lang['in_jump_gate_doesnt_have_one'];
                }
            }
            else
            {
                $RetMessage = $lang['in_jump_gate_already_used'] . $RestString['string'];
            }
        }
        else
        {
            $RetMessage = $lang['in_jump_gate_error_data'];
        }

        return $RetMessage;
    }  

    private function BuildFleetListRows ($CurrentPlanet)
    {
        global $resource, $lang;

        $RowsTPL  = gettemplate('infos/info_gate_rows');
        $CurrIdx  = 1;
        $Result   = "";
        for ($Ship = 200; $Ship < 250; $Ship++ )
        {
            if ($resource[$Ship] != "")
            {
                if ($CurrentPlanet[$resource[$Ship]] > 0)
                {
                    $bloc['idx']             = $CurrIdx;
                    $bloc['fleet_id']        = $Ship;
                    $bloc['fleet_name']      = $lang['tech'][$Ship];
                    $bloc['fleet_max']       = Format::pretty_number ( $CurrentPlanet[$resource[$Ship]] );
                    $bloc['gate_ship_dispo'] = $lang['in_jump_gate_available'];
                    $Result                 .= parsetemplate ( $RowsTPL, $bloc );
                    $CurrIdx++;
                }
            }
        }
        return $Result;
    }

    private function BuildJumpableMoonCombo ( $CurrentUser, $CurrentPlanet )
    {
        global $resource;
        $QrySelectMoons  = "SELECT * FROM {{table}} WHERE `planet_type` = '3' AND `id_owner` = '". intval($CurrentUser['id']) ."';";
        $MoonList        = doquery ( $QrySelectMoons, 'planets');
        $Combo           = "";
        while ( $CurMoon = mysql_fetch_assoc($MoonList) )
        {
            if ( $CurMoon['id'] != $CurrentPlanet['id'] )
            {
                $RestString = $this->GetNextJumpWaitTime ( $CurMoon );
                if ($CurMoon[$resource[43]] >= 1)
                    $Combo .= "<option value=\"". $CurMoon['id'] ."\">[". $CurMoon['galaxy'] .":". $CurMoon['system'] .":". $CurMoon['planet'] ."] ". $CurMoon['name'] . $RestString['string'] ."</option>\n";
            }
        }
        return $Combo;
    }

    private function ShowProductionTable ($CurrentUser, $CurrentPlanet, $BuildID, $Template)
    {
        global $ProdGrid, $resource;
        
        $BuildLevelFactor 	= $CurrentPlanet[ $resource[$BuildID]."_porcent" ];
        $BuildTemp        	= $CurrentPlanet[ 'temp_max' ];
        $CurrentBuildtLvl 	= $CurrentPlanet[ $resource[$BuildID] ];
        $BuildLevel       	= ($CurrentBuildtLvl > 0) ? $CurrentBuildtLvl : 1;
        $EnergyLevel        = $CurrentUser["energy_tech"]; 
        
		// BOOST
		$geologe_boost		= 1 + ( $CurrentUser['rpg_geologue']  * GEOLOGUE );
		$engineer_boost		= 1 + ( $CurrentUser['rpg_ingenieur'] * ENGINEER_ENERGY );
		
		// PRODUCTION FORMULAS
		$metal_prod			= eval ( $ProdGrid[$BuildID]['formule']['metal'] );
		$crystal_prod		= eval ( $ProdGrid[$BuildID]['formule']['crystal'] );
		$deuterium_prod		= eval ( $ProdGrid[$BuildID]['formule']['deuterium'] );
		$energy_prod		= eval ( $ProdGrid[$BuildID]['formule']['energy'] );
		
		// PRODUCTION
		$Prod[1]			= Production::production_amount ( $metal_prod , $geologe_boost );
		$Prod[2]			= Production::production_amount ( $crystal_prod , $geologe_boost );
		$Prod[3]			= Production::production_amount ( $deuterium_prod , $geologe_boost );

		if( $BuildID >= 4 )
		{							
			$Prod[4]		= Production::production_amount ( $energy_prod , $engineer_boost );
			$ActualProd    	= floor ( $Prod[4] );
		}
		else 
		{
			$Prod[4]		= Production::production_amount ( $energy_prod , 1 );
			$ActualProd    	= floor ( $Prod[$BuildID] );
		}

        if ( $BuildID != 12 )
        {
        	$ActualNeed     = floor ( $Prod[4] );
        }    
        else
        {
        	$ActualNeed		= floor ( $Prod[3] );
        }

        $BuildStartLvl    = $CurrentBuildtLvl - 2;
        if ($BuildStartLvl < 1)
            $BuildStartLvl = 1;

        $Table     = "";
        $ProdFirst = 0;

        for ( $BuildLevel = $BuildStartLvl; $BuildLevel < $BuildStartLvl + 15; $BuildLevel++ )
        {
            if ( $BuildID != 42 )
            {
				// PRODUCTION FORMULAS
				$metal_prod			= eval ( $ProdGrid[$BuildID]['formule']['metal'] );
				$crystal_prod		= eval ( $ProdGrid[$BuildID]['formule']['crystal'] );
				$deuterium_prod		= eval ( $ProdGrid[$BuildID]['formule']['deuterium'] );
				$energy_prod		= eval ( $ProdGrid[$BuildID]['formule']['energy'] );
				
				// PRODUCTION
				$Prod[1]			= Production::production_amount ( $metal_prod , $geologe_boost );
				$Prod[2]			= Production::production_amount ( $crystal_prod , $geologe_boost );
				$Prod[3]			= Production::production_amount ( $deuterium_prod , $geologe_boost );
		
				if( $BuildID >= 4 )
				{							
					$Prod[4]		= Production::production_amount ( $energy_prod , $engineer_boost );
				}
				else 
				{
					$Prod[4]		= Production::production_amount ( $energy_prod , 1 );
				}

                $bloc['build_lvl']       = ($CurrentBuildtLvl == $BuildLevel) ? "<font color=\"#ff0000\">".$BuildLevel."</font>" : $BuildLevel;

                if ($ProdFirst > 0)
                    if ($BuildID != 12)
                        $bloc['build_gain']      = "<font color=\"lime\">(". Format::pretty_number(floor($Prod[$BuildID] - $ProdFirst)) .")</font>";
                    else
                        $bloc['build_gain']      = "<font color=\"lime\">(". Format::pretty_number(floor($Prod[4] - $ProdFirst)) .")</font>";
                else
                    $bloc['build_gain']      = "";

                if ($BuildID != 12)
                {
                    $bloc['build_prod']      = Format::pretty_number(floor($Prod[$BuildID]));
                    $bloc['build_prod_diff'] = Format::color_number( Format::pretty_number(floor($Prod[$BuildID] - $ActualProd)) );
                    $bloc['build_need']      = Format::color_number( Format::pretty_number(floor($Prod[4])) );
                    $bloc['build_need_diff'] = Format::color_number( Format::pretty_number(floor($Prod[4] - $ActualNeed)) );
                }
                else
                {
                    $bloc['build_prod']      = Format::pretty_number(floor($Prod[4]));
                    $bloc['build_prod_diff'] = Format::color_number( Format::pretty_number(floor($Prod[4] - $ActualProd)) );
                    $bloc['build_need']      = Format::color_number( Format::pretty_number(floor($Prod[3])) );
                    $bloc['build_need_diff'] = Format::color_number( Format::pretty_number(floor($Prod[3] - $ActualNeed)) );
                }
                if ($ProdFirst == 0)
                {
                    if ($BuildID != 12)
                        $ProdFirst = floor($Prod[$BuildID]);
                    else
                        $ProdFirst = floor($Prod[4]);
                }
            }
            else
            {
                $bloc['build_lvl']       = ($CurrentBuildtLvl == $BuildLevel) ? "<font color=\"#ff0000\">".$BuildLevel."</font>" : $BuildLevel;
                $bloc['build_range']     = ($BuildLevel * $BuildLevel) - 1;
            }
            $Table    .= parsetemplate($Template, $bloc);
        }

        return $Table;
    }

    private function ShowRapidFireTo ($BuildID)
    {
        global $lang, $CombatCaps;
        $ResultString = "";
        for ($Type = 200; $Type < 500; $Type++)
        {
            if ($CombatCaps[$BuildID]['sd'][$Type] > 1)
                $ResultString .= $lang['in_rf_again']. " ". $lang['tech'][$Type] ." <font color=\"#00ff00\">".$CombatCaps[$BuildID]['sd'][$Type]."</font><br>";
        }
        return $ResultString;
    }

    private function ShowRapidFireFrom ($BuildID)
    {
        global $lang, $CombatCaps;

        $ResultString = "";
        for ($Type = 200; $Type < 500; $Type++)
        {
            if ($CombatCaps[$Type]['sd'][$BuildID] > 1)
                $ResultString .= $lang['in_rf_from']. " ". $lang['tech'][$Type] ." <font color=\"#ff0000\">".$CombatCaps[$Type]['sd'][$BuildID]."</font><br>";
        }
        return $ResultString;
    }

    public function __construct ($CurrentUser, $CurrentPlanet, $BuildID)
    {
        global $lang, $resource, $pricelist, $CombatCaps;

		if ( !array_key_exists ( $BuildID , $resource ) )
		{
			die ( header ( 'Location: game.php?page=techtree' ) );  
		}       

        $GateTPL              = '';
        $DestroyTPL           = '';
        $TableHeadTPL         = '';

        $parse                = $lang;
        $parse['dpath']       = DPATH;
        $parse['name']        = $lang['info'][$BuildID]['name'];
        $parse['image']       = $BuildID;
        $parse['description'] = $lang['info'][$BuildID]['description'];


        if ($BuildID < 13 OR ($BuildID == 43 && $CurrentPlanet[$resource[43]] > 0))
            $PageTPL = gettemplate('infos/info_buildings_table');  
        elseif($BuildID < 200)
            $PageTPL = gettemplate('infos/info_buildings_general');
        elseif($BuildID < 400)
            $PageTPL = gettemplate('infos/info_buildings_fleet');
        elseif($BuildID < 600)
            $PageTPL = gettemplate('infos/info_buildings_defense');
        else
            $PageTPL = gettemplate('infos/info_officiers_general');

        //Sólo hay destroy en <200
        if($BuildID < 200 AND $BuildID != 33 AND $BuildID != 41)
            $DestroyTPL           = gettemplate('infos/info_buildings_destroy');

        if ($BuildID >=   1 && $BuildID <=   3)
        {
            $PageTPL              = gettemplate('infos/info_buildings_table');
            $TableHeadTPL         = gettemplate('infos/info_production_header');
            $TableTPL             = gettemplate('infos/info_production_body');
        }
        elseif ($BuildID ==   4)
        {
            $PageTPL              = gettemplate('infos/info_buildings_table');
            $TableHeadTPL         = gettemplate('infos/info_production_simple_header');
            $TableTPL             = gettemplate('infos/info_production_simple_body');
        }
        elseif ($BuildID ==  12)
        {
            $TableHeadTPL         = gettemplate('infos/info_energy_header');
            $TableTPL             = gettemplate('infos/info_energy_body');
        }
        /*elseif ($BuildID >=  14 AND $BuildID <= 100 AND $BuildID != 42 AND $BuildID != 41 AND $BuildID != 33 AND $BuildID != 43)
        {
            No hacemos NADA
        }*/
        /*elseif ($BuildID ==  33)
        {
            $PageTPL              = gettemplate('infos/info_buildings_general');
        }
        elseif ($BuildID ==  41)
        {
            $PageTPL              = gettemplate('infos/info_buildings_general');
        }*/
        elseif ($BuildID ==  42)
        {
        	$TableHeadTPL         = gettemplate('infos/info_range_header');
        	$TableTPL             = gettemplate('infos/info_range_body');
        }
        elseif ($BuildID ==  43)
        {
            $GateTPL              = gettemplate('infos/info_gate_table');

            if($_POST)
                message($this->DoFleetJump($CurrentUser, $CurrentPlanet), "game.php?page=infos&gid=43", 2);
        }
        /*elseif ($BuildID >= 106 && $BuildID <= 199)
        {
            NADA! -- $PageTPL              = gettemplate('infos/info_buildings_general');
        }*/
        elseif ($BuildID >= 202 && $BuildID <= 250)
        {
            $PageTPL              = gettemplate('infos/info_buildings_fleet');
            $parse['element_typ'] = $lang['tech'][200];
            $parse['rf_info_to']  = $this->ShowRapidFireTo($BuildID);
            $parse['rf_info_fr']  = $this->ShowRapidFireFrom($BuildID);
            $parse['hull_pt']     = Format::pretty_number ($pricelist[$BuildID]['metal'] + $pricelist[$BuildID]['crystal']);
            $parse['shield_pt']   = Format::pretty_number ($CombatCaps[$BuildID]['shield']);
            $parse['attack_pt']   = Format::pretty_number ($CombatCaps[$BuildID]['attack']);
            $parse['capacity_pt'] = Format::pretty_number ($pricelist[$BuildID]['capacity']);
            $parse['base_speed']  = Format::pretty_number ($pricelist[$BuildID]['speed']);
            $parse['base_conso']  = Format::pretty_number ($pricelist[$BuildID]['consumption']);
            if ($BuildID == 202)
            {
                $parse['upd_speed']   = "<font color=\"yellow\">(". Format::pretty_number ($pricelist[$BuildID]['speed2']) .")</font>";
                $parse['upd_conso']   = "<font color=\"yellow\">(". Format::pretty_number ($pricelist[$BuildID]['consumption2']) .")</font>";
            }
            elseif ($BuildID == 211)
                $parse['upd_speed']   = "<font color=\"yellow\">(". Format::pretty_number ($pricelist[$BuildID]['speed2']) .")</font>";
        }
        elseif ( $BuildID >= 401 && $BuildID <= 550 )
        {
            $PageTPL              = gettemplate('infos/info_buildings_defense');
            $parse['element_typ'] = $lang['tech'][400];

            if ( $BuildID < 500 )
            {
                $parse['rf_info_to']  = $this->ShowRapidFireTo ($BuildID);
                $parse['rf_info_fr']  = $this->ShowRapidFireFrom ($BuildID);
            }
            
            $parse['hull_pt']     = Format::pretty_number ($pricelist[$BuildID]['metal'] + $pricelist[$BuildID]['crystal']);
            $parse['shield_pt']   = Format::pretty_number ($CombatCaps[$BuildID]['shield']);
            $parse['attack_pt']   = Format::pretty_number ($CombatCaps[$BuildID]['attack']);
        }

        if ($TableHeadTPL != '')
        {
            $parse['table_head']  = parsetemplate ($TableHeadTPL, $lang);
            $parse['table_data']  = $this->ShowProductionTable ($CurrentUser, $CurrentPlanet, $BuildID, $TableTPL);
        }

        $page  = parsetemplate($PageTPL, $parse);
        if ($GateTPL != '')
        {
            if ($CurrentPlanet[$resource[$BuildID]] > 0)
            {
                $RestString               = $this->GetNextJumpWaitTime ( $CurrentPlanet );
                $parse['gate_start_link'] = BuildPlanetAdressLink ( $CurrentPlanet );
                if ($RestString['value'] != 0)
                {
                    include(XGP_ROOT . 'includes/functions/InsertJavaScriptChronoApplet.php');

                    $parse['gate_time_script'] = InsertJavaScriptChronoApplet ( "Gate", "1", $RestString['value'], TRUE );
                    $parse['gate_wait_time']   = "<div id=\"bxx". "Gate" . "1" ."\"></div>";
                    $parse['gate_script_go']   = InsertJavaScriptChronoApplet ( "Gate", "1", $RestString['value'], FALSE );
                }
                else
                {
                    $parse['gate_time_script'] = "";
                    $parse['gate_wait_time']   = "";
                    $parse['gate_script_go']   = "";
                }
                $parse['gate_dest_moons'] = $this->BuildJumpableMoonCombo ($CurrentUser, $CurrentPlanet);
                $parse['gate_fleet_rows'] = $this->BuildFleetListRows ($CurrentPlanet);
                $page .= parsetemplate($GateTPL, $parse);
            }
        }

        if ($DestroyTPL != '')
        {
            if ($CurrentPlanet[$resource[$BuildID]] > 0)
            {
                $NeededRessources     = GetBuildingPrice ($CurrentUser, $CurrentPlanet, $BuildID, TRUE, TRUE);
                $DestroyTime          = GetBuildingTime  ($CurrentUser, $CurrentPlanet, $BuildID) / 2;
                $parse['destroyurl']  = "game.php?page=buildings&cmd=destroy&building=".$BuildID;
                $parse['levelvalue']  = $CurrentPlanet[$resource[$BuildID]];
                $parse['nfo_metal']   = $lang['Metal'];
                $parse['nfo_crysta']  = $lang['Crystal'];
                $parse['nfo_deuter']  = $lang['Deuterium'];
                $parse['metal']       = Format::pretty_number ($NeededRessources['metal']);
                $parse['crystal']     = Format::pretty_number ($NeededRessources['crystal']);
                $parse['deuterium']   = Format::pretty_number ($NeededRessources['deuterium']);
                $parse['destroytime'] = Format::pretty_time   ($DestroyTime);
                $page .= parsetemplate($DestroyTPL, $parse);
            }
        }
        display($page);
    }
}