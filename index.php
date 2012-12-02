<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

define('INSIDE', TRUE);
define('INSTALL', FALSE);
define('LOGIN', TRUE);
define('XN_ROOT', realpath('./').'/');

$InLogin = TRUE;

include(XN_ROOT.'global.php');

includeLang('PUBLIC');

$parse = $lang;
$page	= isset($_GET['page']) ? $_GET['page'] : NULL;

switch ($page)
{
	case'lostpassword':
		function sendnewpassword($mail)
		{
			global $lang;

			$ExistMail = doquery("SELECT `email` FROM `{{table}}` WHERE `email` = '".$mail."' LIMIT 1;", 'users', TRUE);

			if (empty($ExistMail['email']))
			{
				message($lang['mail_not_exist'], "index.php?page=lostpassword",2, FALSE, FALSE);
			}
			else
			{
				$Caracters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$NewPass = '';
				for ($i=0; $i < 8; $i++)
				{
					$NewPass .= substr($pool, mt_rand(0, 61), 1);
				}

				$Title 	= $lang['mail_title'];
				$Body 	= $lang['mail_text'];
				$Body  .= $NewPass;
				mail($mail, $Title, $Body);
				$NewPassSql = sha1($NewPass);
				$QryPassChange = "UPDATE `{{table}}` SET ";
				$QryPassChange .= "`password` ='".$NewPassSql."' ";
				$QryPassChange .= "WHERE `email`='".$mail."' LIMIT 1;";
				doquery($QryPassChange, 'users');
			}
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			sendnewpassword ($_POST['email']);
			message($lang['mail_sended'], GAMEURL, 2, FALSE, FALSE);
		}
		else
		{
			$parse['year']		   = date("Y");
			$parse['version']	   = VERSION;
			$parse['forum_url']    = read_config('forum_url');

			display(parsetemplate(gettemplate('public/lostpassword'), $parse), FALSE, '', FALSE, FALSE);
		}
	break;
	default:
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$login = doquery("SELECT `id`,`username`,`password`,`banaday` FROM `{{table}}` WHERE `username` = '".$db->real_escape_string($_POST['username'])."' && `password` = '". sha1($_POST['password'])."' LIMIT 1", "users", TRUE);

			if ($login['banaday'] <= time() && $login['banaday'] != '0')
			{
				doquery("UPDATE `{{table}}` SET `banaday` = '0', `bana` = '0' WHERE `username` = '".$login['username']."' LIMIT 1;", 'users');
				doquery("DELETE FROM `{{table}}` WHERE `who` = '".$login['username']."'",'banned');
			}

			if ($login)
			{
				if (isset($_POST["rememberme"]))
				{
					$expiretime = time() + 31536000;
					$rememberme = 1;
				}
				else
				{
					$expiretime = 0;
					$rememberme = 0;
				}

				@include('config.php');
				$cookie = $login["id"]."/%/".$login["username"]."/%/". md5($login["password"]."--".$dbsettings["secretword"])."/%/".$rememberme;
				setcookie(read_config('cookie_name'), $cookie, $expiretime, "/", "", 0);

				doquery("UPDATE `{{table}}` SET `current_planet` = `id_planet` WHERE `id` ='".$login["id"]."'", 'users');

				unset($dbsettings);
				header('location:game.php?page=overview');
				exit;
			}
			else
			{
				message($lang['login_error'], GAMEURL, 2, FALSE, FALSE);
			}
		}
		else
		{
			$parse['year']		   = date("Y");
			$parse['version']	   = VERSION;
			$parse['servername']   = read_config('game_name');
			$parse['forum_url']    = read_config('forum_url');

			display(parsetemplate(gettemplate('public/index_body'), $parse), FALSE, '', FALSE, FALSE);
		}
}
?>