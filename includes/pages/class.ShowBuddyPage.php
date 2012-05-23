<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowBuddyPage
{
	function __construct ( $CurrentUser )
	{
		global $lang;

		$mode	= intval ( $_GET['mode'] );
		$bid	= intval ( $_GET['bid'] );
		$sm		= intval ( $_GET['sm'] );
		$user	= intval ( $_GET['u'] );
		$parse	= $lang;

		switch ( $mode )
		{
			case 1:

				switch ( $sm )
				{
					// REJECT / CANCEL
					case 1:

						$senderID = doquery ( "SELECT * FROM {{table}} WHERE `id`='" . intval ( $bid ) . "'" , "buddy" , TRUE );

						if ( $senderID['active'] == 0 )
						{
							if ( $senderID['sender'] != $CurrentUser['id'] )
							{
								SendSimpleMessage ( $senderID['sender'] , $CurrentUser['id'] , '' , 1 , $CurrentUser['username'] , $lang['bu_rejected_title'] , str_replace ( '%u' , $CurrentUser['username'] , $lang['bu_rejected_text'] ) );
							}
							elseif ( $senderID['sender'] == $CurrentUser['id'] )
							{
								SendSimpleMessage ( $senderID['owner'] , $CurrentUser['id'] , '' , 1 , $CurrentUser['username'] , $lang['bu_rejected_title'] , str_replace ( '%u' , $CurrentUser['username'] , $lang['bu_rejected_title'] ) );
							}
						}
						else
						{
							if ( $senderID['sender'] != $CurrentUser['id'] )
							{
								SendSimpleMessage ( $senderID['sender'] , $CurrentUser['id'] , '' , 1 , $CurrentUser['username'] , $lang['bu_deleted_title'] , str_replace ( '%u' , $CurrentUser['username'] , $lang['bu_deleted_text'] ) );
							}
							elseif ( $senderID['sender'] == $CurrentUser['id'] )
							{
								SendSimpleMessage ( $senderID['owner'] , $CurrentUser['id'] , '' , 1 , $CurrentUser['username'] , $lang['bu_deleted_title'] , str_replace ( '%u' , $CurrentUser['username'] , $lang['bu_deleted_text'] ) );
							}
						}

						doquery ( "DELETE FROM {{table}} WHERE `id`='" . intval ( $bid ) . "' AND (`owner`='" . $CurrentUser['id'] . "' OR `sender`='" . $CurrentUser['id'] . "') " , "buddy" );

						header("location:game.php?page=buddy");

						break;

						// ACCEPT
					case 2:

						$senderID = doquery ( "SELECT * FROM {{table}} WHERE `id`='" . intval ( $bid ) . "'" , "buddy" , TRUE );

						SendSimpleMessage ( $senderID['sender'] , $CurrentUser['id'] , '' , 1 , $CurrentUser['username'] , $lang['bu_accepted_title'] , str_replace ( '%u' , $CurrentUser['username'] , $lang['bu_accepted_text'] ) );

						doquery ( "UPDATE {{table}} SET `active` = '1' WHERE `id` ='" . intval ( $bid ) . "' AND `owner`='" . $CurrentUser['id'] . "'" , "buddy" );

						header("location:game.php?page=buddy");

						break;

						// SEND REQUEST
					case 3:

						$query = doquery ( "SELECT `id` FROM {{table}} WHERE (`owner`='" . intval ( $CurrentUser[id] ) . "' AND `sender`='" . intval ( $_POST['user'] ) . "') OR (`owner`='" . intval ( $_POST['user'] ) . "' AND `sender`='" . intval( $CurrentUser[id] ) . "')" , "buddy" , TRUE );

						if ( !$query )
						{

							$text = mysql_escape_string ( strip_tags ( $_POST['text'] ) );

							SendSimpleMessage ( intval ( $_POST['user'] ) , $CurrentUser['id'] , '' , 1 , $CurrentUser['username'] , $lang['bu_to_accept_title'] , str_replace ( '%u' , $CurrentUser['username'] , $lang['bu_to_accept_text'] ) );

							doquery ( "INSERT INTO {{table}} SET `sender`='" . intval ( $CurrentUser[id] ) . "', `owner`='" . intval ( $_POST['user'] ) . "', `active`='0', `text`='" . $text . "'" , "buddy" );

							header("location:game.php?page=buddy");
						}
						else
						{
							message ( $lang['bu_request_exists'] , 'game.php?page=buddy' , 2 );
						}

						break;
						// ANY OTHER OPTION EXIT
					default:

						header("location:game.php?page=buddy");

						break;
				}

				break;

				// FRIENDSHIP REQUEST
			case 2:

				// IF USER = REQUESTED USER, SHOW ERROR.
				if ( $user == $CurrentUser['id'] )
				{
					message ( $lang['bu_cannot_request_yourself'] , 'game.php?page=buddy' , 2 );
				}
				else
				{
					// SEARCH THE PLAYER
					$player				= doquery ( "SELECT `username` FROM {{table}} WHERE `id`='" . intval ( $user ) . "'" , "users" , TRUE );

					// IF PLAYER EXISTS, PROCEED
					if ( $player )
					{
						$parse['user']		= $user;
						$parse['player']	= $player['username'];

						display ( parsetemplate( gettemplate ( 'buddy/buddy_request' ) , $parse ) );
					}
					else // EXIT
					{
						header("location:game.php?page=buddy");
					}
				}

				break;

				// NOTHING SELECTED
			default:

				$getBuddys 		= doquery ( "SELECT * FROM {{table}} WHERE `sender`='" . intval ( $CurrentUser[id] ) . "' OR `owner`='" . intval ( $CurrentUser[id] ) . "'" , "buddy" );
				$subTemplate	= gettemplate ( 'buddy/buddy_row' );

				while ( $buddy = mysql_fetch_assoc ( $getBuddys ) )
				{
					if ( $buddy['active'] == 0 )
					{
						if ( $buddy['sender'] == $CurrentUser['id'] )
						{
							$owner = doquery ( "SELECT `id`, `username`, `galaxy`, `system`, `planet`,`ally_id`, `ally_name` FROM {{table}} WHERE `id`='" . intval ( $buddy[owner] ) . "'" , "users" , TRUE );

							$parse['id']				= $owner['id'];
							$parse['username']			= $owner['username'];
							$parse['ally_id']			= $owner['ally_id'];
							$parse['ally_name']			= $owner['ally_name'];
							$parse['galaxy']			= $owner['galaxy'];
							$parse['system']			= $owner['system'];
							$parse['planet']			= $owner['planet'];
							$parse['text']				= $buddy['text'];
							$parse['action']			= '<a href="game.php?page=buddy&mode=1&sm=1&bid=' . $buddy[id] . '">' . $lang['bu_cancel_request'] . '</a>';

							$requestsSended .= parsetemplate ( $subTemplate , $parse );
						}
						else
						{
							$sender	= doquery ( "SELECT `id`, `username`, `galaxy`, `system`, `planet`,`ally_id`, `ally_name` FROM {{table}} WHERE `id`='" . intval ( $buddy[sender] ) . "'" , "users" , TRUE );

							$parse['id']				= $sender['id'];
							$parse['username']			= $sender['username'];
							$parse['ally_id']			= $sender['ally_id'];
							$parse['ally_name']			= $sender['ally_name'];
							$parse['galaxy']			= $sender['galaxy'];
							$parse['system']			= $sender['system'];
							$parse['planet']			= $sender['planet'];
							$parse['text']				= $buddy['text'];
							$parse['action']			= '<a href="game.php?page=buddy&mode=1&sm=2&bid=' . $buddy[id] . '">' . $lang['bu_accept'] . '</a><br /><a href="game.php?page=buddy&mode=1&sm=1&bid=' . $buddy[id] . '">' . $lang['bu_decline'] . '</a>';

							$requestsReceived .= parsetemplate ( $subTemplate , $parse );
						}
					}
					else
					{
						if ( $buddy['sender'] == $CurrentUser['id'] )
						{
							$owner = doquery ( "SELECT `id`, `username`, `onlinetime`, `galaxy`, `system`, `planet`,`ally_id`, `ally_name` FROM {{table}} WHERE `id`='" . intval ( $buddy[owner] ) . "'" , "users" , TRUE );
						}
						else
						{
							$owner = doquery ( "SELECT `id`, `username`, `onlinetime`, `galaxy`, `system`, `planet`,`ally_id`, `ally_name` FROM {{table}} WHERE `id`='" . intval ( $buddy[sender] ) . "'" , "users" , TRUE );
						}

						$parse['id']				= $owner['id'];
						$parse['username']			= $owner['username'];
						$parse['ally_id']			= $owner['ally_id'];
						$parse['ally_name']			= $owner['ally_name'];
						$parse['galaxy']			= $owner['galaxy'];
						$parse['system']			= $owner['system'];
						$parse['planet']			= $owner['planet'];
						$parse['text']				= '<font color="' . ( ( $owner["onlinetime"] + 60 * 10 >= time() ) ? 'lime">' . $lang['bu_connected'] . "" : ( ( $owner["onlinetime"] + 60 * 15 >= time() )? 'yellow">' . $lang['bu_fifteen_minutes'] : 'red">' . $lang['bu_disconnected'] ) ) . '</font>';
						$parse['action']			= '<a href="game.php?page=buddy&mode=1&sm=1&bid=' . $buddy[id] . '">' . $lang['bu_delete'] . '</a>';

						$budys .= parsetemplate ( $subTemplate , $parse );
					}
				}

				$parse['request_received']	= $requestsSended;
				$parse['request_sended']	= $requestsReceived;
				$parse['buddys']			= $budys;

				display ( parsetemplate( gettemplate ( 'buddy/buddy_body' ) , $parse ) );

				break;
		}
	}
}
?>