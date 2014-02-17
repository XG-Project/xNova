<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location: ./../../"));
if ( ! ADM_CONFIGURATION) die(message($lang['not_enough_permissions']));

class ShowErrorsPage {

	public function __construct()
	{
		global $lang, $user;
		$parse = $lang;

		$errors	= isset($_GET['errors']) ? $_GET['errors'] : NULL;

		switch ($errors)
		{
			case 'sql':
				if (isset($_GET['delete']) && is_numeric($_GET['delete']))
				{
					doquery("DELETE FROM `{{table}}` WHERE `error_id`='".$_GET['delete']."'", 'errors');
					$Log	=	"\n".$lang['log_errores_title']."\n";
					$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_errors']."\n";
					LogFunction($Log, "general");
				}
				elseif (isset($_GET['deleteall']) && $_GET['deleteall'] === 'yes')
				{
					doquery("DELETE FROM `{{table}}` WHERE `error_type` != 'PHP'", 'errors');
					$Log	=	"\n".$lang['log_errores_title']."\n";
					$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_all_sql_errors']."\n";
					LogFunction($Log, "general");
				}

				$query = doquery("SELECT * FROM `{{table}}` WHERE `error_type` != 'PHP' ORDER BY `error_time`", 'errors');
				$i = 0;
				$parse['errors_list'] = '';

				while ($u = $query->fetch_assoc())
				{
					$i++;

					$parse['errors_list']	.= '<div class="row">';
					$parse['errors_list']	.= '<div class="content">'.$u['error_id'].'</div>';
					$parse['errors_list']	.= '<div class="content">'.(( ! $u['error_sender']) ? $lang['er_public'] : $u['error_sender']).'</div>';
					$parse['errors_list']	.= '<div class="content">'.date('d/m/Y h:i:s', $u['error_time']).'</div>';
					$parse['errors_list']	.= '<div class="content">'.nl2br($u['error_text']).'</div>';
					$parse['errors_list']	.= '<div class="content"><a href="admin.php?page=errors&amp;errors=sql&amp;delete='.$u['error_id'].'" title="'.$lang['button_delete'].'"><figure class="false"></figure></a></div>';
					$parse['errors_list']	.= '</div>';
				}
				$parse['total_errors'] = $i;
				display(parsetemplate(gettemplate('adm/SQLerrorMessagesBody'), $parse), TRUE, '', TRUE);
			break;
			case 'php':
				if (isset($_GET['delete']) && is_numeric($_GET['delete']))
				{
					doquery("DELETE FROM `{{table}}` WHERE `error_id`='".$_GET['delete']."'", 'errors');
					$Log	=	"\n".$lang['log_errores_title']."\n";
					$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_errors']."\n";
					LogFunction($Log, "general");
				}
				elseif (isset($_GET['deleteall']) && $_GET['deleteall'] === 'yes')
				{
					doquery("DELETE FROM `{{table}}` WHERE `error_type` = 'PHP'", 'errors');
					$Log	=	"\n".$lang['log_errores_title']."\n";
					$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_all_php_errors']."\n";
					LogFunction($Log, "general");
				}

				$error_level	= array('32767', '8192', '4096', '2048', '8', '2');
				$show			= array();
				foreach ($error_level as $error)
				{
					$parse['checked_'.$error] = '';
					if ( ! isset($_POST['submit']) OR (isset($_POST['show_'.$error]) && $_POST['show_'.$error]))
					{
						$show[$error] = TRUE;
						$parse['checked_'.$error] = ' checked';
					}
				}

				if ( ! empty($show))
				{
					$filter	= ' && (';
					$i		= 0;
					$total	= count($show);
					foreach ($show as $key => $value)
					{
						$i++;
						$filter .= "`error_level` = '".$key."'";
						if ($i != $total) $filter .= ' OR ';
					}
					$filter .= ')';
				}

				$query					= doquery("SELECT * FROM `{{table}}` WHERE `error_type` = 'PHP'".$filter." ORDER BY `error_file` ASC, `error_line` ASC", 'errors');
				$i						= 0;
				$error_text				= array('E_ALL', 'E_DEPRECATED', 'E_RECOVERABLE_ERROR', 'E_STRICT', 'E_NOTICE', 'E_WARNING');
				$parse['errors_list']	= '';

				while ($u = $query->fetch_assoc())
				{
					$i++;

					$parse['errors_list']	.= '<div class="row">';
					$parse['errors_list']	.= '<div class="content">'.$u['error_id'].'</div>';
					$parse['errors_list']	.= '<div class="content">'.date('d/m/Y H:i:s', $u['error_time']).'</div>';
					$parse['errors_list']	.= '<div class="content">'.(( ! $u['error_sender']) ? $lang['er_public'] : $u['error_sender']).'</div>';
					$parse['errors_list']	.= '<div class="content">'.str_replace($error_level, $error_text, $u['error_level']).'</div>';
					$parse['errors_list']	.= '<div class="content">'.$u['error_file'].'</div>';
					$parse['errors_list']	.= '<div class="content">'.$u['error_line'].'</div>';
					$parse['errors_list']	.= '<div class="content">'.str_replace('%lang%', $lang['lang_key'], $u['error_text']).'</div>';
					$parse['errors_list']	.= '<div class="content"><a href="admin.php?page=errors&amp;errors=php&amp;delete='.$u['error_id'].'" title="'.$lang['button_delete'].'"><figure class="false"></figure></a></div>';
					$parse['errors_list']	.= '</div>';
				}
				$parse['total_errors']		= $i;
				display(parsetemplate(gettemplate('adm/PHPerrorMessagesBody'), $parse), TRUE, '', TRUE);
			break;
			default:
				display(parsetemplate(gettemplate('adm/ErrorMenu'), $lang), TRUE, '', TRUE);
		}
	}
}


/* End of file class.ShowErrorsPage.php */
/* Location: ./includes/pages/adm/class.ShowErrorsPage.php */