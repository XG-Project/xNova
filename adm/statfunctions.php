<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

function GetTechnoPoints ( $CurrentUser ) {
	global $resource, $pricelist, $reslist;

	$TechCounts = 0;
	$TechPoints = 0;
	foreach ( $reslist['tech'] as $n => $Techno )
	{
		if ( $CurrentUser[ $resource[ $Techno ] ] > 0 ) {
			for ( $Level = 0; $Level < $CurrentUser[ $resource[ $Techno ] ]; $Level++ ) {
				$Units       = $pricelist[ $Techno ]['metal'] + $pricelist[ $Techno ]['crystal'] + $pricelist[ $Techno ]['deuterium'];
				$LevelMul    = pow( $pricelist[ $Techno ]['factor'], $Level );
				$TechPoints += ($Units * $LevelMul);
				$TechCounts += 1;
			}
		}
	}
	$RetValue['TechCount'] = $TechCounts;
	$RetValue['TechPoint'] = $TechPoints;

	return $RetValue;
}

function GetBuildPoints ( $CurrentPlanet ) {
	global $resource, $pricelist, $reslist;

	$BuildCounts = 0;
	$BuildPoints = 0;
	foreach($reslist['build'] as $n => $Building) {
		if ( $CurrentPlanet[ $resource[ $Building ] ] > 0 )
		{
			for ( $Level = 0; $Level < $CurrentPlanet[ $resource[ $Building ] ]; $Level++ ) {
				$Units        = $pricelist[ $Building ]['metal'] + $pricelist[ $Building ]['crystal'] + $pricelist[ $Building ]['deuterium'];
				$LevelMul     = pow( $pricelist[ $Building ]['factor'], $Level );
				$BuildPoints += ($Units * $LevelMul);
				$BuildCounts += 1;
			}
		}
	}
	$RetValue['BuildCount'] = $BuildCounts;
	$RetValue['BuildPoint'] = $BuildPoints;

	return $RetValue;
}

function GetDefensePoints ( $CurrentPlanet ) {
	global $resource, $pricelist, $reslist;

	$DefenseCounts = 0;
	$DefensePoints = 0;
	foreach($reslist['defense'] as $n => $Defense) {
		if ($CurrentPlanet[ $resource[ $Defense ] ] > 0) {
			$Units          = $pricelist[ $Defense ]['metal'] + $pricelist[ $Defense ]['crystal'] + $pricelist[ $Defense ]['deuterium'];
			$DefensePoints += ($Units * $CurrentPlanet[ $resource[ $Defense ] ]);
			$DefenseCounts += $CurrentPlanet[ $resource[ $Defense ] ];
		}
	}
	$RetValue['DefenseCount'] = $DefenseCounts;
	$RetValue['DefensePoint'] = $DefensePoints;

	return $RetValue;
}

