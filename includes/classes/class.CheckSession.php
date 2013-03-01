<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header('location:../../'));

class CheckSession {

	private function CheckCookies ($IsUserChecked)
	{
		global $lang, $db;

		$UserRow = array();

		require(XN_ROOT.'config.php');

		$game_cookie	= read_config('cookie_name');

		if (isset($_COOKIE[$game_cookie]))
		{
			$TheCookie	= explode("/%/", $_COOKIE[$game_cookie]);

			// START FIX BY JSTAR
			$TheCookie	= array_map(array($db, 'real_escape_string'), $TheCookie);
			// END FIX BY JSTAR

			// BETTER QUERY BY LUCKY! REDUCE GENERAL QUERY FROM 11 TO 10.
			$UserResult = doquery("SELECT {{table}}users.*, {{table}}statpoints.total_rank, {{table}}statpoints.total_points
									FROM {{table}}statpoints
									RIGHT JOIN {{table}}users ON {{table}}statpoints.id_owner = {{table}}users.id
									WHERE ({{table}}users.username = '{$TheCookie[1]}') LIMIT 1;", '');


			if ($UserResult->num_rows != 1)
			{
				message($lang['ccs_multiple_users'], GAMEURL, 5, FALSE, FALSE);
			}

			$UserRow	= $UserResult->fetch_array();

			if ($UserRow["id"] != $TheCookie[0])
			{
				message($lang['ccs_other_user'], GAMEURL, 5,  FALSE, FALSE);
			}

			if (md5($UserRow["password"]."--".$dbsettings["secretword"]) !== $TheCookie[2])
			{
				message($lang['css_different_password'], GAMEURL, 5,  FALSE, FALSE);
			}

			$NextCookie = implode("/%/", $TheCookie);

			if ($TheCookie[3] == 1)
			{
				$ExpireTime = time() + 31536000;
			}
			else
			{
				$ExpireTime = 0;
			}

			if ( ! $IsUserChecked)
				setcookie($game_cookie, $NextCookie, $ExpireTime, "/", "", FALSE, TRUE);

			$QryUpdateUser  = "UPDATE `{{table}}` SET ";
			$QryUpdateUser .= "`onlinetime` = '".time()."', ";
			$QryUpdateUser .= "`current_page` = '".$db->real_escape_string(htmlspecialchars($_SERVER['REQUEST_URI']))."', ";
			$QryUpdateUser .= "`user_lastip` = '".$db->real_escape_string(htmlspecialchars($_SERVER['REMOTE_ADDR']))."', ";
			$QryUpdateUser .= "`user_agent` = '".$db->real_escape_string(htmlspecialchars($_SERVER['HTTP_USER_AGENT']))."' ";
			$QryUpdateUser .= "WHERE ";
			$QryUpdateUser .= "`id` = '".intval($TheCookie[0])."' LIMIT 1;";
			doquery($QryUpdateUser, 'users');

			$IsUserChecked = TRUE;
		}

		unset($dbsettings);

		$Return['state']  = $IsUserChecked;
		$Return['record'] = $UserRow;

		return $Return;
	}

	public function CheckUser($IsUserChecked)
	{
		global $user, $lang;

		$Result        = $this->CheckCookies($IsUserChecked);
		$IsUserChecked = $Result['state'];

		if ($Result['record'])
		{
			$user = $Result['record'];

			if ($user['bana'] == 1)
			{
				die("<div align=\"center\"><h1>".$lang['css_account_banned_message']."</h1><br> <strong>".$lang['css_account_banned_expire'].date("d-m-y H:i", $user['banaday'])."</strong></div>");
			}

			$RetValue['record'] = $user;
			$RetValue['state']  = $IsUserChecked;
		}
		else
		{
			$RetValue['record'] = array();
			$RetValue['state']  = FALSE;
		}

		return $RetValue;
	}
}


/* End of file class.CheckSession.php */
/* Location: ./includes/classes/class.CheckSession.php */
