<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);
define('XGP_ROOT', './../');

include(XGP_ROOT . 'global.php');

if ($ConfigGame != 1) die(message ($lang['404_page']));
$AreLog	=	$LogCanWork;

function DisplayGameSettingsPage ( $CurrentUser )
{
	global $lang, $AreLog;

	$game_config	= 	read_config ( '' , TRUE );

	if ( $_POST['opt_save'] == "1" )
	{
		$Log	.=	"\n".$lang['log_the_user'].$user['username'].$lang['log_sett_no1'].":\n";

		if (isset($_POST['closed']) && $_POST['closed'] == 'on') {
		$game_config['game_disable']         = 1;
		$game_config['close_reason']         = addslashes( $_POST['close_reason'] );
		$Log	.=	$lang['log_sett_close'].": ".$lang['log_viewmod2'][1]."\n";
		} else {
		$game_config['game_disable']         = 0;
		$game_config['close_reason']         = addslashes( $_POST['close_reason'] );
		$Log	.=	$lang['log_sett_close'].": ".$lang['log_viewmod2'][0]."\n";
		$Log	.=	$lang['log_sett_close_rea'].": ".$_POST['close_reason']."\n";
		}

		if (isset($_POST['debug']) && $_POST['debug'] == 'on') {
		$game_config['debug'] = 1;
		$Log	.=	$lang['log_sett_debug'].": ".$lang['log_viewmod'][1]."\n";
		} else {
		$game_config['debug'] = 0;
		$Log	.=	$lang['log_sett_debug'].": ".$lang['log_viewmod'][0]."\n";
		}

		if (isset($_POST['game_name']) && $_POST['game_name'] != '') {
		$game_config['game_name'] = $_POST['game_name'];
		$Log	.=	$lang['log_sett_name_game'].": ".$_POST['game_name']."\n";
		}

		if (isset($_POST['forum_url']) && $_POST['forum_url'] != '') {
		$game_config['forum_url'] = $_POST['forum_url'];
		$Log	.=	$lang['log_sett_forum_url'].": ".$_POST['forum_url']."\n";
		}

		if (isset($_POST['game_speed']) && is_numeric($_POST['game_speed'])) {
		$game_config['game_speed'] = (2500 * $_POST['game_speed']);
		$Log	.=	$lang['log_sett_velo_game'].": x".$_POST['game_speed']."\n";
		}

		if (isset($_POST['fleet_speed']) && is_numeric($_POST['fleet_speed'])) {
		$game_config['fleet_speed'] = (2500 * $_POST['fleet_speed']);
		$Log	.=	$lang['log_sett_velo_flottes'].": x".$_POST['fleet_speed']."\n";
		}

		if (isset($_POST['resource_multiplier']) && is_numeric($_POST['resource_multiplier'])) {
		$game_config['resource_multiplier'] = $_POST['resource_multiplier'];
		$Log	.=	$lang['log_sett_velo_prod'].": x".$_POST['resource_multiplier']."\n";
		}

		if (isset($_POST['initial_fields']) && is_numeric($_POST['initial_fields'])) {
		$game_config['initial_fields'] = $_POST['initial_fields'];
		$Log	.=	$lang['log_sett_fields'].": ".$_POST['initial_fields']."\n";
		}

		if (isset($_POST['metal_basic_income']) && is_numeric($_POST['metal_basic_income'])) {
		$game_config['metal_basic_income'] = $_POST['metal_basic_income'];
		$Log	.=	$lang['log_sett_basic_m'].": ".$_POST['metal_basic_income']."\n";
		}

		if (isset($_POST['crystal_basic_income']) && is_numeric($_POST['crystal_basic_income'])) {
		$game_config['crystal_basic_income'] = $_POST['crystal_basic_income'];
		$Log	.=	$lang['log_sett_basic_c'].": ".$_POST['crystal_basic_income']."\n";
		}

		if (isset($_POST['deuterium_basic_income']) && is_numeric($_POST['deuterium_basic_income'])) {
		$game_config['deuterium_basic_income'] = $_POST['deuterium_basic_income'];
		$Log	.=	$lang['log_sett_basic_d'].": ".$_POST['deuterium_basic_income']."\n";
		}

		if (isset($_POST['adm_attack']) && $_POST['adm_attack'] == 'on') {
			$game_config['adm_attack'] = 1;
			$Log	.=	$lang['log_sett_adm_protection'].": ".$lang['log_viewmod'][1]."\n";
		} else {
			$game_config['adm_attack'] = 0;
			$Log	.=	$lang['log_sett_adm_protection'].": ".$lang['log_viewmod'][0]."\n";
		}

		if (isset($_POST['language'])) {
			$game_config['lang'] = $_POST['language'];
			$Log	.=	$lang['log_sett_language'].": ".$_POST['language']."\n";
		} else {
			$game_config['lang'];
		}

		if (isset($_POST['cookie_name']) && $_POST['game_name'] != '') {
			$game_config['cookie_name'] = $_POST['cookie_name'];
			$Log	.=	$lang['log_sett_name_cookie'].": ".$_POST['cookie_name']."\n";
		}

		if (isset($_POST['Defs_Cdr']) && is_numeric($_POST['Defs_Cdr'])) {
			if ($_POST['Defs_Cdr'] < 0){
				$game_config['defs_cdr'] = 0;
				$Number	=	0;}
			else{
				$game_config['defs_cdr'] = $_POST['Defs_Cdr'];
				$Number	=	$_POST['Defs_Cdr'];}

			$Log	.=	$lang['log_sett_debris_def'].": ".$Number."%\n";
		}

		if (isset($_POST['Fleet_Cdr']) && is_numeric($_POST['Fleet_Cdr'])) {
			if ($_POST['Fleet_Cdr'] < 0){
				$game_config['fleet_cdr'] = 0;
				$Number2	=	0;}
			else{
				$game_config['fleet_cdr'] = $_POST['Fleet_Cdr'];
				$Number2	=	$_POST['Fleet_Cdr'];}

			$Log	.=	$lang['log_sett_debris_flot'].": ".$Number2."%\n";
		}

		if (isset($_POST['noobprotection']) && $_POST['noobprotection'] == 'on') {
			$game_config['noobprotection'] = 1;
			$Log	.=	$lang['log_sett_act_noobs'].": ".$lang['log_viewmod'][1]."\n";
		} else {
			$game_config['noobprotection'] = 0;
			$Log	.=	$lang['log_sett_act_noobs'].": ".$lang['log_viewmod'][0]."\n";
		}

		if (isset($_POST['noobprotectiontime']) && is_numeric($_POST['noobprotectiontime'])) {
			$game_config['noobprotectiontime'] = $_POST['noobprotectiontime'];
			$Log	.=	$lang['log_sett_noob_time'].": ".$_POST['noobprotectiontime']."\n";
		}

		if (isset($_POST['noobprotectionmulti']) && is_numeric($_POST['noobprotectionmulti'])) {
			$game_config['noobprotectionmulti'] = $_POST['noobprotectionmulti'];
			$Log	.=	$lang['log_sett_noob_multi'].": ".$_POST['noobprotectionmulti']."\n";
		}


		LogFunction($Log, "ConfigLog", $AreLog);

		update_config ( 'game_disable'				, $game_config['game_disable'] 			);
		update_config ( 'close_reason' 				, $game_config['close_reason'] 			);
		update_config ( 'game_name' 				, $game_config['game_name'] 				);
		update_config ( 'forum_url' 				, $game_config['forum_url'] 				);
		update_config ( 'game_speed' 				, $game_config['game_speed'] 				);
		update_config ( 'fleet_speed' 				, $game_config['fleet_speed']            	);
		update_config ( 'resource_multiplier' 		, $game_config['resource_multiplier']    	);
		update_config ( 'initial_fields' 			, $game_config['initial_fields']         	);
		update_config ( 'metal_basic_income' 		, $game_config['metal_basic_income']     	);
		update_config ( 'crystal_basic_income' 		, $game_config['crystal_basic_income']   	);
		update_config ( 'deuterium_basic_income'	, $game_config['deuterium_basic_income']	);
		update_config ( 'debug' 					, $game_config['debug'] 					);
		update_config ( 'adm_attack' 				, $game_config['adm_attack'] 				);
		update_config ( 'lang' 						, $game_config['lang'] 					);
		update_config ( 'cookie_name' 				, $game_config['cookie_name'] 			);
		update_config ( 'noobprotection' 			, $game_config['noobprotection'] 			);
		update_config ( 'defs_cdr' 					, $game_config['defs_cdr'] 				);
		update_config ( 'fleet_cdr' 				, $game_config['fleet_cdr'] 				);
		update_config ( 'noobprotectiontime' 		, $game_config['noobprotectiontime'] 		);
		update_config ( 'noobprotectionmulti' 		, $game_config['noobprotectionmulti'] 	);

		header ( 'location:SettingsPage.php' );
	}
	else
	{
		$parse								= $lang;
		$parse['game_name']              	= $game_config['game_name'];
		$parse['game_speed']             	= ($game_config['game_speed'] / 2500);
		$parse['fleet_speed']            	= ($game_config['fleet_speed'] / 2500);
		$parse['resource_multiplier']    	= $game_config['resource_multiplier'];
		$parse['forum_url']              	= $game_config['forum_url'];
		$parse['initial_fields']         	= $game_config['initial_fields'];
		$parse['metal_basic_income']     	= $game_config['metal_basic_income'];
		$parse['crystal_basic_income']   	= $game_config['crystal_basic_income'];
		$parse['deuterium_basic_income'] 	= $game_config['deuterium_basic_income'];
		$parse['closed']                 	= ($game_config['game_disable'] == 1) ? " checked = 'checked' ":"";
		$parse['close_reason']           	= stripslashes($game_config['close_reason']);
		$parse['debug']                  	= ($game_config['debug'] == 1)        ? " checked = 'checked' ":"";
		$parse['adm_attack']             	= ($game_config['adm_attack'] == 1)   ? " checked = 'checked' ":"";
		$parse['cookie'] 					= $game_config['cookie_name'];
		$parse['defenses'] 					= $game_config['defs_cdr'];
		$parse['shiips'] 					= $game_config['fleet_cdr'];
		$parse['noobprot']            	 	= ($game_config['noobprotection'] == 1)   ? " checked = 'checked' ":"";
		$parse['noobprot2'] 				= $game_config['noobprotectiontime'];
		$parse['noobprot3'] 				= $game_config['noobprotectionmulti'];

		$LangFolder = opendir("./../" . 'language');

		while (($LangSubFolder = readdir($LangFolder)) !== FALSE)
		{
			if($LangSubFolder != '.' && $LangSubFolder != '..' && $LangSubFolder != '.htaccess' && $LangSubFolder != '.svn' && $LangSubFolder != 'index.html')
			{
				$parse['language_settings'] .= "<option ";

				if($game_config['lang'] == $LangSubFolder)
					$parse['language_settings'] .= "selected = selected";

				$parse['language_settings'] .= " value=\"".$LangSubFolder."\">".$LangSubFolder."</option>";
			}
		}

		return display (parsetemplate(gettemplate('adm/SettingsBody'),  $parse), FALSE, '', TRUE, FALSE);
	}
}

DisplayGameSettingsPage($user);
?>