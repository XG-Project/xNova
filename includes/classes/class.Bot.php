<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 * @author	shoghicp
 */

function scmp($a, $b)
{
	 mt_srand((double)microtime()*1000000);
	 return mt_rand(-1,1);
}

function UpdateBots()
{
	$now		= time();

	include_once(XN_ROOT.'includes/functions/CheckPlanetBuildingQueue.php');
	include_once(XN_ROOT.'includes/functions/GetBuildingPrice.php');
	include_once(XN_ROOT.'includes/functions/IsElementBuyable.php');
	include_once(XN_ROOT.'includes/functions/SetNextQueueElementOnTop.php');
	include_once(XN_ROOT.'includes/functions/UpdatePlanetBatimentQueueList.php');
	include_once(XN_ROOT.'includes/functions/IsTechnologieAccessible.php');
	include_once(XN_ROOT.'includes/functions/GetElementPrice.php');
	include_once(XN_ROOT.'includes/functions/HandleTechnologieBuild.php');
	include_once(XN_ROOT.'includes/functions/CheckPlanetUsedFields.php');
	include_once(XN_ROOT.'includes/classes/class.FlyingFleetHandler.php');

	if (read_config('log_bots')) $log = "\n\n------------------------------------------\n";
	$allbots		= doquery("SELECT * FROM `{{table}}` WHERE `next_time` < ".$now, 'bots');
	$update_bots	= array();
	$update_users	= array();

	while ($bot = $allbots->fetch_array())
	{
		$user		= doquery("SELECT * FROM `{{table}}` WHERE `id` = '".$bot['user']."'", 'users', TRUE);
		$thebot		= new Bot($user, $bot);
		$thebot->Play();
		if (isset($log)) $log .= $thebot->log;

		/**
		 *	Para calcular la pr?xima actividad, se genera una funci?n que decrece de
		 *	probabilidad casi totalmente en 15 minutos. Luego se calcula la pr?xima
		 *	actividad un poco aleat?riamente, teniendo en cuenta la noche, las horas
		 *	de sue?o y seg?n los minutos que est? conectado.
		 **/

		if (date('H', $now) < 8)
			$max_time			= 28800/(($bot['minutes_per_day'] < 975 ? 15 : $bot['minutes_per_day']-960)/15);
		elseif ($bot['minutes_per_day'] >= 960)
			$max_time			= 60;
		else
			$max_time			= 57600/($bot['minutes_per_day']/15);

		if ($max_time/60 > 15)
		{
			$random			= mt_rand(1,100);

			if ($random <= 30)
				$next_time	= $now + mt_rand(1,120);
			elseif ($random <= 45)
				$next_time	= $now + mt_rand(61,180);
			elseif ($random <= 55)
				$next_time	= $now + mt_rand(121,240);
			elseif ($random <= 62)
				$next_time	= $now + mt_rand(181,300);
			elseif ($random <= 68)
				$next_time	= $now + mt_rand(241,360);
			elseif ($random <= 73)
				$next_time	= $now + mt_rand(301,420);
			elseif ($random <= 81)
				$next_time	= $now + mt_rand(361,540);
			elseif ($random <= 90)
				$next_time	= $now + mt_rand(421,660);
			else
				$next_time	= $now + mt_rand(541,960);
		}

		if (mt_rand(0, 1) OR $max_time/60 <= 15)
		{
			$next_time		= $now+mt_rand($max_time > 120 ? $max_time-60 : 60, $max_time+60);
		}

		if (date('H', $next_time) < 8 && $bot['minutes_per_day'] < 960) $next_time = mktime(8);

		$update_bots[$bot['id']] = array(	'last_time'			=> $now,
											'next_time'			=> $next_time,
											'minutes_per_day'	=> ( ! mt_rand(0, 999)) ? mt_rand(1, 1440) : $bot['minutes_per_day'],
											'last_planet'		=> $thebot->end_planet);

		$update_users[$bot['user']] = array('onlinetime'		=> $now,
											'user_lastip'		=> 'BOT',
											'user_agent'		=> 'BOT');
		unset($thebot);
	}

	if (isset($log))
	{
		$st		= fopen(XN_ROOT."includes/logs/bots.php", "a");
		$log	.= 'Bots actualizados a las '.date('H:i:s - j/n/Y', $now)."\n";
		$log	.= "------------------------------------------";
		fwrite($st, $log);
		fclose($st);
	}
	unset($bot);
	unset($allbots);

	if ( ! empty($update_bots))
	{
		$query_bots	= $query_users = 'UPDATE `{{table}}` SET';
		$bot_ids	= array();
		$user_ids	= array();

		foreach ($update_bots as $id => $values)
		{
			foreach ($values as $field => $value)
			{
				if ( ! isset($bot_fields[$field])) $bot_fields[$field] = ' `'.$field.'` = CASE';
				$bot_fields[$field] .= ' WHEN `id` = '.$id.' THEN \''.$value.'\'';
			}
			$bot_ids[] = $id;
		}

		foreach ($bot_fields as $field => $text)
			$query_bots .= $text.' ELSE `'.$field.'` END,';

		$query_bots = substr($query_bots, 0, -1).' WHERE `id` IN ('.implode(',', $bot_ids).')';

		foreach ($update_users as $id => $values)
		{
			foreach ($values as $field => $value)
			{
				if ( ! isset($user_fields[$field])) $user_fields[$field] = ' `'.$field.'` = CASE';
				$user_fields[$field] .= ' WHEN `id` = '.$id.' THEN \''.$value.'\'';
			}
			$user_ids[] = $id;
		}

		foreach ($user_fields as $field => $text)
			$query_users .= $text.' ELSE `'.$field.'` END,';

		$query_users = substr($query_users, 0, -1).' WHERE `id` IN ('.implode(',', $user_ids).')';

		doquery($query_bots, 'bots');
		doquery($query_users, 'users');
	}

	update_config('bots_last_update', $now);
}

class Bot {

	protected $user;
	protected $Bot;
	protected $CurrentPlanet;
	protected $Database;
	public $log;
	public $end_planet;

	function __construct($user, $bot)
	{
		$this->user = $user;
		$this->Bot = $bot;
		$this->log = read_config('log_bots') ? '' : NULL;
		$this->Database = new BotDatabase(md5($user['id']));
		$this->end_planet = NULL;
	}

