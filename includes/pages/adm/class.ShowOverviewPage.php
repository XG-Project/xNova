<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location: ./../../"));

class ShowOverviewPage {

	private function check_updates()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://xnova.razican.com/current.php');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$latest_version	= curl_exec($ch);
		curl_close($ch);

		return ($latest_version && version_compare(read_config('version'), $latest_version, '<'));
	}

	public function __construct()
	{
		global $lang, $db;
		$parse 		= $lang;
		$message	= '';
		$error		= 0;

		if (file_exists(XN_ROOT.'install/') OR file_exists(XN_ROOT.'install.php'))
		{
			$message	.= '<span>'.$lang['ow_install_file_detected'].'</span>';
			$error++;
		}

		if (AUTHLEVEL >= 3)
		{
			if (is_writable(XN_ROOT.'config.php'))
			{
				$message	.= '<span>'.$lang['ow_config_file_writable'].'</span>';
				$error++;
			}

			if ( ! is_writable(XN_ROOT.'includes/bots'))
			{
				$message	.= '<span>'.$lang['ow_bot_folder_no_writable'].'</span>';
				$error++;
			}

			if ( ! is_writable(XN_ROOT.'includes/xml/config.xml'))
			{
				$message	.= '<span>'.$lang['ow_config_file_no_writable'].'</span>';
				$error++;
			}

			foreach (scandir(XN_ROOT.'includes/logs') as $log_file)
			{
				if (is_file(XN_ROOT.'includes/logs/'.$log_file) && ( ! is_writable(XN_ROOT.'includes/logs/'.$log_file)))
				{
					$message	.= '<span>'.$lang['ow_log_file_no_writable'].'</span>';
					$error++;
					break;
				}
			}

			$errors = doquery("SELECT COUNT(*) AS `errors` FROM `{{table}}` WHERE 1;", 'errors', TRUE);

			if ($errors['errors'])
			{
				$message	.= '<span>'.$lang['ow_database_errors'].'</span>';
				$error++;
			}

			if ($this->check_updates())
			{
				$message	.= '<span>'.$lang['ow_old_version'].'</span>';
				$error++;
			}
		}

		if ($error)
		{
			$parse['error_message']		=	$message;
			$parse['error_class']		=	"some_errors";
		}
		else
		{
			$parse['error_message']		= 	$lang['ow_none'];
			$parse['error_class']		=	"no_errors";
		}

		display(parsetemplate(gettemplate('adm/OverviewBody'), $parse), TRUE, '', TRUE);
	}
}


/* End of file class.ShowOverviewPage.php */
/* Location: ./includes/pages/adm/class.ShowOverviewPage.php */