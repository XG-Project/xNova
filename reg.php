<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  ,  TRUE);
define('INSTALL' , FALSE);
define('LOGIN'   ,  TRUE);
define('XGP_ROOT',	'./');

$InLogin = TRUE;

include(XGP_ROOT . 'global.php');

includeLang('PUBLIC');

$parse = $lang;

function sendpassemail ( $emailaddress , $password )
{
	global $lang;

	$email 				= parsetemplate ( $lang['reg_mail_text_part1'] . $password . $lang['reg_mail_text_part2'] . GAMEURL , $parse );
	$status 			= mymail ( $emailaddress , $lang['register_at'] . read_config ( 'game_name' ) , $email );

	return $status;
}

function mymail ( $to , $title , $body , $from = '' )
{
	$from = trim ( $from );

	if ( !$from )
	{
		$from = ADMINEMAIL;
	}

	$rp = ADMINEMAIL;

	$head = '';
	$head .= "Content-Type: text/html \r\n";
	$head  .= "charset: UTF-8 \r\n";
	$head .= "Date: " . date('r') . " \r\n";
	$head .= "Return-Path: $rp \r\n";
	$head .= "From: $from \r\n";
	$head .= "Sender: $from \r\n";
	$head .= "Reply-To: $from \r\n";
	$head .= "Organization: $org \r\n";
	$head .= "X-Sender: $from \r\n";
	$head .= "X-Priority: 3 \r\n";
	$body = str_replace ( "\r\n" , "\n" , $body );
	$body = str_replace ( "\n" , "\r\n" , $body );

	return mail ( $to , $title , $body , $head );
}

