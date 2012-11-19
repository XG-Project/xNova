<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location: ./../../"));

class ShowLogsPage {

	public function __construct()
	{
		global $lang;
		$parse	= $lang;
		$file	= isset($_GET['file']) && file_exists(XN_ROOT.'includes/logs/'.$_GET['file'].'.php') ? XN_ROOT.'includes/logs/'.$_GET['file'].'.php' : NULL;
		$option	= isset($_GET['option']) ? $_GET['option'] : NULL;

		switch ($option)
		{
			case 'delete':
				if (AUTHLEVEL < 3) die(message($lang['not_enough_permissions']));
				if (is_null($file)) header('Location: admin.php?page=logs');

				$fp	= fopen($file, "w");
				fclose($fp);

				message($lang['log_delete_succes'].$_GET['file'], "admin.php?page=logs&options=links&file=".$_GET['file'], 2);
			break;

			case 'edit':
				if (AUTHLEVEL < 3) die(message($lang['not_enough_permissions']));
				if (is_null($file)) header('Location: admin.php?page=logs');

				if ($_SERVER['REQUEST_METHOD'] === 'POST')
				{
					$fp	= fopen($file, "w");
					fwrite($fp, str_replace('\r\n', PHP_EOL, $_POST['text']));
					fclose($fp);

					message($lang['log_edit_succes'], "admin.php?page=logs&option=edit&file=".$_GET['file'], 2);
				}

				$parse['content']	= file_get_contents($file);
				$parse['file']		= $_GET['file'];
				$parse['filename']	= $lang['log_file_'.$_GET['file']];

				display(parsetemplate(gettemplate('adm/LogEditBody'), $parse), TRUE, '', TRUE);
			break;

			case 'links':
				if (is_null($file)) header('Location: admin.php?page=logs');

				$edt_del			= AUTHLEVEL !== 3 ? $lang['log_log_title_22'] :
					'<a href="admin.php?page=logs&option=delete&file='.$_GET['file'].'" onclick="return confirm(\''.$lang['log_alert'].'\');">'.
					' ['.$lang['log_delete_link'].']</a><a href="admin.php?page=logs&option=edit&file='.$_GET['file'].'">['.$lang['log_edit_link'].']</a>';

				$parse['content']	= '<h3>'.$edt_del.'</h3>';
				$parse['content']	.= '<div class="content">'.$lang['log_file_'.$_GET['file']].' ('.round((filesize($file)/1024), 3).' KiB)'.'</div><div class="content">';
				$parse['content']	.= filesize($file) === 0 ? $lang['log_filesize_0'] : nl2br(file_get_contents($file));
				$parse['content']	.= '</div>';

				display(parsetemplate(gettemplate('adm/LogBody'), $parse), TRUE, '', TRUE);
			break;

			default:
				display(parsetemplate(gettemplate('adm/LogBody'), $parse), TRUE, '', TRUE);
		}
	}
}


/* End of file class.ShowLogsPage.php */
/* Location: ./includes/pages/adm/class.ShowLogsPage.php */