	function Play()
	{
		global $resource;

		$this->HandleOwnFleets();

		$iPlanetCount		= doquery("SELECT count(*) AS `total` FROM `{{table}}` WHERE `id_owner` = '".$this->user['id']."' && `planet_type` = '1'", 'planets',TRUE);
		$maxfleet			= doquery("SELECT COUNT(fleet_owner) AS `actcnt` FROM `{{table}}` WHERE `fleet_owner` = '".$this->user['id']."';", 'fleets', TRUE);
		$maxcolofleet		= doquery("SELECT COUNT(fleet_owner) AS `total` FROM `{{table}}` WHERE `fleet_owner` = '".$this->user['id']."' && `fleet_mission` = '7';", 'fleets', TRUE);
		$MaxFlyingFleets	= $maxfleet['actcnt'];
		$MaxFlottes			= $this->user[$resource[108]];
		$planetselected		= FALSE;
		$planetwork			= FALSE;
		$planetquery		= doquery("SELECT * FROM `{{table}}` WHERE `id_owner` = '".$this->user['id']."' && `planet_type` = '1' ORDER BY `id` ASC;",'planets', FALSE);
		while ($this->CurrentPlanet = $planetquery->fetch_assoc())
		{
			if ($planetselected && $this->CurrentPlanet['id_owner'] == $this->user['id'])
			{
				CheckPlanetUsedFields($this->CurrentPlanet);

				if ( ! is_null($this->log)) $this->log .= $this->user['username'].' - Hora: '.date('H:i:s - j/n/Y').' - Planeta: '.$this->CurrentPlanet['name'].' ['.$this->CurrentPlanet['id'].']'."\n";

				$this->BuildStores();

				if (mt_rand(0, 1) OR $this->CurrentPlanet[$resource[4]] <= 5)
					$this->BuildBuildings();
				else
					$this->BuildSpecialBuildings();

				if ($this->CurrentPlanet[$resource[31]] > 0)
					$this->ResearchTechs();

				if (mt_rand(0, 1))
					$this->BuildFleet();
				else
					$this->BuildDefense();

				if ($iPlanetCount['total']	< MAX_PLAYER_PLANETS &&
					$maxcolofleet['total']	< (MAX_PLAYER_PLANETS - $maxcolofleet['total']) &&
					$MaxFlyingFleets		< $MaxFlottes &&
					$this->CurrentPlanet[$resource[208]] >= 1)
				{
					$this->Colonize($iPlanetCount['total']);
				}

				if ($this->CurrentPlanet['id'] == $this->user['id_planet'] && $MaxFlyingFleets < ($MaxFlottes + 1))
					$this->HandleOtherFleets();
				elseif ($MaxFlyingFleets < $MaxFlottes)
					$this->GetFleet();

				$this->Update();
				$planetselected = FALSE;
				$planetwork = TRUE;
				$planetid = $this->CurrentPlanet['id'];
			}
			elseif ($this->CurrentPlanet['id'] == $this->Bot['last_planet'])
			{
				$planetselected = TRUE;
			}
		}
		if ( ! $planetwork)
		{
				$this->CurrentPlanet = doquery("SELECT * FROM `{{table}}` WHERE `id` = '".$this->user['id_planet']."';",'planets', TRUE);
				CheckPlanetUsedFields($this->CurrentPlanet);

				if ( ! is_null($this->log)) $this->log .= $this->user['username'].' - Hora: '.date('H:i:s - j/n/Y').' - Planeta: '.$this->CurrentPlanet['name'].' ['.$this->CurrentPlanet['id'].']'."\n";

				$this->BuildStores();

				if (mt_rand(0, 1) OR $this->CurrentPlanet[$resource[4]] <= 5)
					$this->BuildBuildings();
				else
					$this->BuildSpecialBuildings();

				if ($this->CurrentPlanet[$resource[31]] > 0)
					$this->ResearchTechs();

				if (mt_rand(0, 1))
					$this->BuildFleet();
				else
					$this->BuildDefense();

				if ($iPlanetCount['total'] < MAX_PLAYER_PLANETS &&
					$maxcolofleet['total'] < (MAX_PLAYER_PLANETS - $maxcolofleet['total']) &&
					$MaxFlyingFleets < $MaxFlottes && $this->CurrentPlanet[$resource[208]] >= 1)
				{
					$this->Colonize($iPlanetCount['total']);
				}

				if ($this->CurrentPlanet['id'] == $this->user['id_planet'] && $MaxFlyingFleets < ($MaxFlottes + 1))
					$this->HandleOtherFleets();
				elseif ($MaxFlyingFleets < $MaxFlottes)
					$this->GetFleet();

				$this->Update();
				$planetid = $this->user['id_planet'];
		}
		$this->end_planet = $planetid;
	}

	protected function BuildBuildings()
	{
		global $resource, $lang, $CurrentUser;

		$CurrentQueue  = $this->CurrentPlanet['b_building_id'];
		if ($CurrentQueue) {
			$QueueArray    = explode(";", $CurrentQueue);
			$ActualCount   = count($QueueArray);
		} else {
			$QueueArray    = "";
			$ActualCount   = 0;
		}
		if (($this->CurrentPlanet['energy_max'] == 0 &&
			$this->CurrentPlanet['energy_used'] > 0) OR $CurrentUser['urlaubs_modus'] == 1) {
			$production_level = 0;
		} elseif ($this->CurrentPlanet['energy_max']  > 0 &&
			abs($this->CurrentPlanet['energy_used']) > $this->CurrentPlanet['energy_max']) {
			$production_level = floor(($this->CurrentPlanet['energy_max']) / $this->CurrentPlanet['energy_used'] * 100);
		} elseif ($this->CurrentPlanet['energy_max'] == 0 &&
			abs($this->CurrentPlanet['energy_used']) > $this->CurrentPlanet['energy_max']) {
			$production_level = 0;
		} else {
			$production_level = 100;
		}
		if ($production_level > 100) {
			$production_level = 100;
		}
		$production_level = abs($production_level);
		$MaxBuildings = array(1 => 60, 2 => 62, 3 => 65);
		if ($production_level < 100)
		{
			if ($ActualCount <= 0 &&
				IsElementBuyable($this->user, $this->CurrentPlanet, 4, TRUE, FALSE) &&
				$this->CurrentPlanet["field_current"] < (CalculateMaxPlanetFields($this->CurrentPlanet)))
			{
				$this->AddBuildingToQueue($this->CurrentPlanet, $this->user, 4, TRUE);
				if ( ! is_null($this->log)) $this->log .= '	Construir: '.$lang['tech'][4].' al nivel '.($this->CurrentPlanet[$resource[4]] + 1)."\n";
			}
		}else{
			mt_srand(time());
			$Element = mt_rand(1, 3);
			if ($this->CurrentPlanet[$resource[$Element]] < $MaxBuildings[$Element])
			{
				if ($ActualCount <= 0 &&
					IsElementBuyable($this->user, $this->CurrentPlanet, $Element, TRUE, FALSE) &&
					$this->CurrentPlanet["field_current"] < (CalculateMaxPlanetFields($this->CurrentPlanet)))
				{
					$this->AddBuildingToQueue($this->CurrentPlanet, $this->user, $Element, TRUE);
					if ( ! is_null($this->log)) $this->log .= '	Construir: '.$lang['tech'][$Element].' al nivel '.($this->CurrentPlanet[$resource[$Element]] + 1)."\n";
				}
			}
		}
		SetNextQueueElementOnTop($this->CurrentPlanet, $this->user);
		$this->SavePlanetRecord();
	}

