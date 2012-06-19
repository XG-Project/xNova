<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowMessagesPage
{
	function __construct ( $CurrentUser )
	{
		global $lang;

		$OwnerID		= intval ( $_GET['id'] );
		$MessCategory  	= intval ( $_GET['messcat'] );
		$MessPageMode  	= addslashes ( mysql_escape_string ( $_GET["mode"] ) );
		$DeleteWhat    	= $_POST['deletemessages'];

		if ( isset ( $DeleteWhat ) )
		{
			$MessPageMode = "delete";
		}

		$UsrMess       = doquery("SELECT * FROM {{table}} WHERE `message_owner` = '" . intval ( $CurrentUser['id'] ) . "' ORDER BY `message_time` DESC;" , 'messages' );
		$UnRead        = doquery("SELECT * FROM {{table}} WHERE `id` = '" . intval ( $CurrentUser['id'] ) . "';" , 'users' , TRUE );

		$MessageType   = array ( 0, 1, 2, 3, 4, 5, 15, 99, 100 );
		$TitleColor    = array (
									0 => '#FFFF00',
									1 => '#FF6699',
									2 => '#FF3300',
									3 => '#FF9900',
									4 => '#773399',
									5 => '#009933',
									15 => '#030070',
									99 => '#007070',
									100 => '#ABABAB'
								);

		for ( $MessType = 0 ; $MessType < 101 ; $MessType++ )
		{
			if ( in_array ( $MessType , $MessageType ) )
			{
				$WaitingMess[$MessType]	= $UnRead[$messfields[$MessType]];
				$TotalMess[$MessType]   = 0;
			}
		}

		while ( $CurMess = mysql_fetch_array ( $UsrMess ) )
		{
			$MessType              = $CurMess['message_type'];
			$TotalMess[$MessType] += 1;
			$TotalMess[100]       += 1;
		}

		switch ( $MessPageMode )
		{
			case 'write':

				if ( !is_numeric ( $OwnerID ) )
				{
					header ( "location:game.php?page=messages" );
				}
				else
				{
					$OwnerRecord	=	doquery ( "SELECT `id_planet`,`username` FROM {{table}} WHERE `id` = '" . intval ( $OwnerID ) . "';" , 'users' , TRUE );
					$OwnerHome		= 	doquery ( "SELECT `galaxy`,`system`,`planet` FROM {{table}} WHERE `id_planet` = '" . intval ( $OwnerRecord["id_planet"] ) . "';" , 'galaxy' , TRUE );

					if ( !$OwnerRecord or !$OwnerHome )
					{
						header ( "location:game.php?page=messages" );
					}
				}

				$parse	=	$lang;

				if ( $_POST )
				{
					$error 	= 	0;

					if ( !$_POST["subject"] )
					{
						$error++;
						$parse['error_text']	=	$lang['mg_no_subject'];
						$parse['error_color']	=	'#FF0000';
						$error_page				=	parsetemplate ( gettemplate ( 'messages/messages_error_table' ) , $parse );
					}

					if ( !$_POST["text"] )
					{
						$error++;
						$parse['error_text']	=	$lang['mg_no_text'];
						$parse['error_color']	=	'#FF0000';
						$error_page				=	parsetemplate ( gettemplate ( 'messages/messages_error_table' ) , $parse );
					}

					if ( $error == 0 )
					{
						$parse['error_text']	=	$lang['mg_msg_sended'];
						$parse['error_color']	=	'#00FF00';
						$error_page				=	parsetemplate ( gettemplate ( 'messages/messages_error_table' ) , $parse );

						$_POST['text'] 			= str_replace ( "'" , '&#39;' , $_POST['text'] );
						$Owner   				= $OwnerID;
						$Sender  				= intval ( $CurrentUser['id'] );
						$From    				= $CurrentUser['username'] . " [" .$CurrentUser['galaxy'] . ":" . $CurrentUser['system'] . ":" . $CurrentUser['planet'] . "]";
						$Subject 				= $_POST['subject'];
						$Message				= preg_replace ( "/([^\s]{80}?)/" , "\\1<br />" , trim ( nl2br ( strip_tags ( $_POST['text'] , '<br>' ) ) ) );

						SendSimpleMessage ( $Owner , $Sender , '' , 1 , $From , $Subject , $Message );

						$subject 				= "";
						$text    				= "";
					}
				}

				$parse['id']           		= $OwnerID;
				$parse['to']           		= $OwnerRecord['username'] . " [" .$OwnerHome['galaxy'] . ":" . $OwnerHome['system'] . ":" . $OwnerHome['planet'] . "]";
				$parse['subject']      		= ( !isset ( $subject ) ) ? $lang['mg_no_subject'] : $subject;
				$parse['text']         		= $text;
				$parse['status_message']	= $error_page;

				display ( parsetemplate ( gettemplate ( 'messages/messages_pm_form' ) , $parse ) );

				break;

			case 'delete':

				$DeleteWhat 	= $_POST['deletemessages'];

				if($DeleteWhat == 'deleteall')
				{
					doquery("DELETE FROM {{table}} WHERE `message_owner` = '". intval($CurrentUser['id']) ."';", 'messages');
				}
				elseif ( $DeleteWhat == 'deletemarked' )
				{
					foreach ( $_POST as $Message => $Answer )
					{
						if ( preg_match ( "/delmes/i" , $Message ) && $Answer == 'on' )
						{
							$MessId   = str_replace("delmes", "", $Message);
							$MessHere = doquery("SELECT * FROM {{table}} WHERE `message_id` = '". intval($MessId) ."' AND `message_owner` = '". intval($CurrentUser['id']) ."';", 'messages');

							if ( $MessHere )
							{
								doquery ( "DELETE FROM {{table}} WHERE `message_id` = '" . intval ( $MessId ) . "';" , 'messages' );
							}
						}
					}
				}
				elseif ( $DeleteWhat == 'deleteunmarked' )
				{
					foreach ( $_POST as $Message => $Answer )
					{
						$CurMess    	= preg_match ( "/showmes/i" , $Message );
						$MessId     	= str_replace ( "showmes" , "" , $Message );
						$Selected   	= "delmes" . $MessId;
						$IsSelected		= $_POST[$Selected];

						if ( preg_match ( "/showmes/i" , $Message ) && !isset ( $IsSelected ) )
						{
							$MessHere = doquery("SELECT * FROM {{table}} WHERE `message_id` = '" . intval ( $MessId ) . "' AND `message_owner` = '" . intval ( $CurrentUser['id'] ) . "';" , 'messages' );

							if ( $MessHere )
							{
								doquery("DELETE FROM {{table}} WHERE `message_id` = '" . intval ( $MessId ) . "';" , 'messages' );
							}
						}
					}
				}

				header("location:game.php?page=messages");

				break;
			case 'show':

				###############################################################################################
				#
				# LOAD MESSAGES
				#
				###############################################################################################

				$parse					=	$lang;
				$subTemplateMessages	= 	gettemplate ( 'messages/messages_row' );
				$subTemplateOperators	=   gettemplate ( 'messages/messages_adm_row' );

				if ( $MessCategory == 100 )
				{
					$UsrMess	= doquery ( "SELECT * FROM {{table}} WHERE `message_owner` = '" . intval ( $CurrentUser['id'] ) . "' ORDER BY `message_time` DESC;" , 'messages' );
				}
				else
				{
					$UsrMess	= doquery ( "SELECT * FROM {{table}} WHERE `message_owner` = '" . intval ( $CurrentUser['id'] ) . "' AND `message_type` = '" . $MessCategory . "' ORDER BY `message_time` DESC;" , 'messages' );
				}

				$QryUpdateUser  = "UPDATE {{table}} SET ";
				$QryUpdateUser .= "`new_message` = '0' ";
				$QryUpdateUser .= "WHERE ";
				$QryUpdateUser .= "`id` = '" . intval ( $CurrentUser['id'] ) . "';";
				doquery ( $QryUpdateUser, 'users');

				while ( $CurMess = mysql_fetch_array ( $UsrMess ) )
				{
					$parse['message_id']		=	$CurMess['message_id'];
					$parse['message_date']		=	date ( "m-d H:i:s" , $CurMess['message_time'] );
					$parse['message_from']		=   stripslashes( $CurMess['message_from'] );
					$parse['message_subject']	=	stripslashes( $CurMess['message_subject'] );


					if ( $CurMess['message_type'] == 1 )
					{
						$parse['message_subject'] .= "<a href=\"game.php?page=messages&mode=write&amp;id=". $CurMess['message_sender'] ."&amp;subject=Re: " . htmlspecialchars( $CurMess['message_subject']) ."\">";
						$parse['message_subject'] .= " <img src=\"". DPATH ."img/m.gif\" border=\"0\"></a>\n";
					}
					else
					{
						$parse['message_subject'] .= "";
					}

					$parse['message_text']		= stripslashes( nl2br( $CurMess['message_text'] ) );

					$messagesBody				.= parsetemplate ( $subTemplateMessages , $parse );
				}

				###############################################################################################
				#
				# LOAD OPERATORS
				#
				###############################################################################################

				$QrySelectUser  = "SELECT `username`, `email` ";
				$QrySelectUser .= "FROM `{{table}}` ";
				$QrySelectUser .= "WHERE `authlevel` != '0' ORDER BY `username` ASC;";
				$GameOps = doquery ($QrySelectUser, 'users');

				while ( $Ops = mysql_fetch_assoc ( $GameOps ) )
				{
					$parse['dpath']		= DPATH;
					$parse['username'] 	= $Ops['username'];
					$parse['mail']		= $Ops['email'];
					$operatorsBody		.= parsetemplate ( $subTemplateOperators , $parse );
				}

				// SUBTEMPLATES
				$parse['show_messages']			= $messagesBody;
				$parse['show_operators']		= $operatorsBody;

				display ( parsetemplate ( gettemplate ( 'messages/messages_body' ) , $parse ) );

				break;
			default:

				$parse				=   $lang;
				$parse['all_color']	=	$TitleColor[100];
				$parse['all_total']	=  	$TotalMess[100];
				$parse['all_lang']	= 	$lang['mg_type'][100];

				$subTemplate 	= gettemplate ( 'messages/messages_menu_row' );

				for ( $MessType = 0 ; $MessType < 100 ; $MessType++ )
				{
					if ( in_array ( $MessType , $MessageType ) )
					{

						$parse['color'] = $TitleColor[$MessType];
						$parse['total'] = $TotalMess[$MessType];
						$parse['lang']	= $lang['mg_type'][$MessType];
						$parse['type']	= $MessType;

						$body .= parsetemplate ( $subTemplate , $parse );
					}
				}

				$parse['messages_menu_row']	= $body;

				display ( parsetemplate ( gettemplate ( 'messages/messages_menu_body' ) , $parse ) );

				break;
		}
	}
}
?>