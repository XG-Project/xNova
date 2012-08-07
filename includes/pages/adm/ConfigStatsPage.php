<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);
define('XN_ROOT', './../');

include(XN_ROOT . 'global.php');
include('AdminFunctions/Autorization.php');

if ($ConfigGame != 1) die(message ($lang['404_page']));

	$game_stat				=	read_config ( 'stat' );
	$game_stat_level		=	read_config ( 'stat_level' );
	$game_stat_flying		=	read_config ( 'stat_flying' );
	$game_stat_settings		=	read_config ( 'stat_settings' );
	$game_stat_amount		=	read_config ( 'stat_amount' );
	$game_stat_update_time	=	read_config ( 'stat_update_time' );
	$game_stat_last_update	=	read_config ( 'stat_last_update' );

	if ($_POST['save'] == $lang['cs_save_changes'])
	{
		$Log	.=	"\n".$lang['log_the_user'].$user['username'].$lang['log_change_stats'].":\n";
		if (isset($_POST['stat']) && $_POST['stat'] != $game_stat )
		{
			update_config('stat' , $_POST['stat']);
			$game_stat	= $_POST['stat'];
			$ASD3		=	$_POST['stat'];
			$Log		.=	$lang['log_stats_value_5'].": ".$lang['log_viewmod'][$ASD3]."\n";
		}
		if (isset($_POST['stat_level']) &&  is_numeric($_POST['stat_level']) && $_POST['stat_level'] != $game_stat_level)
		{
			update_config('stat_level',  $_POST['stat_level']);
			$game_stat_level = $_POST['stat_level'];
			$ASD1	=	$_POST['stat_level'];
			$Log	.=	$lang['log_stats_value_6'].": ".$lang['rank'][$ASD1]."\n";
		}
		if (isset($_POST['stat_flying']) && $_POST['stat_flying'] != $game_stat_flying)
		{
			update_config('stat_flying',  $_POST['stat_flying']);
			$game_stat_flying	= $_POST['stat_flying'];
			$ASD2	=	$_POST['stat_flying'];
			$Log	.=	$lang['log_stats_value_4'].": ".$lang['log_viewmod'][$ASD2]."\n";
		}
		if (isset($_POST['stat_settings']) &&  is_numeric($_POST['stat_settings']) && $_POST['stat_settings'] != $game_stat_settings)
		{
			update_config('stat_settings',  $_POST['stat_settings']);
			$game_stat_settings = $_POST['stat_settings'];
			$Log	.=	$lang['log_stats_value'].": ".$_POST['stat_settings']."\n";
		}
		if (isset($_POST['stat_amount']) &&  is_numeric($_POST['stat_amount']) && $_POST['stat_amount'] != $game_stat_amount && $_POST['stat_amount'] >= 10)
		{
			update_config('stat_amount',  $_POST['stat_amount']);
			$game_stat_amount	= $_POST['stat_amount'];
			$Log	.=	$lang['log_stats_value_3'].": ".$_POST['stat_amount']."\n";
		}
		if (isset($_POST['stat_update_time']) &&  is_numeric($_POST['stat_update_time']) && $_POST['stat_update_time'] != $game_stat_update_time)
		{
			update_config('stat_update_time',  $_POST['stat_update_time']);
			$game_stat_update_time = $_POST['stat_update_time'];
			$Log	.=	$lang['log_stats_value_2'].": ".$_POST['stat_update_time']."\n";
		}
		LogFunction($Log, "ConfigLog", $LogCanWork);
		header('location:ConfigStatsPage.php');

	}
	else
	{
		$parse						=	$lang;
		$selected					=	"selected";
		$stat						=	(($game_stat == 1)? 'sel_sta0':'sel_sta1');
		$parse[$stat]				=	$selected;
		$stat_fly					=	(($game_stat_flying == 1)? 'sel_sf1':'sel_sf0');
		$parse[$stat_fly]			=	$selected;
		$parse['stat_settings']		=	$game_stat_settings;
		$parse['stat_amount']		=	$game_stat_amount;
		$parse['stat_update_time']	=	$game_stat_update_time;
		$parse['timeact']			=	gmdate("d/M/y H:i:s", $game_stat_last_update);
		$parse['selected_'.$game_stat_level]	= 'selected';
		foreach ($lang['rank'] as $key => $rank) $parse['rank_'.$key] = $rank;
		$parse['yes']				=	$lang['one_is_yes'][1];
		$parse['no']				=	$lang['one_is_yes'][0];
		$admin_settings = parsetemplate(gettemplate('adm/ConfigStatsBody'), $parse);
		display($admin_settings, FALSE, '', TRUE, FALSE);
	}


/* End of file ConfigStatsPage.php */
/* Location: ./adm/ConfigStatsPage.php */
