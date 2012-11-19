<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location: ./../../"));

class ShowBotsPage {

	public function __construct()
	{
		global $lang;

		if ( ! ADM_CONFIGURATION) die(message($lang['not_enough_permissions']));

		$parse	= $lang;
		$mode	= isset($_GET['mode']) ? $_GET['mode'] : NULL;

		switch ($mode)
		{
			case 'new_bot':
				$user				= isset($_POST['user']) ? $_POST['user'] : NULL;
				$minutes_per_day	= isset($_POST['minutes_per_day']) ? $_POST['minutes_per_day'] : NULL;

				$i	= 0;
				if ($_SERVER['REQUEST_METHOD'] === 'POST')
				{
					$CheckBots = doquery("SELECT `user` FROM `{{table}}` WHERE `user` = '".$db->real_escape_string($_POST['user'])."' ", "bots");
					$CheckUser = doquery("SELECT `id` FROM `{{table}}` WHERE `id` = '".$db->real_escape_string($_POST['user'])."' ", "users");

					if ( ! $user OR ! $minutes_per_day)
					{
						$errors	.= '<span>'.$lang['bot_err_complete_all'].'</span>';
						$i++;
					}

					if ( ! $user)
					{
						$errors	.= '<span>'.$lang['bot_err_complete_user'].'</span>';
						$i++;
					}

					if ($CheckBots->num_rows > 0)
					{
						$errors	.= '<span>'.$lang['bot_err_bot_exist'].'</span>';
						$i++;
					}

					if ($CheckUser->num_rows === 0)
					{
						$errors	.= '<span>'.$lang['bot_err_user_not_exist'].'</span>';
						$i++;
					}

					if ( ! $minutes_per_day OR ! is_numeric($minutes_per_day) OR $minutes_per_day > 1440)
					{
						$errors	.= '<span>'.$lang['bot_err_minutes_per_day'].'</span>';
						$i++;
					}

					if ($i === 0)
					{
						$Query1  = "INSERT INTO `{{table}}` SET ";
						$Query1 .= "`user` = '".$user."', ";
						$Query1 .= "`minutes_per_day` = '".$minutes_per_day."'; ";

						doquery($Query1, "bots");
						update_config('bots', read_config('bots') + 1);

						$parse['errors']	= '<section class="content errors no_errors">Nuevo Bot creado.</section>';
					}
					else
					{
						$parse['errors']	= '<section class="content errors some_errors">'.$errors.'</section>';
					}
				}

				display(parsetemplate(gettemplate('adm/CreateBotBody'), $parse), TRUE, '', TRUE);
			break;
			case 'delete_log':
				$file = fopen(XN_ROOT."includes/logs/bots.php", "w");
				fclose($file);
				message($lang['bot_log_delete_ok'], 'admin.php?page=bots', 2);
			break;
			default:
				$query = doquery("SELECT * FROM {{table}}", 'bots');

				$parse['bots_list'] = '';
				while ($u = $query->fetch_array())
				{
					$parse['bots_list'] .= '<div class="row">'.
											'<div class="content">'.$u['id'].'</div>'.
											'<div class="content">'.$u['user'].'</div>'.
											'<div class="content">'.date('H:i:s - j/n/Y', $u['last_time']).'</div>'.
											'<div class="content">'.date('H:i:s - j/n/Y', $u['next_time']).'</div>'.
											'<div class="content">'.$u['minutes_per_day'].'</div>'.
											'<div class="content">'.$u['last_planet'].'</div>'.
											'<div class="content"><a href="admin.php?page=bots&amp;delete='.$u['id'].'" onclick="return confirm(\''.$lang['bot_delete_confirm'].'\');">'.
												'<figure class="false"></figure></a></div>'.
											'<div class="content"><a href="admin.php?page=bots&amp;id_u='.$u['user'].'" title="'.$lang['button_delete'].'">'.
												'<figure class="arrowright"></figure></a></div>'. //TODO Hay que actualizarlo con el nuevo link
											'</div>';
				}

				$parse['total_bots']	= sprintf($lang['bot_total'], read_config('bots'));

				if (isset($_GET['delete']))
				{
					$bot = doquery('SELECT `user` FROM `{{table}}` WHERE `id`='.$delete, 'bots', TRUE);
					doquery('DELETE FROM `{{table}}` WHERE `id`='.$delete, 'bots');
					update_config('bots', read_config('bots') - 1);
					unlink(XN_ROOT.'includes/bots/'.md5($bot['user']).'.botdb');

					die(header("Location: admin.php?page=bots"));
				}
				elseif (isset($_GET['deleteall']))
				{
					doquery("TRUNCATE TABLE {{table}}", 'bots');
					update_config('bots', 0);

					foreach (scandir(XN_ROOT.'includes/bots/') as $file)
					{
						if (is_file(XN_ROOT.'includes/bots/'.$file))
						{
							unlink(XN_ROOT.'includes/bots/'.$file);
						}
					}

					die(header("Location: admin.php?page=bots"));
				}
				$parse['log'] = file_get_contents(XN_ROOT.'includes/logs/bots.php');

				display(parsetemplate(gettemplate('adm/BotSettingsBody'), $parse), TRUE, '', TRUE);
		}
	}
}


/* End of file class.ShowBotsPage.php */
/* Location: ./includes/pages/adm/class.ShowBotsPage.php */