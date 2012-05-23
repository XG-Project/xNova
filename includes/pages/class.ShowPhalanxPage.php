<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

/**
 * @autor jstar, 
 * @version v2
 * @copyright gnu v3 
 */

if (!defined('INSIDE')){die(header("location:../../"));}
    
class ShowPhalanxPage
{
    public function __construct($CurrentUser, $CurrentPlanet)
    {
        global $lang;

        include_once (XGP_ROOT . 'includes/functions/InsertJavaScriptChronoApplet.' . $phpEx);
        include_once (XGP_ROOT . 'includes/classes/class.FlyingFleetsTable.' . $phpEx);
        include_once (XGP_ROOT . 'includes/classes/class.GalaxyRows.' . $phpEx);

        $FlyingFleetsTable = new FlyingFleetsTable();
        $GalaxyRows = new GalaxyRows();

        $parse = $lang;
        /* range */
        $radar_limit_inf = $CurrentPlanet['system'] - $GalaxyRows->GetPhalanxRange($CurrentPlanet['phalanx']);
        $radar_limit_sup = $CurrentPlanet['system'] + $GalaxyRows->GetPhalanxRange($CurrentPlanet['phalanx']);
        $radar_limit_inf = max($radar_limit_inf, 1);
        $radar_limit_sup = min($radar_limit_sup, MAX_SYSTEM_IN_GALAXY);
        /* input validation */
        $Galaxy = intval($_GET["galaxy"]);
        $System = intval($_GET["system"]);
        $Planet = intval($_GET["planet"]);
        $PlType = intval($_GET["planettype"]);
        /* cheater detection */
        if ($System < $radar_limit_inf || $System > $radar_limit_sup || $Galaxy != $CurrentPlanet['galaxy'] || $PlType != 1 || $CurrentPlanet['planet_type'] != 3)
            die(header("Location: game.php?page=galaxy"));
        /* main page */
        if ($CurrentPlanet['deuterium'] > 10000)
        {
            doquery("UPDATE {{table}} SET `deuterium` = `deuterium` - '10000' WHERE `id` = '" . $CurrentUser['current_planet'] . "';", 'planets');

            $QryTargetInfo = "SELECT ";

            $QryTargetInfo .= "`name`, ";
            $QryTargetInfo .= "`id_owner` ";
            $QryTargetInfo .= "FROM {{table}} WHERE ";
            $QryTargetInfo .= "`galaxy` = '" . $Galaxy . "' AND ";
            $QryTargetInfo .= "`system` = '" . $System . "' AND ";
            $QryTargetInfo .= "`planet` = '" . $Planet . "' AND ";
            $QryTargetInfo .= "`planet_type` = 1 ";
            $TargetInfo = doquery($QryTargetInfo, 'planets', true);
            $TargetID = $TargetInfo['id_owner'];
            $TargetName = $TargetInfo['name'];

            $QryTargetInfo = "SELECT ";
            $QryTargetInfo .= "`destruyed` ";
            $QryTargetInfo .= "FROM {{table}} WHERE ";
            $QryTargetInfo .= "`galaxy` = '" . $Galaxy . "' AND ";
            $QryTargetInfo .= "`system` = '" . $System . "' AND ";
            $QryTargetInfo .= "`planet` = '" . $Planet . "' AND ";
            $QryTargetInfo .= "`planet_type` = 3 ";
            $TargetInfo = doquery($QryTargetInfo, 'planets', true);
            //if there isn't a moon,
            if ($TargetInfo === false)
            {
                $TargetMoonIsDestroyed = true;
            }
            else
            {
                $TargetMoonIsDestroyed = $TargetInfo['destruyed'] !== 0;
            }

            $QryLookFleets = "SELECT * ";
            $QryLookFleets .= "FROM {{table}} ";
            $QryLookFleets .= "WHERE ( ( ";
            $QryLookFleets .= "`fleet_start_galaxy` = '" . $Galaxy . "' AND ";
            $QryLookFleets .= "`fleet_start_system` = '" . $System . "' AND ";
            $QryLookFleets .= "`fleet_start_planet` = '" . $Planet . "'  ";
            $QryLookFleets .= ") OR ( ";
            $QryLookFleets .= "`fleet_end_galaxy` = '" . $Galaxy . "' AND ";
            $QryLookFleets .= "`fleet_end_system` = '" . $System . "' AND ";
            $QryLookFleets .= "`fleet_end_planet` = '" . $Planet . "'  ";
            $QryLookFleets .= ") ) ;";

            $FleetToTarget = doquery($QryLookFleets, 'fleets');

            $Record = 0;
            $fpage = array();
            while ($FleetRow = mysql_fetch_array($FleetToTarget))
            {
                $Record++;

                $ArrivetoTargetTime = $FleetRow['fleet_start_time'];
                $EndStayTime = $FleetRow['fleet_end_stay'];
                $ReturnTime = $FleetRow['fleet_end_time'];
                $Mission = $FleetRow['fleet_mission'];
                $myFleet = ($FleetRow['fleet_owner'] == $TargetID) ? true : false;
                $FleetRow['fleet_resource_metal'] = 0;
                $FleetRow['fleet_resource_crystal'] = 0;
                $FleetRow['fleet_resource_deuterium'] = 0;
                $isStartedfromThis = $FleetRow['fleet_start_galaxy'] == $Galaxy && $FleetRow['fleet_start_system'] == $System && $FleetRow['fleet_start_planet'] == $Planet;
                $isTheTarget = $FleetRow['fleet_end_galaxy'] == $Galaxy && $FleetRow['fleet_end_system'] == $System && $FleetRow['fleet_end_planet'] == $Planet;


                /* 1)the arrive to target fleet table event
                * you can see start-fleet event only if this is a planet(or destroyed moon) 
                * and if the fleet mission started from this planet is different from hold 
                * or if it's a enemy mission.
                */
                if ($ArrivetoTargetTime > time())
                {
                    //scannig of fleet started planet
                    if ($isStartedfromThis && ($FleetRow['fleet_start_type'] == 1 || ($FleetRow['fleet_start_type'] == 3 && $TargetMoonIsDestroyed)))
                    {
                        if ($Mission != 4)
                        {
                            $Label = "fs";
                            $fpage[$ArrivetoTargetTime] = .= "\n". $FlyingFleetsTable->BuildFleetEventTable($FleetRow, 0, $myFleet, $Label, $Record);
                        }
                    }
                    //scanning of destination fleet planet
                    elseif (!$isStartedfromThis && ($FleetRow['fleet_end_type'] == 1 || ($FleetRow['fleet_end_type'] == 3 && $TargetMoonIsDestroyed)))
                    {
                        $Label = "fs";
                        $fpage[$ArrivetoTargetTime] .= "\n". $FlyingFleetsTable->BuildFleetEventTable($FleetRow, 0, $myFleet, $Label, $Record);
                    }
                }
                /* 2)the stay fleet table event
                * you can see stay-fleet event only if the target is a planet(or destroyed moon) and is the targetPlanet
                */
                if ($EndStayTime > time() && $Mission == 5 && ($FleetRow['fleet_end_type'] == 1 || ($FleetRow['fleet_end_type'] == 3 && $TargetMoonIsDestroyed)) && $isTheTarget)
                {
                    $Label = "ft";
                    $fpage[$EndStayTime] .= "\n". $FlyingFleetsTable->BuildFleetEventTable($FleetRow, 1, $myFleet, $Label, $Record);
                }
                /* 3)the return fleet table event
                * you can see the return fleet if this is the started planet(or destroyed moon)
                * but no if it is a hold mission or mip         
                */
                if ($ReturnTime > time() && $Mission != 4 && $Mission != 10 && $isStartedfromThis && ($FleetRow['fleet_start_type'] == 1 || ($FleetRow['fleet_start_type'] == 3 && $TargetMoonIsDestroyed)))
                {
                    $Label = "fe";
                    $fpage[$ReturnTime] .= "\n". $FlyingFleetsTable->BuildFleetEventTable($FleetRow, 2, $myFleet, $Label, $Record);
                }
            }
            ksort($fpage);
            foreach ($fpage as $FleetTime => $FleetContent)
                $Fleets .= $FleetContent . "\n";

            $parse['phl_fleets_table'] = $Fleets;
            $parse['phl_er_deuter'] = "";
        }
        else
            $parse['phl_er_deuter'] = $lang['px_no_deuterium'];

        $parse['phl_pl_galaxy'] = $Galaxy;
        $parse['phl_pl_system'] = $System;
        $parse['phl_pl_place'] = $Planet;
        $parse['phl_pl_name'] = $TargetName;

        return display(parsetemplate(gettemplate('galaxy/phalanx_body'), $parse), false, '', false, false);
    }
}

?>