function GetFleetPoints ( $CurrentPlanet ) {
	global $resource, $pricelist, $reslist;

	$FleetCounts = 0;
	$FleetPoints = 0;
	foreach($reslist['fleet'] as $n => $Fleet) {
		if ($CurrentPlanet[ $resource[ $Fleet ] ] > 0) {
			$Units          = $pricelist[ $Fleet ]['metal'] + $pricelist[ $Fleet ]['crystal'] + $pricelist[ $Fleet ]['deuterium'];
			$FleetPoints   += ($Units * $CurrentPlanet[ $resource[ $Fleet ] ]);
			$FleetCounts   += $CurrentPlanet[ $resource[ $Fleet ] ];
		}
	}
	$RetValue['FleetCount'] = $FleetCounts;
	$RetValue['FleetPoint'] = $FleetPoints;

	return $RetValue;
}
// INICIO FIX ACTUALIZACION PUNTOS CON FLOTA VOLANDO
function GetFlyingFleetPoints($fleet_array)
{global $resource, $pricelist, $reslist;
	// PADA FUNCTION
	// USE AT YOUR OWN RISK :3
	$FleetRec     = explode(";", $fleet_array);
	if(is_array($FleetRec))
	{
		foreach($FleetRec as $Item => $Group)
		{
			if ($Group  != '')
			{
				$Ship    = explode(",", $Group);
				$Units         = $pricelist[ $Ship[0] ]['metal'] + $pricelist[ $Ship[0] ]['crystal'] + $pricelist[ $Ship[0] ]['deuterium'];
				$FleetPoints   += ($Units * $Ship[1]);
				$FleetCounts   += $Ship[1];
			}
		}
	}
$RetValue['FleetCount'] = $FleetCounts;
$RetValue['FleetPoint'] = $FleetPoints;
return $RetValue;
}
//FIN FIX ACTUALIZACION PUNTOS CON FLOTA VOLANDO
function MakeStats()
{
	global $resource, $pricelist, $reslist;

	$CheckUserQuery = FALSE;
	$CheckAllyQuery	= FALSE;

	// Initial Time
	$mtime        = microtime();
	$mtime        = explode(" ", $mtime);
	$mtime        = $mtime[1] + $mtime[0];
	$starttime    = $mtime;
	//Initial Memory
	$result['initial_memory']= array(round(memory_get_usage() / 1024,1),round(memory_get_usage(1) / 1024,1));
	//Change the last stats time
	$stats_time   = time();
	//Delete old messages
	$del_before 	= time() - (60 * 60 * 24); // 1 DAY
	$del_inactive 	= time() - (60 * 60 * 24 * 30); // 1 MONTH
	$del_deleted 	= time() - (60 * 60 * 24 * 7); // 1 WEEK

	$ChooseToDelete = doquery("SELECT `id` FROM `{{table}}` WHERE (`db_deaktjava` < '".$del_deleted."' AND `db_deaktjava` <> 0) OR (`onlinetime` < '".$del_inactive."' AND `authlevel` <> 3)", 'users');

	if($ChooseToDelete)
	{
		include_once(XGP_ROOT . 'includes/functions/DeleteSelectedUser.php');

		while($delete = mysql_fetch_array($ChooseToDelete))
		{
			DeleteSelectedUser($delete[id]);
		}
	}

	doquery ("DELETE FROM {{table}} WHERE `message_time` < '". $del_before ."' ;", 'messages');
	doquery ("DELETE FROM {{table}} WHERE `time` < '". $del_before ."' ;", 'rw');
	//STATS FOR USERS....
	//Here we make the select query, with this all the custom stuff with be included
	$select_defenses	=	'';
	foreach($reslist['defense'] as $n => $Defense)
	{
		if ($resource[ $Defense ] != 'small_protection_shield' && $resource[ $Defense ] != 'big_protection_shield')
		{
			$select_defenses	.= " SUM(p.`".$resource[ $Defense ]."`) AS `".$resource[ $Defense ]."`,";
		}
	}
	$select_buildings	=	'';
	foreach($reslist['build'] as $n => $Building)
	{
		$select_buildings	.= " p.`".$resource[ $Building ]."`,";
	}
	$selected_tech	=	'';
	foreach($reslist['tech'] as $n => $Techno)
	{
			$selected_tech	.= " u.`".$resource[ $Techno ]."`,";
	}
	$select_fleets	=	'';
	foreach($reslist['fleet'] as $n => $Fleet)
	{
			$select_fleets	.= " SUM(p.`".$resource[ $Fleet ]."`) AS `".$resource[ $Fleet ]."`,";
	}
	//If you have some data type enmu is better if you put it here, because that data give a error in the SUM function.
	$selected_enum	=	"p.small_protection_shield, p.big_protection_shield";//For now...
	$select_planet		= "p.id_owner,";
	//For Stats table..
	$select_old_ranks	= "id_owner, stat_type,tech_rank AS old_tech_rank, build_rank AS old_build_rank, defs_rank AS old_defs_rank, fleet_rank AS old_fleet_rank, total_rank AS old_total_rank";
	//For users table
	$select_user		= " u.id, u.ally_id, u.authlevel ";
	//We check how many users are for not overload the server...
	$total_users = doquery("SELECT COUNT(*) AS `count` FROM {{table}} WHERE 1;", 'users', TRUE);
	//We will make query every 'stat_amount' users
	//Min amount = 10, if it is less than 10, it is not a good system

	$game_stat_amount	=	read_config ( 'stat_amount' );
	$game_users_amount	=	read_config ( 'users_amount' );
	$game_stat_flying	=	read_config ( 'stat_flying' );
	$game_stat_settings	=	read_config ( 'stat_settings' );
	$game_stat_level	=	read_config ( 'stat_level' );
	$game_stat			=	read_config ( 'stat' );

	$game_stat_amount	= (($game_stat_amount>=10)?$game_stat_amount:10);
	$amount_per_block	= (($game_stat_amount<$game_users_amount)?$game_users_amount:$game_stat_amount);
	if ($total_users['count'] > $amount_per_block)
	{
		$LastQuery = Format::round_up($total_users['count'] / $amount_per_block);
	}
	else
	{
		$LastQuery = 1;
	}

	for ($Query=1;$Query<=$LastQuery;$Query++)
	{
		if ($Query==1)
		{//based on:http://www.desarrolloweb.com/articulos/1035.php
			$start = 0;
		}
		else
		{
			$start = ($Query - 1) * $amount_per_block;
		}
		$minmax_sql	=	'SELECT Max(id) AS `max`, Min(id) AS `min` FROM
						(SELECT id FROM {{table}}users ORDER BY id ASC LIMIT
						'. $start.','. ($amount_per_block) .') AS A';
		$minmax	= doquery($minmax_sql, '',TRUE);
		$sql_parcial = 	'SELECT '.$select_buildings .$select_planet . $selected_enum.', p.id FROM {{table}}planets as p WHERE p.id_owner <='.  $minmax['max'].' AND p.id_owner >= ' .$minmax['min'].';';
		//We delete now the old stats of the users
		$sql_old_stats	=	'SELECT '.$select_old_ranks.' FROM {{table}} WHERE stat_type = 1 AND stat_code = 1 AND id_owner <= '.$minmax['max'].' AND id_owner >=  '.$minmax['min'].';';
		//Here we make the array with the planets buildings array and the user id and planet id for use in the next step...
		//Here we excecute all the querys
		$parcial_data	= doquery($sql_parcial, '');//Here we obtained the stuff that can not be SUM
		while ($CurPlanet = mysql_fetch_assoc($parcial_data))
		{
			$Buildings_array[$CurPlanet['id_owner']][$CurPlanet['id']]	=	$CurPlanet;//We made a array with the content of the query

		}
		unset($CurPlanet, $parcial_data);
		$old_stats		=	 doquery($sql_old_stats, 'statpoints');
		while ($CurStats = mysql_fetch_assoc($old_stats))
		{
			$old_stats_array[$CurStats['id_owner']]	=	$CurStats;

		}
		unset($CurStats, $old_stats);
		//We take the data of flying fleets if stat_flying is =1 in game config
		//If you have trouble with the RAM and CPU usage, please set stat_flying = 0 and a low value of stat_amount (25, 15...)
		if($game_stat_flying == 1)
		{
			$sql_flying_fleets	=	'SELECT fleet_array, fleet_owner, fleet_id FROM {{table}} WHERE fleet_owner <= '. $minmax['max'].' AND fleet_owner >= '. $minmax['min'].';';
			$flying_fleets		=	doquery($sql_flying_fleets, 'fleets');
			while ($CurFleets = mysql_fetch_assoc($flying_fleets))
			{
				$flying_fleets_array[$CurFleets['fleet_owner']][$CurFleets['fleet_id']]	=	$CurFleets['fleet_array'];
			}
			unset($CurFleets, $flying_fleets);
		}
		//This query will have a LOT of data...
		$sql	=	'SELECT  '.$select_planet .$select_defenses .$selected_tech .$select_fleets .$select_user.
					'FROM {{table}}planets as p
					INNER JOIN {{table}}users as u ON u.id = p.id_owner
					WHERE p.id_owner <= '.$minmax['max'].' AND p.id_owner >=  '.$minmax['min'].'
					GROUP BY p.id_owner, u.id, u.authlevel;';
		$total_data	=	doquery ($sql,'');
		unset($sql,$sql_old_stats,$sql_parcial);
		doquery ("DELETE FROM {{table}} WHERE stat_type = 1 AND stat_code = 1 AND id_owner <= ". $minmax['max']." AND id_owner >= ". $minmax['min'].";",'statpoints');
		$insert_user_query	=	"INSERT INTO {{table}}
								(`id_owner`, `id_ally`, `stat_type`, `stat_code`,
								`tech_old_rank`, `tech_points`, `tech_count`,
								`build_old_rank`, `build_points`, `build_count`,
								`defs_old_rank`, `defs_points`, `defs_count`,
								`fleet_old_rank`, `fleet_points`, `fleet_count`,
								`total_old_rank`, `total_points`, `total_count`, `stat_date`) VALUES ";
		//Here we start the update...
		while ($CurUser = mysql_fetch_assoc($total_data))
		{
			$u_OldTotalRank = (($old_stats_array[$CurUser['id']]['old_total_rank'])? $old_stats_array[$CurUser['id']]['old_total_rank']:0);
			$u_OldTechRank  = (($old_stats_array[$CurUser['id']]['old_tech_rank'])? $old_stats_array[$CurUser['id']]['old_tech_rank']:0);
			$u_OldBuildRank = (($old_stats_array[$CurUser['id']]['old_build_rank'])? $old_stats_array[$CurUser['id']]['old_build_rank']:0);
			$u_OldDefsRank  = (($old_stats_array[$CurUser['id']]['old_defs_rank'])? $old_stats_array[$CurUser['id']]['old_defs_rank']:0);
			$u_OldFleetRank = (($old_stats_array[$CurUser['id']]['old_fleet_rank'])? $old_stats_array[$CurUser['id']]['old_fleet_rank']:0);
			//We dont need this anymore...
			unset($old_stats_array[$CurUser['id']]);
			//1 point=  'stat_settings' ressources
			//Make the tech points XD
			$u_points			= GetTechnoPoints ( $CurUser );
			$u_TTechCount		= $u_points['TechCount'];
			$u_TTechPoints	= ($u_points['TechPoint'] / $game_stat_settings);
			//Make the defense points
			$u_points			= GetDefensePoints ( $CurUser );
			$u_TDefsCount		= $u_points['DefenseCount'];
			$u_TDefsPoints	= ($u_points['DefensePoint'] / $game_stat_settings);
			//Make the fleets points (without the flying fleets...
			$u_points			= GetFleetPoints ( $CurUser );
			$u_TFleetCount	= $u_points['FleetCount'];
			$u_TFleetPoints	= ($u_points['FleetPoint'] / $game_stat_settings);
			//Now we add the flying fleets points
			//This is used if($game_stat_flying == 1)
			if($game_stat_flying == 1)
			{
				if($flying_fleets_array[$CurUser['id']])
				{
					foreach($flying_fleets_array[$CurUser['id']] as $fleet_id => $fleet_array)
					{
						$u_points			= GetFlyingFleetPoints ( $fleet_array );
						$u_TFleetCount  	+= $u_points['FleetCount'];
						$u_TFleetPoints 	+= ($u_points['FleetPoint'] / $game_stat_settings);
					}
				}
				//We dont need this anymore...
				unset($flying_fleets_array[$CurUser['id']],$fleet_array,$fleet_id);
			}
			else
			{//We take one query per fleet in flying, with this we increase the time and the querys, but we decrease the cpu load...
				$OwnFleets = doquery("SELECT fleet_array, fleet_id FROM {{table}} WHERE `fleet_owner` = '". $CurUser['id'] ."';", 'fleets');
				while ($FleetRow = mysql_fetch_array($OwnFleets))
				{
						$u_points			= GetFlyingFleetPoints ( $FleetRow['fleet_array'] );
						$u_TFleetCount  	+= $u_points['FleetCount'];
						$u_TFleetPoints 	+= ($u_points['FleetPoint'] / $game_stat_settings);
				}
				//We dont need this anymore...
				unset($OwnFleets, $FleetRow);
			}
			$u_TBuildCount    = 0;
			$u_TBuildPoints   = 0;
			if($Buildings_array[$CurUser['id']])
			{
				foreach($Buildings_array[$CurUser['id']] as $planet_id => $building)
				{
					$u_points				= GetBuildPoints ( $building );
					$u_TBuildCount		+= $u_points['BuildCount'];
					$u_TBuildPoints		+= ($u_points['BuildPoint'] / $game_stat_settings);
					//We add the shields points (this way is a temporary way...)
					$u_points				= GetDefensePoints ( $building );
					$u_TDefsCount			+= $u_points['DefenseCount'];
					$u_TDefsPoints		+= ($u_points['DefensePoint'] / $game_stat_settings);
				}
				//We dont need this anymore...
				unset($Buildings_array[$CurUser['id']],$planet_id,$building);
			}
			else
			{
			//Here we will send a error message....print_r("<br>usuario sin planeta: ". $CurUser['id']);
			}
			$u_GCount			= $u_TDefsCount  + $u_TTechCount  + $u_TFleetCount  + $u_TBuildCount;
			$u_GPoints		= $u_TTechPoints + $u_TDefsPoints + $u_TFleetPoints + $u_TBuildPoints;
			if (($CurUser['authlevel'] >= $game_stat_level&& $game_stat==1 ) || $CurUser['bana']==1)
			{
				$insert_user_query  .= '('.$CurUser['id'].','.$CurUser['ally_id'].',1,1,'.$u_OldTechRank.',
										0,0,'.$u_OldBuildRank.',0,0,'.$u_OldDefsRank.',0,0,'.$u_OldFleetRank.',
										0,0,'.$u_OldTotalRank.',0,0,'.$stats_time.'),' ;
			}
			else
			{
				$insert_user_query  .= '('.$CurUser['id'].','.$CurUser['ally_id'].',1,1,'.$u_OldTechRank.',
										'.$u_TTechPoints.','.$u_TTechCount.','.$u_OldBuildRank.','.$u_TBuildPoints.',
										'.$u_TBuildCount.','.$u_OldDefsRank.','.$u_TDefsPoints.','.$u_TDefsCount.',
										'.$u_OldFleetRank.','.$u_TFleetPoints.','.$u_TFleetCount.','.$u_OldTotalRank.',
										'.$u_GPoints.','.$u_GCount.','.$stats_time.'),' ;
			}
			unset_vars( 'u_' );

			$CheckUserQuery = TRUE;
		}
		//TODO, make a end string check in case that insert_user_query end in VALUE...
		//Here we change the end of the query for ;

		if($CheckUserQuery == TRUE)
		{
			$insert_user_query	=	substr_replace($insert_user_query, ';', -1);
			doquery ( $insert_user_query , 'statpoints');
		}



		unset($insert_user_query, $total_data, $CurUser, $old_stats_array, $Buildings_array, $flying_fleets_array);
	}
	//STATS FOR ALLYS
	//Delet invalid allys
	doquery("DELETE FROM {{table}} WHERE ally_members='0'", "alliance");
	//We create this just for make a check of the ally
	$ally_check  = doquery("SELECT * FROM {{table}}", 'alliance');
	$total_ally		=0;
	while ($CurAlly = mysql_fetch_assoc($ally_check))
	{
		++$total_ally;
	$ally_check_value[$CurAlly['id']]=1;
	}
	unset($ally_check);
	unset($start,$QueryValue,$Query,$LastQuery);
	if ($total_ally > 0)//We only update allys if at least 1 ally exist...
	{
		//Min amount = 10, if it is less than 10, it is not a good system
		$game_stat_amount= (($game_stat_amount>=10)?$game_stat_amount:10);
		$amount_per_block	= (($game_stat_amount<$game_users_amount)?$game_users_amount:$game_stat_amount);
		if ($total_ally > $amount_per_block)
		{
			$LastQuery = Format::round_up($total_ally / $amount_per_block);
		}
		else
		{
			$LastQuery = 1;
		}

		for ($Query=1;$Query<=$LastQuery;$Query++)
		{
			if ($Query==1)
			{//based on:http://www.desarrolloweb.com/articulos/1035.php
				$start = 0;
			}
			else
			{
				$start = ($Query - 1) * $amount_per_block;
			}
			$minmax_sql	=	'SELECT Max(id) AS `max`, Min(id) AS `min` FROM
						(SELECT id FROM {{table}}alliance ORDER BY id ASC LIMIT
						'. $start.','. $amount_per_block.') AS A';
			$minmax	= doquery($minmax_sql, '',TRUE);
			$select_old_a_ranks	=	"s.id_owner , s.stat_type,	s.tech_rank AS old_tech_rank,
								s.build_rank AS old_build_rank, s.defs_rank AS old_defs_rank, s.fleet_rank AS old_fleet_rank,
								s.total_rank AS old_total_rank";
			$select_ally		= " a.id ";
			$sql_ally	=	'SELECT  '.$select_ally.', '.$select_old_a_ranks.'
							FROM {{table}}alliance AS a
							INNER JOIN {{table}}statpoints AS s ON a.id = s.id_owner  AND s.stat_type = 2
							WHERE a.id <= '.$minmax['max'].' AND a.id >=  '.$minmax['min'].'
							ORDER BY a.id;';
			$ally_data	=	doquery ($sql_ally,'');
			$ally_sql_points	='SELECT
							s.stat_type, s.id_ally, Sum(s.tech_points) AS TechPoint,
								Sum(s.tech_count) AS TechCount, Sum(s.build_points) AS BuildPoint,
								Sum(s.build_count) AS BuildCount, Sum(s.defs_points) AS DefsPoint,
								Sum(s.defs_count) AS DefsCount, Sum(s.fleet_points) AS FleetPoint,
								Sum(s.fleet_count) AS FleetCount, Sum(s.total_points) AS TotalPoint,
								Sum(s.total_count) AS TotalCount
								FROM
								{{table}}statpoints AS s
								WHERE	s.stat_type =  1 AND s.id_ally > 0
								AND s.id_ally <= '.$minmax['max'].' AND s.id_ally >=  '.$minmax['min'].'
								GROUP BY	s.id_ally;';
			$ally_points	=	doquery ($ally_sql_points,'');
			//We delete now the old stats of the allys
			doquery ('DELETE FROM {{table}} WHERE `stat_type` = 2 AND id_owner <= '.$minmax['max'].' AND id_owner >=  '.$minmax['min'].';','statpoints');
			while ($CurAlly = mysql_fetch_assoc($ally_data))
			{
				$ally_old_data[$CurAlly['id']]=$CurAlly;
			}
			unset($CurAlly, $ally_data);
			$insert_ally_query	=	"INSERT INTO {{table}}
									(`id_owner`, `id_ally`, `stat_type`, `stat_code`,
									`tech_old_rank`, `tech_points`, `tech_count`,
									`build_old_rank`, `build_points`, `build_count`,
									`defs_old_rank`, `defs_points`, `defs_count`,
									`fleet_old_rank`, `fleet_points`, `fleet_count`,
									`total_old_rank`, `total_points`, `total_count`, `stat_date`) VALUES ";
			while ($CurAlly = mysql_fetch_assoc($ally_points))
			{
				if ($ally_check_value[$CurAlly['id_ally']] == 1)
				{
					$u_OldTotalRank = (($ally_old_data[$CurAlly['id_ally']]['old_total_rank'])? $ally_old_data[$CurAlly['id_ally']]['old_total_rank']:0);
					$u_OldTechRank  = (($ally_old_data[$CurAlly['id_ally']]['old_tech_rank'])? $ally_old_data[$CurAlly['id_ally']]['old_tech_rank']:0);
					$u_OldBuildRank = (($ally_old_data[$CurAlly['id_ally']]['old_build_rank'])? $ally_old_data[$CurAlly['id_ally']]['old_build_rank']:0);
					$u_OldDefsRank  = (($ally_old_data[$CurAlly['id_ally']]['old_defs_rank'])? $ally_old_data[$CurAlly['id_ally']]['old_defs_rank']:0);
					$u_OldFleetRank = (($ally_old_data[$CurAlly['id_ally']]['old_fleet_rank'])? $ally_old_data[$CurAlly['id_ally']]['old_fleet_rank']:0);
					$u_TTechCount     = $CurAlly['TechCount'];
					$u_TTechPoints    = $CurAlly['TechPoint'];
					$u_TBuildCount    = $CurAlly['BuildCount'];
					$u_TBuildPoints   = $CurAlly['BuildPoint'];
					$u_TDefsCount     = $CurAlly['DefsCount'];
					$u_TDefsPoints    = $CurAlly['DefsPoint'];
					$u_TFleetCount    = $CurAlly['FleetCount'];
					$u_TFleetPoints   = $CurAlly['FleetPoint'];
					$u_GCount         = $CurAlly['TotalCount'];
					$u_GPoints        = $CurAlly['TotalPoint'];
					$insert_ally_query  .= '('.$CurAlly['id_ally'].',0,2,1,'.$u_OldTechRank.',
											'.$u_TTechPoints.','.$u_TTechCount.','.$u_OldBuildRank.','.$u_TBuildPoints.',
											'.$u_TBuildCount.','.$u_OldDefsRank.','.$u_TDefsPoints.','.$u_TDefsCount.',
											'.$u_OldFleetRank.','.$u_TFleetPoints.','.$u_TFleetCount.','.$u_OldTotalRank.',
											'.$u_GPoints.','.$u_GCount.','.$stats_time.'),' ;
				unset($CurAlly);
				unset_vars( 'u_' );
				}
				else
				{
					doquery ( "UPDATE {{table}}	SET `ally_id`=0, `ally_name` = '', 	`ally_register_time`= 0, `ally_rank_id`= 0 	WHERE `ally_id`='{$CurAlly['id_ally']}'", "users");
				}

				$CheckAllyQuery	= TRUE;
			}
			//Here we change the end of the query for ;

			if($CheckAllyQuery == TRUE)
			{
				$insert_ally_query	=	substr_replace($insert_ally_query, ';', -1);
				doquery ( $insert_ally_query , 'statpoints');
			}

			unset($insert_ally_query, $ally_old_data, $CurAlly, $ally_points);
		}
		unset($ally_check_value);
		//We update the ranks of the allys
		MakeNewRanks(2);
	}
	//We update the ranks of the users
	MakeNewRanks(1);
	// Calcul de la duree de traitement (calcul)
	$mtime        = microtime();
	$mtime        = explode(" ", $mtime);
	$mtime        = $mtime[1] + $mtime[0];
	$endtime      = $mtime;
	$result['stats_time']	=	$stats_time;
	$result['totaltime']    = ($endtime - $starttime);
	$result['memory_peak']	=	array(round(memory_get_peak_usage() / 1024,1),round(memory_get_peak_usage(1) / 1024,1));
	$result['end_memory']	= array(round(memory_get_usage() / 1024,1),round(memory_get_usage(1) / 1024,1));
	$result['amount_per_block']	=$amount_per_block;
	return $result;
}
//TODO: Find a best way to make this ranks... with a little less querys
function MakeNewRanks($stat_type)
{
	$Rank           = 1;
	$RankQry        = doquery("SELECT `id_owner` FROM {{table}} WHERE `stat_type` = '".$stat_type."' AND `stat_code` = '1' ORDER BY `tech_points` DESC;", 'statpoints');
	while ($CurUser = mysql_fetch_assoc($RankQry) )
	{
		$tech[$CurUser['id_owner']]	=	$Rank;
		$Rank++;
	}
	unset($Rank,$RankQry,$QryUpdateStats,$CurUser);
	$Rank           = 1;
	$RankQry        = doquery("SELECT `id_owner` FROM {{table}} WHERE `stat_type` = '".$stat_type."' AND `stat_code` = '1' ORDER BY `build_points` DESC;", 'statpoints');
	while ($CurUser = mysql_fetch_assoc($RankQry) )
	{
		$build[$CurUser['id_owner']]	=	$Rank;
		$Rank++;
	}
	unset($Rank,$RankQry,$QryUpdateStats,$CurUser);
	$Rank           = 1;
	$RankQry        = doquery("SELECT `id_owner` FROM {{table}} WHERE `stat_type` = '".$stat_type."' AND `stat_code` = '1' ORDER BY `defs_points` DESC;", 'statpoints');
	while ($CurUser = mysql_fetch_assoc($RankQry) )
	{
		$defs[$CurUser['id_owner']]	=	$Rank;
		$Rank++;
	}
	unset($Rank,$RankQry,$QryUpdateStats,$CurUser);
	$Rank           = 1;
	$RankQry        = doquery("SELECT `id_owner` FROM {{table}} WHERE `stat_type` = '".$stat_type."' AND `stat_code` = '1' ORDER BY `fleet_points` DESC;", 'statpoints');
	while ($CurUser = mysql_fetch_assoc($RankQry) )
	{
		$fleet[$CurUser['id_owner']]	=	$Rank;
		$Rank++;
	}
	unset($Rank,$RankQry,$QryUpdateStats,$CurUser);
	$Rank           = 1;
	$RankQry        = doquery("SELECT `id_owner` FROM {{table}} WHERE `stat_type` = '".$stat_type."' AND `stat_code` = '1' ORDER BY `total_points` DESC;", 'statpoints');
	while ($CurUser = mysql_fetch_assoc($RankQry) )
	{
		$QryUpdateStats  = "UPDATE {{table}} SET ";
		$QryUpdateStats .= "`tech_rank` = '". $tech[$CurUser['id_owner']] ."' ,";
		$QryUpdateStats .= "`build_rank` = '". $build[$CurUser['id_owner']] ."' ,";
		$QryUpdateStats .= "`defs_rank` = '". $defs[$CurUser['id_owner']] ."' ,";
		$QryUpdateStats .= "`fleet_rank` = '". $fleet[$CurUser['id_owner']] ."' ,";
		$QryUpdateStats .= "`total_rank` = '". $Rank ."' ";
		$QryUpdateStats .= "WHERE ";
		$QryUpdateStats .= " `stat_type` = '".$stat_type."' AND `stat_code` = '1' AND `id_owner` = '". $CurUser['id_owner'] ."';";
		doquery ( $QryUpdateStats , 'statpoints');
		unset($tech[$CurUser['id_owner']],$build[$CurUser['id_owner']],$defs[$CurUser['id_owner']],$fleet[$CurUser['id_owner']]);
		$Rank++;
	}
	unset($Rank,$RankQry,$QryUpdateStats,$CurUser);
	doquery ( "DELETE FROM {{table}} WHERE `stat_code` = '2';" , 'statpoints');
}
?>