	protected function BuildSpecialBuildings()
	{
		global $resource, $lang;

		$CurrentQueue  = $this->CurrentPlanet['b_building_id'];
		if ($CurrentQueue) {
			$QueueArray    = explode(";", $CurrentQueue);
			$ActualCount   = count($QueueArray);
		} else {
			$QueueArray    = "";
			$ActualCount   = 0;
		}
		$MaxBuildings = array(/*33 => 100, */ 14 => 20, 15 => 10, 21 => 17, 31 => 16);
			uasort($MaxBuildings, 'scmp');

		foreach ($MaxBuildings as $Element => $Max)
		{
			if ($this->CurrentPlanet[$resource[$Element]] < $MaxBuildings[$Element] && $Element)
			{
				if ($ActualCount <= 0 &&
					IsTechnologieAccessible($this->user, $this->CurrentPlanet, $Element) &&
					IsElementBuyable ($this->user, $this->CurrentPlanet, $Element, TRUE, FALSE) &&
					$this->CurrentPlanet["field_current"] < (CalculateMaxPlanetFields($this->CurrentPlanet)))
				{
					$this->AddBuildingToQueue($this->CurrentPlanet, $this->user, $Element, TRUE);
					if ( ! is_null($this->log)) $this->log .= '	Construir: '.$lang['tech'][$Element].' al nivel '.($this->CurrentPlanet[$resource[$Element]] + 1)."\n";
					break;
				}
			}

		}

		SetNextQueueElementOnTop($this->CurrentPlanet, $this->user);
		$this->SavePlanetRecord();
	}

	protected function BuildStores()
	{
		global $resource, $lang;

		$CurrentQueue  = $this->CurrentPlanet['b_building_id'];
		if ($CurrentQueue) {
			$QueueArray    = explode(";", $CurrentQueue);
			$ActualCount   = count($QueueArray);
		} else {
			$QueueArray    = "";
			$ActualCount   = 0;
		}

		$StoreLevel = array(22 => 20, 23 => 20, 24 => 20);

		foreach ($StoreLevel as $Element => $Max){

			if ($Element === 22)
			{
				if ($ActualCount <= 0 &&
					$this->CurrentPlanet[$resource[$Element]] < $Max &&
					$this->CurrentPlanet['metal'] >= $this->CurrentPlanet['metal_max'] &&
					$Queue2['lenght'] < 2 &&
					IsElementBuyable($this->user, $this->CurrentPlanet, $Element, TRUE, FALSE) &&
					$this->CurrentPlanet["field_current"] < (CalculateMaxPlanetFields($this->CurrentPlanet)))
				{
						$this->AddBuildingToQueue($this->CurrentPlanet, $this->user, $Element, TRUE);
						$ActualCount++;
						if ( ! is_null($this->log)) $this->log .= '	Construir: '.$lang['tech'][$Element].' al nivel '.($this->CurrentPlanet[$resource[$Element]] + 1)."\n";
				}
			}
			elseif ($Element === 23)
			{
				if ($ActualCount <= 0 &&
					$this->CurrentPlanet[$resource[$Element]] < $Max &&
					$this->CurrentPlanet['crystal'] >= $this->CurrentPlanet['crystal_max'] &&
					$Queue2['lenght'] < 2 &&
					IsElementBuyable($this->user, $this->CurrentPlanet, $Element, TRUE, FALSE) &&
					$this->CurrentPlanet["field_current"] < (CalculateMaxPlanetFields($this->CurrentPlanet)))
				{
						$this->AddBuildingToQueue($this->CurrentPlanet, $this->user, $Element, TRUE);
						$ActualCount++;
						if ( ! is_null($this->log)) $this->log .= '	Construir: '.$lang['tech'][$Element].' al nivel '.($this->CurrentPlanet[$resource[$Element]] + 1)."\n";
				}
			}
			elseif ($Element === 24)
			{
				if ($ActualCount <= 0 &&
					$this->CurrentPlanet[$resource[$Element]] < $Max &&
					$this->CurrentPlanet['deuterium'] >= $this->CurrentPlanet['deuterium_max'] &&
					$Queue2['lenght'] < 2 &&
					IsElementBuyable($this->user, $this->CurrentPlanet, $Element, TRUE, FALSE) &&
					$this->CurrentPlanet["field_current"] < (CalculateMaxPlanetFields($this->CurrentPlanet)))
				{
						$this->AddBuildingToQueue($this->CurrentPlanet, $this->user, $Element, TRUE);
						$ActualCount++;
						if ( ! is_null($this->log)) $this->log .= '	Construir: '.$lang['tech'][$Element].' al nivel '.($this->CurrentPlanet[$resource[$Element]] + 1)."\n";
				}
			}
		}
		SetNextQueueElementOnTop($this->CurrentPlanet, $this->user);
		$this->SavePlanetRecord();
	}

	protected function ResearchTechs()
	{
		global $resource, $lang;

		if ($this->CheckLabSettingsInQueue ($this->CurrentPlanet))
		{
			$TechLevel =  array(122 => 5, 114 => 9, 118 => 11, 109 => 20, 108 => 20, 113 => 12, 115 => 8, 117 => 8, 124 => 3, 120 => 12, 106 => 12, 111 => 4, 110 => 20, 121 => 7, 199 => 1);

			uasort($TechLevel, 'scmp');

			foreach ($TechLevel as $Techno => $Max)
			{
				if ($Techno&& $this->user["b_tech_planet"] == 0 && $this->user[$resource[$Techno]] < $Max && IsElementBuyable($this->user, $this->CurrentPlanet, $Techno) && IsTechnologieAccessible($this->user, $this->CurrentPlanet, $Techno))
				{
					$this->Research($Techno);

					if ( ! is_null($this->log)) $this->log .= '	Investigar: '.$lang['tech'][$Techno].' al nivel '.($this->CurrentPlanet[$resource[$Techno]] + 1)."\n";
					break;
				}
			}
		}
	}

