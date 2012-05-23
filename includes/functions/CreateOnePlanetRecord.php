<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */


if(!defined('INSIDE')){die(header("location:../../"));}

function PlanetSizeRandomiser($Position,$HomeWorld = FALSE)
{
   global $user;
   if(!$HomeWorld)
   {
      // linear distribution probability with right average, information from http://www.osite.it/index.php?zone=guide=colonizzare
      $RandomMin = array(37,43,50,65,47,34,97,105,110,65,71,71,30,14,22);
      $RandomMax = array(91,93,100,283,287,276,192,195,207,136,127,143,190,154,171);
      $PlanetFields = mt_rand($RandomMin[$Position - 1],$RandomMax[$Position - 1]);
      $PlanetFields *= PLANETSIZE_MULTIPLER;
   }
   else
   {
      $PlanetFields = read_config ( 'initial_fields' );
   }
   $PlanetSize = floor(sqrt($PlanetFields) * 1000);
   $return['diameter'] = $PlanetSize;
   $return['field_max'] = $PlanetFields;
   return $return;
}

function CreateOnePlanetRecord($Galaxy,$System,$Position,$PlanetOwnerID,$PlanetName = '',$HomeWorld = FALSE)
{
   global $lang;

   $QrySelectPlanet = "SELECT	`id` ";
   $QrySelectPlanet .= "FROM {{table}} ";
   $QrySelectPlanet .= "WHERE ";
   $QrySelectPlanet .= "`galaxy` = '" . $Galaxy . "' AND ";
   $QrySelectPlanet .= "`system` = '" . $System . "' AND ";
   $QrySelectPlanet .= "`planet` = '" . $Position . "';";
   $PlanetExist = doquery($QrySelectPlanet,'planets',TRUE);

   if(!$PlanetExist)
   {
      $planet = PlanetSizeRandomiser($Position,$HomeWorld);
      $planet['diameter'] = ($planet['field_max'] ^ (14 / 1.5)) * 75;
      $planet['metal'] = BUILD_METAL;
      $planet['crystal'] = BUILD_CRISTAL;
      $planet['deuterium'] = BUILD_DEUTERIUM;
      $planet['metal_perhour'] = read_config ( 'metal_basic_income' );
      $planet['crystal_perhour'] = read_config ( 'crystal_basic_income' );
      $planet['deuterium_perhour'] = read_config ( 'deuterium_basic_income' );
      $planet['metal_max'] = BASE_STORAGE_SIZE;
      $planet['crystal_max'] = BASE_STORAGE_SIZE;
      $planet['deuterium_max'] = BASE_STORAGE_SIZE;

      // Posistion  1 -  3: 80% entre  40 et  70 Cases (  55+ / -15 )
      // Posistion  4 -  6: 80% entre 120 et 310 Cases ( 215+ / -95 )
      // Posistion  7 -  9: 80% entre 105 et 195 Cases ( 150+ / -45 )
      // Posistion 10 - 12: 80% entre  75 et 125 Cases ( 100+ / -25 )
      // Posistion 13 - 15: 80% entre  60 et 190 Cases ( 125+ / -65 )


      $planet['galaxy'] = $Galaxy;
      $planet['system'] = $System;
      $planet['planet'] = $Position;

      if($Position == 1 || $Position == 2 || $Position == 3)
      {
         $PlanetType = array('trocken');
         $PlanetClass = array('planet');
         $PlanetDesign = array('01','02','03','04','05','06','07','08','09','10');
         $planet['temp_min'] = rand(0,100);
         $planet['temp_max'] = $planet['temp_min'] + 40;
      }
      elseif($Position == 4 || $Position == 5 || $Position == 6)
      {
         $PlanetType = array('dschjungel');
         $PlanetClass = array('planet');
         $PlanetDesign = array('01','02','03','04','05','06','07','08','09','10');
         $planet['temp_min'] = rand(-25,75);
         $planet['temp_max'] = $planet['temp_min'] + 40;
      }
      elseif($Position == 7 || $Position == 8 || $Position == 9)
      {
         $PlanetType = array('normaltemp');
         $PlanetClass = array('planet');
         $PlanetDesign = array('01','02','03','04','05','06','07');
         $planet['temp_min'] = rand(-50,50);
         $planet['temp_max'] = $planet['temp_min'] + 40;
      }
      elseif($Position == 10 || $Position == 11 || $Position == 12)
      {
         $PlanetType = array('wasser');
         $PlanetClass = array('planet');
         $PlanetDesign = array('01','02','03','04','05','06','07','08','09');
         $planet['temp_min'] = rand(-75,25);
         $planet['temp_max'] = $planet['temp_min'] + 40;
      }
      elseif($Position == 13 || $Position == 14 || $Position == 15)
      {
         $PlanetType = array('eis');
         $PlanetClass = array('planet');
         $PlanetDesign = array('01','02','03','04','05','06','07','08','09','10');
         $planet['temp_min'] = rand(-100,10);
         $planet['temp_max'] = $planet['temp_min'] + 40;
      }
      else
      {
         $PlanetType = array('dschjungel','gas','normaltemp','trocken','wasser','wuesten','eis');
         $PlanetClass = array('planet');
         $PlanetDesign = array('01','02','03','04','05','06','07','08','09','10','00');
         $planet['temp_min'] = rand(-120,10);
         $planet['temp_max'] = $planet['temp_min'] + 40;
      }

      $planet['image'] = $PlanetType[rand(0,count($PlanetType) - 1)];
      $planet['image'] .= $PlanetClass[rand(0,count($PlanetClass) - 1)];
      $planet['image'] .= $PlanetDesign[rand(0,count($PlanetDesign) - 1)];
      $planet['planet_type'] = 1;
      $planet['id_owner'] = $PlanetOwnerID;
      $planet['last_update'] = time();
      $planet['name'] = ($PlanetName == '') ? $lang['sys_colo_defaultname'] : $PlanetName;

      $QryInsertPlanet = "INSERT INTO {{table}} SET ";

      if($HomeWorld == FALSE)
         $QryInsertPlanet .= "`name` = '" . $lang['fcp_colony'] . "', ";

      $QryInsertPlanet .= "`id_owner` = '" . $planet['id_owner'] . "', ";
      $QryInsertPlanet .= "`id_level` = '" . $user['authlevel'] . "', ";
      $QryInsertPlanet .= "`galaxy` = '" . $planet['galaxy'] . "', ";
      $QryInsertPlanet .= "`system` = '" . $planet['system'] . "', ";
      $QryInsertPlanet .= "`planet` = '" . $planet['planet'] . "', ";
      $QryInsertPlanet .= "`last_update` = '" . $planet['last_update'] . "', ";
      $QryInsertPlanet .= "`planet_type` = '" . $planet['planet_type'] . "', ";
      $QryInsertPlanet .= "`image` = '" . $planet['image'] . "', ";
      $QryInsertPlanet .= "`diameter` = '" . $planet['diameter'] . "', ";
      $QryInsertPlanet .= "`field_max` = '" . $planet['field_max'] . "', ";
      $QryInsertPlanet .= "`temp_min` = '" . $planet['temp_min'] . "', ";
      $QryInsertPlanet .= "`temp_max` = '" . $planet['temp_max'] . "', ";
      $QryInsertPlanet .= "`metal` = '" . $planet['metal'] . "', ";
      $QryInsertPlanet .= "`metal_perhour` = '" . $planet['metal_perhour'] . "', ";
      $QryInsertPlanet .= "`metal_max` = '" . $planet['metal_max'] . "', ";
      $QryInsertPlanet .= "`crystal` = '" . $planet['crystal'] . "', ";
      $QryInsertPlanet .= "`crystal_perhour` = '" . $planet['crystal_perhour'] . "', ";
      $QryInsertPlanet .= "`crystal_max` = '" . $planet['crystal_max'] . "', ";
      $QryInsertPlanet .= "`deuterium` = '" . $planet['deuterium'] . "', ";
      $QryInsertPlanet .= "`deuterium_perhour` = '" . $planet['deuterium_perhour'] . "', ";
      $QryInsertPlanet .= "`deuterium_max` = '" . $planet['deuterium_max'] . "';";
      doquery($QryInsertPlanet,'planets');

      $QrySelectPlanet = "SELECT `id` ";
      $QrySelectPlanet .= "FROM {{table}} ";
      $QrySelectPlanet .= "WHERE ";
      $QrySelectPlanet .= "`galaxy` = '" . $planet['galaxy'] . "' AND ";
      $QrySelectPlanet .= "`system` = '" . $planet['system'] . "' AND ";
      $QrySelectPlanet .= "`planet` = '" . $planet['planet'] . "' AND ";
      $QrySelectPlanet .= "`id_owner` = '" . $planet['id_owner'] . "';";
      $GetPlanetID = doquery($QrySelectPlanet,'planets',TRUE);

      $QrySelectGalaxy = "SELECT * ";
      $QrySelectGalaxy .= "FROM {{table}} ";
      $QrySelectGalaxy .= "WHERE ";
      $QrySelectGalaxy .= "`galaxy` = '" . $planet['galaxy'] . "' AND ";
      $QrySelectGalaxy .= "`system` = '" . $planet['system'] . "' AND ";
      $QrySelectGalaxy .= "`planet` = '" . $planet['planet'] . "';";
      $GetGalaxyID = doquery($QrySelectGalaxy,'galaxy',TRUE);

      if($GetGalaxyID)
      {
         $QryUpdateGalaxy = "UPDATE {{table}} SET ";
         $QryUpdateGalaxy .= "`id_planet` = '" . $GetPlanetID['id'] . "' ";
         $QryUpdateGalaxy .= "WHERE ";
         $QryUpdateGalaxy .= "`galaxy` = '" . $planet['galaxy'] . "' AND ";
         $QryUpdateGalaxy .= "`system` = '" . $planet['system'] . "' AND ";
         $QryUpdateGalaxy .= "`planet` = '" . $planet['planet'] . "';";
         doquery($QryUpdateGalaxy,'galaxy');
      }
      else
      {
         $QryInsertGalaxy = "INSERT INTO {{table}} SET ";
         $QryInsertGalaxy .= "`galaxy` = '" . $planet['galaxy'] . "', ";
         $QryInsertGalaxy .= "`system` = '" . $planet['system'] . "', ";
         $QryInsertGalaxy .= "`planet` = '" . $planet['planet'] . "', ";
         $QryInsertGalaxy .= "`id_planet` = '" . $GetPlanetID['id'] . "';";
         doquery($QryInsertGalaxy,'galaxy');
      }

      $RetValue = TRUE;
   }
   else
   {
      $RetValue = FALSE;
   }

   return $RetValue;
}
?>