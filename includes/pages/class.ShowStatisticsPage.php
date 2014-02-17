<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowStatisticsPage
{
	function ShowStatisticsPage ( $CurrentUser )
	{
		global $lang;

		$parse	= $lang;
		$who   	= (isset($_POST['who']))   ? $_POST['who']   : (isset($_GET['who'])?$_GET['who']:1);
		$type  	= (isset($_POST['type']))  ? $_POST['type']  : (isset($_GET['type'])?$_GET['type']:1);
		$range 	= (isset($_POST['range'])) ? $_POST['range'] : (isset($_GET['range'])?$_GET['range']:1);

		$parse['who']    = "<option value=\"1\"". (($who == "1") ? " SELECTED" : "") .">".$lang['st_player']."</option>";
		$parse['who']   .= "<option value=\"2\"". (($who == "2") ? " SELECTED" : "") .">".$lang['st_alliance']."</option>";

		$parse['type']   = "<option value=\"1\"". (($type == "1") ? " SELECTED" : "") .">".$lang['st_points']."</option>";
		$parse['type']  .= "<option value=\"2\"". (($type == "2") ? " SELECTED" : "") .">".$lang['st_fleets']."</option>";
		$parse['type']  .= "<option value=\"3\"". (($type == "3") ? " SELECTED" : "") .">".$lang['st_researh']."</option>";
		$parse['type']  .= "<option value=\"4\"". (($type == "4") ? " SELECTED" : "") .">".$lang['st_buildings']."</option>";
		$parse['type']  .= "<option value=\"5\"". (($type == "5") ? " SELECTED" : "") .">".$lang['st_defenses']."</option>";

		switch ($type)
		{
			case 1:
				$Order   = "total_points";
				$Points  = "total_points";
				$Counts  = "total_count";
				$Rank    = "total_rank";
				$OldRank = "total_old_rank";
				break;
			case 2:
				$Order   = "fleet_count";
				$Points  = "fleet_points";
				$Counts  = "fleet_count";
				$Rank    = "fleet_rank";
				$OldRank = "fleet_old_rank";
				break;
			case 3:
				$Order   = "tech_count";
				$Points  = "tech_points";
				$Counts  = "tech_count";
				$Rank    = "tech_rank";
				$OldRank = "tech_old_rank";
				break;
			case 4:
				$Order   = "build_points";
				$Points  = "build_points";
				$Counts  = "build_count";
				$Rank    = "build_rank";
				$OldRank = "build_old_rank";
				break;
			case 5:
				$Order   = "defs_points";
				$Points  = "defs_points";
				$Counts  = "defs_count";
				$Rank    = "defs_rank";
				$OldRank = "defs_old_rank";
				break;
			default:
				$Order   = "total_points";
				$Points  = "total_points";
				$Counts  = "total_count";
				$Rank    = "total_rank";
				$OldRank = "total_old_rank";
				break;
		}

		if ($who == 2)
		{
			$MaxAllys 	= doquery ("SELECT COUNT(*) AS `count` FROM {{table}};", 'alliance', TRUE);
			$LastPage	= 0;
			
			if ($MaxAllys['count'] > 100)
			{
				$LastPage = floor($MaxAllys['count'] / 100);
			}

			$parse['range'] = "";

			for ($Page = 0; $Page <= $LastPage; $Page++)
			{
				$PageValue      = ($Page * 100) + 1;
				$PageRange      = $PageValue + 99;
				$parse['range'] .= "<option value=\"". $PageValue ."\"". (($range >= $PageValue && $range <= $PageRange) ? " SELECTED" : "") .">". $PageValue ."-". $PageRange ."</option>";
			}

			$parse['stat_header'] = parsetemplate(gettemplate('stat/stat_alliancetable_header'), $parse);
			$start = floor($range / 100 % 100) * 100;
			$stats_sql	=	'SELECT s.*, a.id, a.ally_members, a.ally_tag, a.ally_name FROM {{table}}statpoints as s
			INNER JOIN {{table}}alliance as a ON a.id = s.id_owner
			WHERE `stat_type` = 2 AND `stat_code` = 1
			ORDER BY `'. $Order .'` DESC LIMIT '. $start .',100;';

			$start++;
			$parse['stat_date']   = date("Y-m-d, H:i:s",read_config ( 'stat_last_update' ) );
			$parse['stat_values'] = "";
			$query = doquery($stats_sql, '');
			$StatAllianceTableTPL=gettemplate('stat/stat_alliancetable');
			while ($StatRow = mysql_fetch_assoc($query))
			{
				$parse['ally_rank']       = $start;
				if ( $StatRow[ $OldRank ] == 0 || $StatRow[ $Rank ] == 0)
				{
					$rank_old				= $start;
					$QryUpdRank				= doquery("UPDATE {{table}} SET `".$Rank."` = '".$start."', `".$OldRank."` = '".$start."' WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". intval($StatRow['id_owner']) ."';" , "statpoints");
					$StatRow[ $OldRank ]	= $start;
					$StatRow[ $Rank ]		= $start;
				}

				$ranking                  = $StatRow[ $OldRank ] - $StatRow[ $Rank ];

				if ($ranking == 0)
				{
					$parse['ally_rankplus']   = "<font color=#87CEEB>*</font>";
				}

				if ($ranking < 0)
				{
					$parse['ally_rankplus']   = "<font color=red>-".$ranking."</font>";
				}

				if ($ranking > 0)
				{
					$parse['ally_rankplus']   = "<font color=green>+".$ranking."</font>";
				}

				$parse['ally_tag']        	  = $StatRow['ally_tag'];
				$parse['ally_name']       	  = $StatRow['ally_name'];
				$parse['ally_mes']        	  = '';
				$parse['ally_members']    	  = $StatRow['ally_members'];
				$parse['ally_points']     	  = Format::pretty_number( $StatRow[ $Order ] );
				$parse['ally_members_points'] =  Format::pretty_number( floor($StatRow[ $Order ] / $StatRow['ally_members']) );
				$parse['stat_values']    	 .= parsetemplate($StatAllianceTableTPL, $parse);
				$start++;
			}
		}
		else
		{
			$MaxUsers 	= doquery ("SELECT COUNT(*) AS `count` FROM {{table}} WHERE `db_deaktjava` = '0';", 'users', TRUE);
			$LastPage	= 0;
			
			if ($MaxUsers['count'] > 100)
			{
				$LastPage = floor($MaxUsers['count'] / 100);
			}

			$parse['range'] = "";

			for ($Page = 0; $Page <= $LastPage; $Page++)
			{
				$PageValue      = ($Page * 100) + 1;
				$PageRange      = $PageValue + 99;

				$parse['range'] .= "<option value=\"". $PageValue ."\"". (($range >= $PageValue && $range <= $PageRange) ? " SELECTED" : "") .">". $PageValue ."-". $PageRange ."</option>";
			}


			$parse['stat_header'] = parsetemplate(gettemplate('stat/stat_playertable_header'), $parse);

			$start = floor($range / 100 % 100) * 100;

			$stats_sql	=	'SELECT s.*, u.id, u.username, u.ally_id, u.ally_name FROM {{table}}statpoints as s
			INNER JOIN {{table}}users as u ON u.id = s.id_owner
			WHERE `stat_type` = 1 AND `stat_code` = 1
			ORDER BY `'. $Order .'` DESC LIMIT '. $start .',100;';

			$query = doquery($stats_sql, '');

			$start++;

			$parse['stat_date']   = date("Y-m-d, H:i:s",read_config ( 'stat_last_update' ) );
			$parse['stat_values'] = "";

			$previusId = 0;
			$StatPlayerTableTPL=gettemplate('stat/stat_playertable');
			while ($StatRow = mysql_fetch_assoc($query))
			{
				$parse['player_rank']     = $start;
				if ( $StatRow[ $OldRank ] == 0 || $StatRow[ $Rank ] == 0)
				{
					$rank_old				= $start;
					$QryUpdRank				= doquery("UPDATE {{table}} SET `".$Rank."` = '".$start."', `".$OldRank."` = '".$start."' WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". intval($StatRow['id_owner']) ."';" , "statpoints");
					$StatRow[ $OldRank ]	= $start;
					$StatRow[ $Rank ]		= $start;
				}

				$ranking                  = $StatRow[ $OldRank ] - $StatRow[ $Rank ];

				if ($StatRow['id'] != $previusId)
				{
					$previusId 			= $StatRow['id'];

					if ($ranking == 0)
					{
						$parse['player_rankplus'] = "<font color=#87CEEB>*</font>";
					}

					if ($ranking < 0)
						$parse['player_rankplus'] = "<font color=red>".$ranking."</font>";

					if ($ranking > 0)
						$parse['player_rankplus'] = "<font color=green>+".$ranking."</font>";

					if ($StatRow['id'] == $CurrentUser['id'])
						$parse['player_name']     = "<font color=\"lime\">".$StatRow['username']."</font>";
					else
						$parse['player_name']     = $StatRow['username'];

					if ($StatRow['id'] != $CurrentUser['id'])
						$parse['player_mes']      = "<a href=\"game.php?page=messages&mode=write&id=" . $StatRow['id'] . "\"><img src=\"" . DPATH . "img/m.gif\" border=\"0\" title=\"" . $lang['write_message'] . "\" /></a>";
					else
						$parse['player_mes']      = "";

					if ($StatRow['ally_name'] == $CurrentUser['ally_name'])
					{
						$parse['player_alliance'] = "<a href=\"game.php?page=alliance&mode=ainfo&a=".$StatRow['ally_id']."\"><font color=\"#33CCFF\">".$StatRow['ally_name']."</font></a>";
					}
					else
					{
						$parse['player_alliance'] = "<a href=\"game.php?page=alliance&mode=ainfo&a=".$StatRow['ally_id']."\">".$StatRow['ally_name']."</a>";
					}
					$parse['player_points']   = Format::pretty_number( $StatRow[ $Order ] );
					$parse['stat_values']    .= parsetemplate($StatPlayerTableTPL, $parse);


					$start++;
				}
			}
		}

		display(parsetemplate( gettemplate('stat/stat_body'), $parse ));
	}
}
?>