	protected function Research($Techno)
	{
		if (IsTechnologieAccessible($this->user, $this->CurrentPlanet, $Techno) && IsElementBuyable($this->user, $this->CurrentPlanet, $Techno))
		{
			$costs                        = GetBuildingPrice($this->user, $this->CurrentPlanet, $Techno);
			$this->CurrentPlanet['metal']      -= $costs['metal'];
			$this->CurrentPlanet['crystal']    -= $costs['crystal'];
			$this->CurrentPlanet['deuterium']  -= $costs['deuterium'];
			$this->CurrentPlanet["b_tech_id"]   = $Techno;
			$this->CurrentPlanet["b_tech"]      = time() + GetBuildingTime($this->user, $this->CurrentPlanet, $Techno);
			$this->user["b_tech_planet"] = $this->CurrentPlanet["id"];

			$QryUpdatePlanet  = "UPDATE `{{table}}` SET ";
			$QryUpdatePlanet .= "`b_tech_id` = '".  $this->CurrentPlanet['b_tech_id']  ."', ";
			$QryUpdatePlanet .= "`b_tech` = '".     $this->CurrentPlanet['b_tech']     ."' ";
			$QryUpdatePlanet .= "WHERE ";
			$QryUpdatePlanet .= "`id` = '".         $this->CurrentPlanet['id']         ."';";
			doquery($QryUpdatePlanet, 'planets');

			$QryUpdateUser  = "UPDATE `{{table}}` SET ";
			$QryUpdateUser .= "`b_tech_planet` = '".$this->user['b_tech_planet']."' ";
			$QryUpdateUser .= "WHERE ";
			$QryUpdateUser .= "`id` = '".           $this->user['id']           ."';";
			doquery($QryUpdateUser, 'users');
		}
	}

	protected function BuildFleet()
	{
		global $resource;

		$FleetLevel =  array(202 => 200, 203 => 150, 204 => 345, 205 => 100, 206 => 30, 207 => 500, 208 => 1, 209 => 500, 210 => 20, 211 => 200, 212 => 300, 213 => 100, 214 => 50, 215 => 150);

		uasort($FleetLevel, 'scmp');
		foreach ($FleetLevel as $Element => $Max)
		{
			if ($Element === 0 OR $Element == 212 OR $Element == 218) continue;

			$MaxElements   = $this->GetMaxConstructibleElements($Element, $this->CurrentPlanet);
			$Count = $MaxElements;
			if ($Count > ($Max * $this->CurrentPlanet[$resource[21]]))
				$Count = ($Max * $this->CurrentPlanet[$resource[21]]);

			$Value = (1 + pow(10, 2) - pow($this->CurrentPlanet[$resource[21]], 2));
			if ($Value > 0)
				$Count = ceil($Count / $Value);
			else
				$Count = ceil($Count * $Value);

			if (IsElementBuyable($this->user, $this->CurrentPlanet, $Element) &&
				IsTechnologieAccessible($this->user, $this->CurrentPlanet, $Element))
			{
				$this->HangarBuild($Element, $Count);
			}
		}
	}

	protected function BuildDefense()
	{
		global $resource;

		$DefLevel =  array(401 => 150,402 => 150, 403 => 90, 403 => 110,404 => 70,  406 => 50 /*, 407 => 1, 408 => 1 */);

		uasort($DefLevel, 'scmp');
		foreach ($DefLevel as $Element => $Max)
		{
			if ($Element == 0) continue;

			$MaxElements   = $this->GetMaxConstructibleElements($Element, $this->CurrentPlanet);

			$Count = $MaxElements;
			if ($Count > ($Max * $this->CurrentPlanet[$resource[21]])) {
				$Count = ($Max * $this->CurrentPlanet[$resource[21]]);
			}
			$Value = (1 + pow(10, 2) - pow($this->CurrentPlanet[$resource[21]], 2));
			if ($Value > 0){
				$Count = ceil($Count / $Value);
			}else{
				$Count = ceil($Count * $Value);
			}
			if (IsElementBuyable($this->user, $this->CurrentPlanet, $Element) and IsTechnologieAccessible($this->user, $this->CurrentPlanet, $Element)){
				$this->HangarBuild($Element, $Count);

			}
		}
	}

	protected function HangarBuild($Element, $Count)
	{
		global $resource, $lang;

		$Ressource = $this->GetElementRessources($Element, $Count);

		$BuildTime = GetBuildingTime($this->user, $this->CurrentPlanet, $Element, 1);
		if (($Count >= 1 and $this->CurrentPlanet['b_hangar_id'] == ""))
		{
			$this->CurrentPlanet['metal']           -= $Ressource['metal'];
			$this->CurrentPlanet['crystal']         -= $Ressource['crystal'];
			$this->CurrentPlanet['deuterium']       -= $Ressource['deuterium'];
			$this->CurrentPlanet['b_hangar_id']     .= "".$Element.",".$Count.";";

			if ( ! is_null($this->log)) $this->log .= '	Crear: '.$Count.' '.$lang['tech'][$Element]."\n";
		}
	}

	protected function HandleOwnFleets()
	{
		$_fleets = doquery("SELECT * FROM `{{table}}` WHERE `fleet_start_time` <= '".time()."';", 'fleets');
		while ($row = $_fleets->fetch_array())
		{
			//Actualizar solo flotas que afecten al jugador actual
			//TODO Hacer la comprobaci?n en la propia query
			if ($row['fleet_owner'] == $this->user['id'] OR $row['fleet_target_owner'] == $this->user['id'])
			{
				$array                = array();
				$array['galaxy']      = $row['fleet_start_galaxy'];
				$array['system']      = $row['fleet_start_system'];
				$array['planet']      = $row['fleet_start_planet'];

				if ($row['fleet_end_time'] <= time())
					$array['planet_type'] = $row['fleet_end_type'];
				else
					$array['planet_type'] = $row['fleet_start_type'];

				$fleet = new FlyingFleetHandler($array);
				unset($fleet);
				unset($array);
			}
			unset($row);
		}
		unset($_fleets);
	}

