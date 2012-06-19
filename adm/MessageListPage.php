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

if ($Observation != 1) die(message ($lang['404_page']));

	$parse		= $lang;
	$Prev       = ( !empty($_POST['prev'])   ) ? TRUE : FALSE;
	$Next       = ( !empty($_POST['next'])   ) ? TRUE : FALSE;
	$DelSel     = ( !empty($_POST['delsel']) ) ? TRUE : FALSE;
	$DelDat     = ( !empty($_POST['deldat']) ) ? TRUE : FALSE;
	$CurrPage   = ( !empty($_POST['curr'])   ) ? $_POST['curr'] : 1;
	$Selected   = ( !empty($_POST['sele'])   ) ? $_POST['sele'] : 0;
	$SelType    = $_POST['type'];
	$SelPage    = $_POST['page'];

	$ViewPage = 1;
	if ( $Selected != $SelType )
	{
		$Selected = $SelType;
		$ViewPage = 1;
	}
	elseif ( $CurrPage != $SelPage )
	{
		$ViewPage = ( !empty($SelPage) ) ? $SelPage : 1;
	}

	if($Prev   == TRUE)
	{
		$CurrPage -= 1;
		if ($CurrPage >= 1)
			$ViewPage = $CurrPage;
		else
			$ViewPage = 1;

	}
	elseif ($Next   == TRUE && $_POST['page'])
	{
		if ($Selected < 100)
			$Mess      = doquery("SELECT COUNT(*) AS `max` FROM {{table}} WHERE `message_type` = '". $Selected ."';", 'messages', TRUE);
		elseif ($Selected == 100)
			$Mess      = doquery("SELECT COUNT(*) AS `max` FROM {{table}}", 'messages', TRUE);

		$MaxPage   = ceil ( ($Mess['max'] / 25) );
		$CurrPage += 1;
		if ($CurrPage <= $MaxPage)
			$ViewPage = $CurrPage;
		else
			$ViewPage = $MaxPage;
	}

	if ($_POST['delsel'] && $_POST['sele'] >= 1 && $_POST['page'])
	{
		if ($DelSel == TRUE)
		{
			foreach($_POST['sele'] as $MessId => $Value)
			{
				if ($Value = "on")
					doquery ( "DELETE FROM {{table}} WHERE `message_id` = '". $MessId ."';", 'messages');
			}
		}
	}

	if ($_POST['deldat'] && $_POST['sele'] >= 1 && is_numeric($_POST['selday']) && is_numeric($_POST['selmonth']) && is_numeric($_POST['selyear'])
		&& $_POST['page'])
	{
		if ($DelDat == TRUE)
		{
			$SelDay    = $_POST['selday'];
			$SelMonth  = $_POST['selmonth'];
			$SelYear   = $_POST['selyear'];
			$LimitDate = mktime (0,0,0, $SelMonth, $SelDay, $SelYear );
			if ($LimitDate != FALSE)
			{
				doquery ( "DELETE FROM {{table}} WHERE `message_time` <= '". $LimitDate ."';", 'messages');
				doquery ( "DELETE FROM {{table}} WHERE `time` <= '". $LimitDate ."';", 'rw');
			}
		}
	}

	if ($Selected < 100)
		$Mess      = doquery("SELECT COUNT(*) AS `max` FROM {{table}} WHERE `message_type` = '". $Selected ."';", 'messages', TRUE);
	elseif ($Selected == 100)
		$Mess      = doquery("SELECT COUNT(*) AS `max` FROM {{table}}", 'messages', TRUE);

	$MaxPage  = ceil ( ($Mess['max'] / 25) );

	$parse['mlst_data_page']    = $ViewPage;
	$parse['mlst_data_pagemax'] = $MaxPage;
	$parse['mlst_data_sele']    = $Selected;
	$parse['mlst_data_types']   = "<option value=\"0\"".  (($Selected == "0")  ? " SELECTED" : "") .">".$lang['mg_type'][0]."</option>";
	$parse['mlst_data_types']  .= "<option value=\"1\"".  (($Selected == "1")  ? " SELECTED" : "") .">".$lang['mg_type'][1]."</option>";
	$parse['mlst_data_types']  .= "<option value=\"2\"".  (($Selected == "2")  ? " SELECTED" : "") .">".$lang['mg_type'][2]."</option>";
	$parse['mlst_data_types']  .= "<option value=\"3\"".  (($Selected == "3")  ? " SELECTED" : "") .">".$lang['mg_type'][3]."</option>";
	$parse['mlst_data_types']  .= "<option value=\"4\"".  (($Selected == "4")  ? " SELECTED" : "") .">".$lang['mg_type'][4]."</option>";
	$parse['mlst_data_types']  .= "<option value=\"5\"".  (($Selected == "5")  ? " SELECTED" : "") .">".$lang['mg_type'][5]."</option>";
	$parse['mlst_data_types']  .= "<option value=\"15\"". (($Selected == "15") ? " SELECTED" : "") .">".$lang['mg_type'][15]."</option>";
	$parse['mlst_data_types']  .= "<option value=\"99\"". (($Selected == "99") ? " SELECTED" : "") .">".$lang['mg_type'][99]."</option>";
	$parse['mlst_data_types']  .= "<option value=\"100\"". (($Selected == "100") ? " SELECTED" : "") .">".$lang['ml_see_all_messages']."</option>";
	$parse['mlst_data_pages']   = "";

	for ( $cPage = 1; $cPage <= $MaxPage; $cPage++ )
	{
		$parse['mlst_data_pages'] .= "<option value=\"".$cPage."\"".  (($ViewPage == $cPage)  ? " SELECTED" : "") .">". $cPage ."/". $MaxPage ."</option>";
	}

	$StartRec            = ($ViewPage - 1) * 25;

	if ($Selected < 100)
		$Messages            = doquery("SELECT * FROM {{table}} WHERE `message_type` = '". $Selected ."' ORDER BY `message_time` DESC LIMIT ". $StartRec .",25;", 'messages');
	elseif ($Selected == 100)
		$Messages            = doquery("SELECT * FROM {{table}} ORDER BY `message_time` DESC LIMIT ". $StartRec .",25;", 'messages');

		while ($row = mysql_fetch_assoc($Messages))
		{
			$OwnerData = doquery ("SELECT `username` FROM {{table}} WHERE `id` = '". $row['message_owner'] ."';", 'users',TRUE);
			$bloc['mlst_id']      = $row['message_id'];
			$bloc['mlst_from']    = $row['message_from'];
			$bloc['mlst_to']      = $OwnerData['username'] ." ".$lang['input_id'].":". $row['message_owner'];
			$bloc['mlst_subject'] = $row['message_subject'];
			$bloc['mlst_text']    = $row['message_text'];
			$bloc['mlst_time']    = date ("d/M/y H:i:s", $row['message_time'] );

			$parse['mlst_data_rows'] .= parsetemplate(gettemplate('adm/MessageListRows'), $bloc);
		}
	display (parsetemplate(gettemplate('adm/MessageListBody'), $parse), FALSE, '', TRUE, FALSE);
?>