if ($_POST)
{
	$errors = 0;
	$errorlist = "";

	$_POST['email'] = strip_tags($_POST['email']);

	if (!valid_email($_POST['email']))
	{
		$errorlist .= $lang['invalid_mail_adress'];
		$errors++;
	}

	if (!$_POST['character'])
	{
		$errorlist .= $lang['empty_user_field'];
		$errors++;
	}

	if (strlen($_POST['passwrd']) < 4)
	{
		$errorlist .= $lang['password_lenght_error'];
		$errors++;
	}

	if (preg_match("/[^A-z0-9_\-]/", $_POST['character']) == 1)
	{
		$errorlist .= $lang['user_field_no_alphanumeric'];
		$errors++;
	}

	if ($_POST['rgt'] != 'on')
	{
		$errorlist .= $lang['terms_and_conditions'];
		$errors++;
	}

	$ExistUser = doquery("SELECT `username` FROM {{table}} WHERE `username` = '" . mysql_escape_value($_POST['character']) . "' LIMIT 1;", 'users', TRUE);
	if ($ExistUser)
	{
		$errorlist .= $lang['user_already_exists'];
		$errors++;
	}

	$ExistMail = doquery("SELECT `email` FROM {{table}} WHERE `email` = '" . mysql_escape_value($_POST['email']) . "' LIMIT 1;", 'users', TRUE);
	if ($ExistMail)
	{
		$errorlist .= $lang['mail_already_exists'];
		$errors++;
	}

	if ($errors != 0)
	{
		message ($errorlist, "reg.php", "3", FALSE, FALSE);
	}
	else
	{
		$newpass	= $_POST['passwrd'];
		$UserName 	= $_POST['character'];
		$UserEmail 	= $_POST['email'];
		$md5newpass = md5($newpass);

		$QryInsertUser = "INSERT INTO {{table}} SET ";
		$QryInsertUser .= "`username` = '" . mysql_escape_value(strip_tags($UserName)) . "', ";
		$QryInsertUser .= "`email` = '" . mysql_escape_value($UserEmail) . "', ";
		$QryInsertUser .= "`email_2` = '" . mysql_escape_value($UserEmail) . "', ";
		$QryInsertUser .= "`ip_at_reg` = '" . $_SERVER["REMOTE_ADDR"] . "', ";
		$QryInsertUser  .= "`user_agent` = '', ";
		$QryInsertUser .= "`id_planet` = '0', ";
		$QryInsertUser .= "`register_time` = '" . time() . "', ";

		$QryInsertUser .= "`password`='" . $md5newpass . "';";
		doquery($QryInsertUser, 'users');

		$NewUser = doquery("SELECT `id` FROM {{table}} WHERE `username` = '" . mysql_escape_value($_POST['character']) . "' LIMIT 1;", 'users', TRUE);

		$LastSettedGalaxyPos = read_config ( 'lastsettedgalaxypos' );
		$LastSettedSystemPos = read_config ( 'lastsettedsystempos' );
		$LastSettedPlanetPos = read_config ( 'lastsettedplanetpos' );

		while (!isset($newpos_checked))
		{
			for ($Galaxy = $LastSettedGalaxyPos; $Galaxy <= MAX_GALAXY_IN_WORLD; $Galaxy++)
			{
				for ($System = $LastSettedSystemPos; $System <= MAX_SYSTEM_IN_GALAXY; $System++)
				{
					for ($Posit = $LastSettedPlanetPos; $Posit <= 4; $Posit++)
					{
						$Planet = round (rand (4, 12));

						switch ($LastSettedPlanetPos)
						{
							case 1:
								$LastSettedPlanetPos += 1;
							break;
							case 2:
								$LastSettedPlanetPos += 1;
							break;
							case 3:
								if ($LastSettedSystemPos == MAX_SYSTEM_IN_GALAXY)
								{
									$LastSettedGalaxyPos += 1;
									$LastSettedSystemPos = 1;
									$LastSettedPlanetPos = 1;
									break;
								}
								else
								{
									$LastSettedPlanetPos = 1;
								}

								$LastSettedSystemPos += 1;
							break;
						}
						break;
					}
					break;
				}
				break;
			}

			$QrySelectGalaxy = "SELECT * ";
			$QrySelectGalaxy .= "FROM {{table}} ";
			$QrySelectGalaxy .= "WHERE ";
			$QrySelectGalaxy .= "`galaxy` = '" . $Galaxy . "' AND ";
			$QrySelectGalaxy .= "`system` = '" . $System . "' AND ";
			$QrySelectGalaxy .= "`planet` = '" . $Planet . "' ";
			$QrySelectGalaxy .= "LIMIT 1;";
			$GalaxyRow = doquery($QrySelectGalaxy, 'galaxy', TRUE);

			if ($GalaxyRow["id_planet"] == "0")
				$newpos_checked = TRUE;

			if (!$GalaxyRow)
			{
				CreateOnePlanetRecord ($Galaxy, $System, $Planet, $NewUser['id'], '', TRUE);
				$newpos_checked = TRUE;
			}
			if ($newpos_checked)
			{
				update_config ( 'lastsettedgalaxypos' , $LastSettedGalaxyPos );
				update_config ( 'lastsettedsystempos' , $LastSettedSystemPos );
				update_config ( 'lastsettedplanetpos' , $LastSettedPlanetPos );
			}
		}
		$PlanetID = doquery("SELECT `id` FROM {{table}} WHERE `id_owner` = '". $NewUser['id'] ."' LIMIT 1;" , 'planets', TRUE);

		$QryUpdateUser = "UPDATE {{table}} SET ";
		$QryUpdateUser .= "`id_planet` = '" . $PlanetID['id'] . "', ";
		$QryUpdateUser .= "`current_planet` = '" . $PlanetID['id'] . "', ";
		$QryUpdateUser .= "`galaxy` = '" . $Galaxy . "', ";
		$QryUpdateUser .= "`system` = '" . $System . "', ";
		$QryUpdateUser .= "`planet` = '" . $Planet . "' ";
		$QryUpdateUser .= "WHERE ";
		$QryUpdateUser .= "`id` = '" . $NewUser['id'] . "' ";
		$QryUpdateUser .= "LIMIT 1;";
		doquery($QryUpdateUser, 'users');

		$from 		= $lang['welcome_message_from'];
		$sender 	= $lang['welcome_message_sender'];
		$Subject 	= $lang['welcome_message_subject'];
		$message 	= $lang['welcome_message_content'];
		SendSimpleMessage($NewUser['id'], $sender, '', 1, $from, $Subject, $message);

		@include('config.php');
		$cookie = $NewUser['id'] . "/%/" . $UserName . "/%/" . md5($md5newpass . "--" . $dbsettings["secretword"]) . "/%/" . 0;
		setcookie(read_config ( 'cookie_name' ), $cookie, 0, "/", "", 0);

		unset($dbsettings);

		header("location:game.php?page=overview");
	}
}
else
{
	$parse['year']		   = date ( "Y" );
	$parse['version']	   = VERSION;
	$parse['servername']   = read_config ( 'game_name' );
	$parse['forum_url']    = read_config ( 'forum_url' );
	display (parsetemplate(gettemplate('public/registry_form'), $parse), FALSE, '',FALSE, FALSE);
}
?>