	protected function HandleOtherFleets()
	{
		global $resource, $reslist, $pricelist;

		$_fleets = doquery("SELECT * FROM `{{table}}` WHERE `fleet_owner` != '".$this->user['id']."' && `fleet_target_owner` = '".$this->user['id']."' && `fleet_end_time` <= ".time().";", 'fleets');
		while ($row = $_fleets->fetch_array())
		{
			//Actualizar solo flotas que afecten al jugador actual
			if (($row['fleet_mission'] == 1 or $row['fleet_mission'] == 2 or $row['fleet_mission'] == 9) and ($row['fleet_end_galaxy'] == $this->CurrentPlanet['galaxy'] and $row['fleet_end_system'] == $this->CurrentPlanet['system'] and $row['fleet_end_planet'] == $this->CurrentPlanet['planet'])){
				$array                = array();
				$array['galaxy']      = $row['fleet_start_galaxy'];
				$array['system']      = $row['fleet_start_system'];
				$array['planet']      = $row['fleet_start_planet'];
				if ($row['fleet_start_time'] <= time()){
					$array['planet_type'] = $row['fleet_start_type'];
				}else{
					$array['planet_type'] = $row['fleet_end_type'];
				}

				$fleet = new FlyingFleetHandler ($array);
				unset($fleet);
				unset($array);
				$planet = $this->user['planet'];
				$system = $this->user['system'];
				$galaxy = $this->user['galaxy'];
				$fleetarray = array();
				$totalships = 0;

				foreach ($reslist['fleet'] as $Element)
				{
					if ($Element != 212 && $this->CurrentPlanet[$resource[$Element]])
					{
						$fleetarray[$Element] = $this->CurrentPlanet[$resource[$Element]];
						$totalships += $this->CurrentPlanet[$resource[$Element]];
					}
				}

				if ($totalships > 0)
				{
					$AllFleetSpeed  = Fleets::fleet_max_speed($fleetarray, 0, $this->user);
					$MaxFleetSpeed  = min($AllFleetSpeed);
					$distance      = Fleets::target_distance($this->CurrentPlanet['galaxy'], $galaxy, $this->CurrentPlanet['system'], $system, $this->CurrentPlanet['planet'], $planet);
					$duration      = Fleets::mission_duration(1, $MaxFleetSpeed, $distance, GetGameSpeedFactor ());
					$consumption   = Fleets::fleet_consumption($fleetarray, GetGameSpeedFactor (), $duration, $distance, $MaxFleetSpeed, $this->user);
					$StayDuration    = 0;
					$StayTime        = 0;
					$fleet['start_time'] = $duration + time();
					$fleet['end_time']   = $StayDuration + (2 * $duration) + time();
					$FleetStorage        = 0;
					$fleet_array2 = '';
					$FleetShipCount      = 0;
					$FleetSubQRY         = "";

					foreach ($fleetarray as $Ship => $Count)
					{
						$FleetStorage    += $pricelist[$Ship]["capacity"] * $Count;
						$FleetShipCount  += $Count;
						$fleet_array2     .= $Ship.",".$Count.";";
						$FleetSubQRY     .= "`".$resource[$Ship]."` = `".$resource[$Ship]."` - ".$Count.", ";
					}

					$FleetStorage        -= $consumption;

					if (($this->CurrentPlanet['metal']) > ($FleetStorage / 3))
					{
						$Mining['metal']   = $FleetStorage / 3;
						$FleetStorage      = $FleetStorage - $Mining['metal'];
					}
					else
					{
						$Mining['metal']   = $this->CurrentPlanet['metal'];
						$FleetStorage      = $FleetStorage - $Mining['metal'];
					}

					if (($this->CurrentPlanet['crystal']) > ($FleetStorage / 2))
					{
						$Mining['crystal'] = $FleetStorage / 2;
						$FleetStorage      = $FleetStorage - $Mining['crystal'];
					}
					else
					{
						$Mining['crystal'] = $this->CurrentPlanet['crystal'];
						$FleetStorage      = $FleetStorage - $Mining['crystal'];
					}

					if (($this->CurrentPlanet['deuterium']) > $FleetStorage)
					{
						$Mining['deuterium']  = $FleetStorage;
						$FleetStorage      = $FleetStorage - $Mining['deuterium'];
					}
					else
					{
						$Mining['deuterium']  = $this->CurrentPlanet['deuterium'];
						$FleetStorage      = $FleetStorage - $Mining['deuterium'];
					}

					$QryInsertFleet  = "INSERT INTO `{{table}}` SET ";
					$QryInsertFleet .= "`fleet_owner` = '".$this->user['id']."', ";
					$QryInsertFleet .= "`fleet_mission` = '4', ";
					$QryInsertFleet .= "`fleet_amount` = '".$FleetShipCount."', ";
					$QryInsertFleet .= "`fleet_array` = '".$fleet_array2."', ";
					$QryInsertFleet .= "`fleet_start_time` = '".$fleet['start_time']."', ";
					$QryInsertFleet .= "`fleet_start_galaxy` = '".$this->CurrentPlanet['galaxy']."', ";
					$QryInsertFleet .= "`fleet_start_system` = '".$this->CurrentPlanet['system']."', ";
					$QryInsertFleet .= "`fleet_start_planet` = '".$this->CurrentPlanet['planet']."', ";
					$QryInsertFleet .= "`fleet_start_type` = '".$this->CurrentPlanet['planet_type']."', ";
					$QryInsertFleet .= "`fleet_end_time` = '".$fleet['end_time']."', ";
					$QryInsertFleet .= "`fleet_end_stay` = '".$StayTime."', ";
					$QryInsertFleet .= "`fleet_end_galaxy` = '".$galaxy."', ";
					$QryInsertFleet .= "`fleet_end_system` = '".$system."', ";
					$QryInsertFleet .= "`fleet_end_planet` = '".$planet."', ";
					$QryInsertFleet .= "`fleet_end_type` = '1', ";
					$QryInsertFleet .= "`fleet_resource_metal` = '".$Mining['metal']."', ";
					$QryInsertFleet .= "`fleet_resource_crystal` = '".$Mining['crystal']."', ";
					$QryInsertFleet .= "`fleet_resource_deuterium` = '".$Mining['deuterium']."', ";
					$QryInsertFleet .= "`fleet_target_owner` = '0', ";
					$QryInsertFleet .= "`fleet_group` = '0', ";
					$QryInsertFleet .= "`start_time` = '".time()."';";
					doquery($QryInsertFleet, 'fleets');
					$QryUpdatePlanet  = "UPDATE `{{table}}` SET ";
					$QryUpdatePlanet .= $FleetSubQRY;
					$QryUpdatePlanet .= "`id` = '".$this->CurrentPlanet['id']."' ";
					$QryUpdatePlanet .= "WHERE ";
					$QryUpdatePlanet .= "`id` = '".$this->CurrentPlanet['id']."'";
					doquery("LOCK TABLE `{{table}}` WRITE", 'planets');
					doquery($QryUpdatePlanet, "planets");
					doquery("UNLOCK TABLES", '');
					$this->CurrentPlanet["metal"]  -= $Mining['metal'];
					$this->CurrentPlanet["crystal"]  -= $Mining['crystal'];
					$this->CurrentPlanet["deuterium"]  -= $consumption + $Mining['deuterium'];
				}
			}
			unset($row);
		}
		unset($_fleets);
	}

