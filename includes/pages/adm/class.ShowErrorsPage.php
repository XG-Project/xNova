<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
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
					doquery("DELETE FROM `{{table}}` WHERE `error_id`='".$GET['delete']."'", 'errors');
					$Log	=	"\n".$lang['log_errores_title']."\n";
					$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_errors']."\n";
					LogFunction($Log, "GeneralLog");
				}
				elseif (isset($_GET['deleteall']) && $_GET['deleteall'] === 'yes')
				{
					doquery("DELETE FROM `{{table}}` WHERE `error_type` != 'PHP'", 'errors');
					$Log	=	"\n".$lang['log_errores_title']."\n";
					$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_all_sql_errors']."\n";
					LogFunction($Log, "GeneralLog");
				}

				$query = doquery("SELECT * FROM `{{table}}` WHERE `error_type` != 'PHP' ORDER BY `error_time`", 'errors');
				$i = 0;
				$parse['errors_list'] = '';

				while ($u = $query->fetch_assoc())
				{
					$i++;

					//TODO HTML5
					$parse['errors_list'] .= "
					<tr><td width=\"25\">".$u['error_id']."</td>
					<td width=\"70\">".$u['error_sender']."</td>
					<td width=\"100\">".$u['error_type']."</td>
					<td width=\"230\">".date('d/m/Y h:i:s', $u['error_time'])."</td>
					<td width=\"95\"><a href=\"?page=sql&delete=".$u['error_id']."\" border=\"0\"><img src=\"../styles/images/r1.png\" border=\"0\"></a></td></tr>
					<tr><th colspan=\"5\" class=b>".nl2br($u['error_text'])."</td></tr>";
				}
				$parse['errors_list'] .= "<tr><th class=b colspan=5>".$i.$lang['er_errors']."</th></tr>";
				display(parsetemplate(gettemplate('adm/SQLerrorMessagesBody'), $parse), TRUE, '', TRUE);
			break;
			case 'php':
				if (isset($_GET['delete']) && is_numeric($_GET['delete']))
				{
					doquery("DELETE FROM `{{table}}` WHERE `error_id`='".$_GET['delete']."'", 'errors');
					$Log	=	"\n".$lang['log_errores_title']."\n";
					$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_errors']."\n";
					LogFunction($Log, "GeneralLog");
				}
				elseif (isset($_GET['deleteall']) && $_GET['deleteall'] === 'yes')
				{
					doquery("DELETE FROM `{{table}}` WHERE `error_type` = 'PHP'", 'errors');
					$Log	=	"\n".$lang['log_errores_title']."\n";
					$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_delete_all_php_errors']."\n";
					LogFunction($Log, "GeneralLog");
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
				$parse['errors_list'] .= '<div class"content">'.$i.$lang['er_errors'].'</div>';
				display(parsetemplate(gettemplate('adm/PHPerrorMessagesBody'), $parse), TRUE, '', TRUE);
			break;
			default:
				display(parsetemplate(gettemplate('adm/ErrorMenu'), $lang), TRUE, '', TRUE);
		}
	}
}