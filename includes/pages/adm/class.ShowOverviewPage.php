<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("location: ./../../"));
if ($user['authlevel'] < 1) die(message($lang['404_page']));

class ShowOverviewPage {

	private function check_updates()
	{
		if (function_exists('file_get_contents'))
			return version_compare(read_config('version'), file_get_contents('http://xnova.razican.com/current.php'), '<');
		else
			return FALSE;
	}

	public function __construct()
	{
		global $lang, $db, $user;
		$parse 		= $lang;

		if(file_exists(XN_ROOT.'install/') OR file_exists(XN_ROOT.'install.php'))
		{
			$Message	.= '<span>'.$lang['ow_install_file_detected'].'</span>';
			$error++;
		}

		if ($user['authlevel'] >= 3)
		{
			if(is_writable(XN_ROOT.'config.php'))
			{
				$Message	.= '<span>'.$lang['ow_config_file_writable'].'</span>';
				$error++;
			}

			if( ! is_writable(XN_ROOT.'includes/bots'))
			{
				$Message	.= '<span>'.$lang['ow_bot_folder_no_writable'].'</span>';
				$error++;
			}

			if( ! is_writable(XN_ROOT.'includes/xml/config.xml'))
			{
				$Message	.= '<span>'.$lang['ow_config_file_no_writable'].'</span>';
				$error++;
			}

			foreach(scandir(XN_ROOT.'includes/logs') as $log_file)
			{
				if($log_file != '.htaccess' && $log_file != 'index.html' && is_file(XN_ROOT.'adm/Log/'.$log_file) && ( ! is_writable(XN_ROOT.'adm/Log/'.$log_file)))
				{
					$Message	.= '<span>'.$lang['ow_log_file_no_writable'].'</span>';
					$error++;
					break;
				}
			}

			$Errors = doquery("SELECT COUNT(*) AS `errors` FROM {{table}} WHERE 1;", 'errors', TRUE);

			if($Errors['errors'] != 0)
			{
				$Message	.= '<span>'.$lang['ow_database_errors'].'</span>';
				$error++;
			}

			if($this->check_updates())
			{
				$Message	.= '<span>'.$lang['ow_old_version'].'</span>';
				$error++;
			}
		}

		if($error != 0)
		{
			$parse['error_message']		=	$Message;
			$parse['error_class']		=	"some_errors";
		}
		else
		{
			$parse['error_message']		= 	$lang['ow_none'];
			$parse['error_class']		=	"no_error";
		}

		display(parsetemplate(gettemplate('adm/OverviewBody'), $parse), TRUE, '', TRUE, TRUE);
	}
}


/* End of file class.ShowOverviewPage.php */
/* Location: ./includes/pages/adm/class.ShowOverviewPage.php */