	protected function Colonize($iPlanetCount)
	{
		global $resource, $pricelist;

		if ($iPlanetCount >= 4)
		{
			$planet = mt_rand(1, MAX_PLANET_IN_SYSTEM);
			$system = mt_rand(1, MAX_SYSTEM_IN_GALAXY);
			$galaxy = mt_rand(1, MAX_GALAXY_IN_WORLD);
		}
		else
		{
			$planet = mt_rand(1, MAX_PLANET_IN_SYSTEM);
			$system = mt_rand(($this->CurrentPlanet['system'] - 2), ($this->CurrentPlanet['system'] + 2));
			$galaxy = $this->CurrentPlanet['galaxy'];
		}

		$Colo = doquery("SELECT count(*) AS `total` FROM `{{table}}` WHERE `galaxy` = '".$galaxy."' && `system` = '".$system."' && `planet` = '".$planet."' && `planet_type` = '1';", 'planets', TRUE);

		if ($Colo['total'] == 0)
		{
			$fleetarray         = array(208 => 1);
			$AllFleetSpeed  = Fleets::fleet_max_speed($fleetarray, 0, $this->user);
			$MaxFleetSpeed  = min($AllFleetSpeed);
			$distance      = Fleets::target_distance($this->CurrentPlanet['galaxy'], $galaxy, $this->CurrentPlanet['system'], $system, $this->CurrentPlanet['planet'], $planet);
			$duration      = Fleets::mission_duration(10, $MaxFleetSpeed, $distance, GetGameSpeedFactor ());
			$consumption   = Fleets::fleet_consumption($fleetarray, GetGameSpeedFactor (), $duration, $distance, $MaxFleetSpeed, $this->user);
			$StayDuration    = 0;
			$StayTime        = 0;
			$fleet['start_time'] = $duration + time();
			$fleet['end_time']   = $StayDuration + (2 * $duration) + time();
			$FleetStorage        = 0;
			$fleet_array2 = '';
			$FleetShipCount      = 0;
			$FleetSubQRY         = "";

			foreach ($fleetarray as $Ship => $Count)
			{
				$FleetStorage    += $pricelist[$Ship]["capacity"] * $Count;
				$FleetShipCount  += $Count;
				$fleet_array2     .= $Ship.",".$Count.";";
				$FleetSubQRY     .= "`".$resource[$Ship]."` = `".$resource[$Ship]."` - ".$Count.", ";
			}

			$QryInsertFleet  = "INSERT INTO `{{table}}` SET ";
			$QryInsertFleet .= "`fleet_owner` = '".$this->user['id']."', ";
			$QryInsertFleet .= "`fleet_mission` = '7', ";
			$QryInsertFleet .= "`fleet_amount` = '".$FleetShipCount."', ";
			$QryInsertFleet .= "`fleet_array` = '".$fleet_array2."', ";
			$QryInsertFleet .= "`fleet_start_time` = '".$fleet['start_time']."', ";
			$QryInsertFleet .= "`fleet_start_galaxy` = '".$this->CurrentPlanet['galaxy']."', ";
			$QryInsertFleet .= "`fleet_start_system` = '".$this->CurrentPlanet['system']."', ";
			$QryInsertFleet .= "`fleet_start_planet` = '".$this->CurrentPlanet['planet']."', ";
			$QryInsertFleet .= "`fleet_start_type` = '".$this->CurrentPlanet['planet_type']."', ";
			$QryInsertFleet .= "`fleet_end_time` = '".$fleet['end_time']."', ";
			$QryInsertFleet .= "`fleet_end_stay` = '".$StayTime."', ";
			$QryInsertFleet .= "`fleet_end_galaxy` = '".$galaxy."', ";
			$QryInsertFleet .= "`fleet_end_system` = '".$system."', ";
			$QryInsertFleet .= "`fleet_end_planet` = '".$planet."', ";
			$QryInsertFleet .= "`fleet_end_type` = '1', ";
			$QryInsertFleet .= "`fleet_resource_metal` = '0', ";
			$QryInsertFleet .= "`fleet_resource_crystal` = '0', ";
			$QryInsertFleet .= "`fleet_resource_deuterium` = '0', ";
			$QryInsertFleet .= "`fleet_target_owner` = '0', ";
			$QryInsertFleet .= "`fleet_group` = '0', ";
			$QryInsertFleet .= "`start_time` = '".time()."';";
			doquery($QryInsertFleet, 'fleets');
			$QryUpdatePlanet  = "UPDATE `{{table}}` SET ";
			$QryUpdatePlanet .= $FleetSubQRY;
			$QryUpdatePlanet .= "`id` = '".$this->CurrentPlanet['id']."' ";
			$QryUpdatePlanet .= "WHERE ";
			$QryUpdatePlanet .= "`id` = '".$this->CurrentPlanet['id']."'";
			doquery($QryUpdatePlanet, "planets");
			$this->CurrentPlanet["deuterium"]  -= $consumption;
		}
		else
		{
			$this->Colonize($iPlanetCount);
		}
	}

	protected function GetFleet()
	{
		global $resource, $reslist, $pricelist;

		$planet = $this->user['planet'];
		$system = $this->user['system'];
		$galaxy = $this->user['galaxy'];
		$fleetarray = array();
		$totalships = 0;

		foreach ($reslist['fleet'] as $Element)
		{
			if ($Element != 212 && $this->CurrentPlanet[$resource[$Element]] > 0)
			{
				$fleetarray[$Element] = $this->CurrentPlanet[$resource[$Element]];
				$totalships += $this->CurrentPlanet[$resource[$Element]];
			}
		}
		if (($this->CurrentPlanet[$resource[21]] <= 5 && $totalships > 150) OR $totalships > 5000)
		{
			$AllFleetSpeed  = Fleets::fleet_max_speed($fleetarray, 0, $this->user);
			$MaxFleetSpeed  = min($AllFleetSpeed);
			$distance      = Fleets::target_distance($this->CurrentPlanet['galaxy'], $galaxy, $this->CurrentPlanet['system'], $system, $this->CurrentPlanet['planet'], $planet);
			$duration      = Fleets::mission_duration(10, $MaxFleetSpeed, $distance, GetGameSpeedFactor ());
			$consumption   = Fleets::fleet_consumption($fleetarray, GetGameSpeedFactor (), $duration, $distance, $MaxFleetSpeed, $this->user);
			$StayDuration    = 0;
			$StayTime        = 0;
			$fleet['start_time'] = $duration + time();
			$fleet['end_time']   = $StayDuration + (2 * $duration) + time();
			$FleetStorage        = 0;
			$fleet_array2 = '';
			$FleetShipCount      = 0;
			$FleetSubQRY         = "";
			$Mining = array();

			foreach ($fleetarray as $Ship => $Count)
			{
				$FleetStorage    += $pricelist[$Ship]["capacity"] * $Count;
				$FleetShipCount  += $Count;
				$fleet_array2     .= $Ship.",".$Count.";";
				$FleetSubQRY     .= "`".$resource[$Ship]."` = `".$resource[$Ship]."` - ".$Count.", ";
			}

			$FleetStorage        -= $consumption;

			if (($this->CurrentPlanet['metal']) > ($FleetStorage / 3))
			{
				$Mining['metal']   = $FleetStorage / 3;
				$FleetStorage      = $FleetStorage - $Mining['metal'];
			}
			else
			{
				$Mining['metal']   = $this->CurrentPlanet['metal'];
				$FleetStorage      = $FleetStorage - $Mining['metal'];
			}
			if (($this->CurrentPlanet['crystal']) > ($FleetStorage / 2))
			{
				$Mining['crystal'] = $FleetStorage / 2;
				$FleetStorage      = $FleetStorage - $Mining['crystal'];
			}
			else
			{
				$Mining['crystal'] = $this->CurrentPlanet['crystal'];
				$FleetStorage      = $FleetStorage - $Mining['crystal'];
			}

			$QryInsertFleet  = "INSERT INTO `{{table}}` SET ";
			$QryInsertFleet .= "`fleet_owner` = '".$this->user['id']."', ";
			$QryInsertFleet .= "`fleet_mission` = '4', ";
			$QryInsertFleet .= "`fleet_amount` = '".$FleetShipCount."', ";
			$QryInsertFleet .= "`fleet_array` = '".$fleet_array2."', ";
			$QryInsertFleet .= "`fleet_start_time` = '".$fleet['start_time']."', ";
			$QryInsertFleet .= "`fleet_start_galaxy` = '".$this->CurrentPlanet['galaxy']."', ";
			$QryInsertFleet .= "`fleet_start_system` = '".$this->CurrentPlanet['system']."', ";
			$QryInsertFleet .= "`fleet_start_planet` = '".$this->CurrentPlanet['planet']."', ";
			$QryInsertFleet .= "`fleet_start_type` = '".$this->CurrentPlanet['planet_type']."', ";
			$QryInsertFleet .= "`fleet_end_time` = '".$fleet['end_time']."', ";
			$QryInsertFleet .= "`fleet_end_stay` = '".$StayTime."', ";
			$QryInsertFleet .= "`fleet_end_galaxy` = '".$galaxy."', ";
			$QryInsertFleet .= "`fleet_end_system` = '".$system."', ";
			$QryInsertFleet .= "`fleet_end_planet` = '".$planet."', ";
			$QryInsertFleet .= "`fleet_end_type` = '1', ";
			$QryInsertFleet .= "`fleet_resource_metal` = '".$Mining['metal']."', ";
			$QryInsertFleet .= "`fleet_resource_crystal` = '".$Mining['crystal']."', ";
			$QryInsertFleet .= "`fleet_resource_deuterium` = '".$Mining['deuterium']."', ";
			$QryInsertFleet .= "`fleet_target_owner` = '0', ";
			$QryInsertFleet .= "`fleet_group` = '0', ";
			$QryInsertFleet .= "`start_time` = '".time()."';";
			doquery($QryInsertFleet, 'fleets');
			$QryUpdatePlanet  = "UPDATE `{{table}}` SET ";
			$QryUpdatePlanet .= $FleetSubQRY;
			$QryUpdatePlanet .= "`id` = '".$this->CurrentPlanet['id']."' ";
			$QryUpdatePlanet .= "WHERE ";
			$QryUpdatePlanet .= "`id` = '".$this->CurrentPlanet['id']."'";
			doquery($QryUpdatePlanet, "planets");
			$this->CurrentPlanet["metal"]  -= $Mining['metal'];
			$this->CurrentPlanet["crystal"]  -= $Mining['crystal'];
			$this->CurrentPlanet["deuterium"]  -= $consumption + $Mining['deuterium'];
		}
	}

	protected function SavePlanetRecord()
	{
		$QryUpdatePlanet  = "UPDATE `{{table}}` SET ";
		$QryUpdatePlanet .= "`b_building_id` = '".$this->CurrentPlanet['b_building_id']."', ";
		$QryUpdatePlanet .= "`b_building` = '".   $this->CurrentPlanet['b_building']   ."' ";
		$QryUpdatePlanet .= "WHERE ";
		$QryUpdatePlanet .= "`id` = '".           $this->CurrentPlanet['id']           ."';";
		doquery($QryUpdatePlanet, 'planets');
	}

	protected function Update()
	{
		//UpdatePlanet($this->CurrentPlanet, $this->user, time(), TRUE);
		UpdatePlanetBatimentQueueList($this->CurrentPlanet, $this->user);
		HandleTechnologieBuild($this->CurrentPlanet, $this->user);
		PlanetResourceUpdate($this->user, $this->CurrentPlanet, time());
	}

	protected function AddBuildingToQueue(&$CurrentPlanet, $CurrentUser, $Element, $AddMode = TRUE)
	{
		global $resource;

		$CurrentQueue  = $CurrentPlanet['b_building_id'];

		$CurrentMaxFields  	= CalculateMaxPlanetFields($CurrentPlanet);
		if ($CurrentQueue)
		{
			$QueueArray    = explode(";", $CurrentQueue);
			$ActualCount   = count($QueueArray);
		}
		else
		{
			$QueueArray    = "";
			$ActualCount   = 0;
		}

		if ($AddMode)
		{
			$BuildMode = 'build';
		}
		else
		{
			$BuildMode = 'destroy';
		}

		if ($ActualCount < MAX_BUILDING_QUEUE_SIZE)
		{
			$QueueID      = $ActualCount + 1;
		}
		else
		{
			$QueueID      = FALSE;
		}

		if ($QueueID)
		{
			if ($QueueID > 1)
			{
				$InArray = 0;
				for ($QueueElement = 0; $QueueElement < $ActualCount; $QueueElement++)
				{
					$QueueSubArray = explode(",", $QueueArray[$QueueElement]);
					if ($QueueSubArray[0] == $Element)
					{
						$InArray++;
					}
				}
			}
			else
			{
				$InArray = 0;
			}

			if ($InArray)
			{
				$ActualLevel  = $CurrentPlanet[$resource[$Element]];
				if ($AddMode)
				{
					$BuildLevel   = $ActualLevel + 1 + $InArray;
					$CurrentPlanet[$resource[$Element]] += $InArray;
					$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
					$CurrentPlanet[$resource[$Element]] -= $InArray;
				}
				else
				{
					$BuildLevel   = $ActualLevel - 1 - $InArray;
					$CurrentPlanet[$resource[$Element]] -= $InArray;
					$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element) / 2;
					$CurrentPlanet[$resource[$Element]] += $InArray;
				}
			}
			else
			{
				$ActualLevel  = $CurrentPlanet[$resource[$Element]];
				if ($AddMode)
				{
					$BuildLevel   = $ActualLevel + 1;
					$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
				}
				else
				{
					$BuildLevel   = $ActualLevel - 1;
					$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element) / 2;
				}
			}

			if ($QueueID == 1)
			{
				$BuildEndTime = time() + $BuildTime;
			}
			else
			{
				$PrevBuild = explode(",", $QueueArray[$ActualCount - 1]);
				$BuildEndTime = $PrevBuild[3] + $BuildTime;
			}

			$QueueArray[$ActualCount]       = $Element.",".$BuildLevel.",".$BuildTime.",".$BuildEndTime.",".$BuildMode;
			$NewQueue                       = implode(";", $QueueArray);
			$CurrentPlanet['b_building_id'] = $NewQueue;
		}
	}

	private function CheckLabSettingsInQueue($CurrentPlanet)
	{
		if ($CurrentPlanet['b_building_id'])
		{
			$CurrentQueue = $CurrentPlanet['b_building_id'];
			if (strpos ($CurrentQueue, ";"))
			{
				// FIX BY LUCKY - IF THE LAB IS IN QUEUE THE USER CANT RESEARCH ANYTHING...
				$QueueArray		= explode(";", $CurrentQueue);

				for ($i = 0; $i < MAX_BUILDING_QUEUE_SIZE; $i++)
				{
					$ListIDArray	= explode(",", $QueueArray[$i]);
					$Element		= (int) $ListIDArray[0];

					if ($Element === 31) break;
				}
				// END - FIX
			}
			else
			{
				$CurrentBuilding = $CurrentQueue;
			}

			return ( ! ($CurrentBuilding === 31 OR (isset($Element) && $Element === 31)));
		}
		else
		{
			$return = TRUE;
		}

		return $return;
	}

	private function GetMaxConstructibleElements($Element, $Ressources)
	{
		global $pricelist;

		if ($pricelist[$Element]['metal'])
		{
			$Buildable        = floor($Ressources["metal"] / $pricelist[$Element]['metal']);
			$MaxElements      = $Buildable;
		}

		if ($pricelist[$Element]['crystal'])
			$Buildable        = floor($Ressources["crystal"] / $pricelist[$Element]['crystal']);

		if ( ! isset($MaxElements))
			$MaxElements      = $Buildable;
		elseif ($MaxElements > $Buildable)
			$MaxElements      = $Buildable;

		if ($pricelist[$Element]['deuterium'])
			$Buildable        = floor($Ressources["deuterium"] / $pricelist[$Element]['deuterium']);

		if ( ! isset($MaxElements))
			$MaxElements      = $Buildable;
		elseif ($MaxElements > $Buildable)
			$MaxElements      = $Buildable;

		if ($pricelist[$Element]['energy'])
			$Buildable        = floor($Ressources["energy_max"] / $pricelist[$Element]['energy']);

		if ($Buildable < 1)
			$MaxElements      = 0;

		return $MaxElements;
	}

	private function GetElementRessources($Element, $Count)
	{
		global $pricelist;

		$ResType['metal']     = ($pricelist[$Element]['metal']     * $Count);
		$ResType['crystal']   = ($pricelist[$Element]['crystal']   * $Count);
		$ResType['deuterium'] = ($pricelist[$Element]['deuterium'] * $Count);

		return $ResType;
	}
}

class BotDatabase {

	private $SQLite;

	function __construct($Database)
	{
		if ( ! file_exists(XN_ROOT.'includes/bots/'.$Database.'.botdb'))
		{
			$this->SQLite = new SQLite3(XN_ROOT.'includes/bots/'.$Database.'.botdb');
			$this->SQLite->query("CREATE TABLE [actions] (
				[id] INTEGER  NOT NULL PRIMARY KEY,
				[function] TEXT  NOT NULL,
				[parameters] TEXT  NOT NULL,
				[priority] FLOAT DEFAULT '1' NOT NULL,
				[time] TIME  NOT NULL
				);

				CREATE TABLE [config] (
				[name] TEXT  UNIQUE NOT NULL PRIMARY KEY,
				[value] TEXT  NULL
				);

				CREATE TABLE [objetives] (
				[id] INTEGER  NOT NULL PRIMARY KEY,
				[id_user] INTEGER  NOT NULL,
				[id_planet] INTEGER  NOT NULL,
				[galaxy] INTEGER  NOT NULL,
				[system] INTEGER  NOT NULL,
				[planet] INTEGER  NOT NULL,
				[planet_type] INTEGER DEFAULT '1' NOT NULL,
				[priority] FLOAT DEFAULT '1' NOT NULL
				);");
		}
		else
		{
			$this->SQLite = new SQLite3(XN_ROOT.'includes/bots/'.$Database.'.botdb');
		}
	}

	function doquery($query, $fetch = FALSE)
	{
		$result = $this->Db->query($query);
		if ($fetch)
		{
			$array = $result->fetch();
			return $array;
		}
		else
		{
			return $result;
		}
	}
}


/* End of file class.Bot.php */
/* Location: ./includes/classes/class.Bot.php */