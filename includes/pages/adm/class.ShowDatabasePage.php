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

class ShowDatabasePage {

	public function __construct()
	{
		global $lang, $db, $user;
		$parse = $lang;

		$tables				= doquery("SHOW TABLES");
		$parse['tables']	= '';

		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			while ($row = $tables->fetch_assoc())
			{
				foreach ($row as $table)
				{
					if (isset($_POST['optimize']))
					{
						doquery("OPTIMIZE TABLE {$table}", "$table");
						$message	= $lang['od_opt'];
						$Log		= "\n".$lang['log_database_title']."\n".$lang['log_the_user'].$user['username'].$lang['log_database_view'].":\n".$lang['log_data_optimize']."\n";
					}
					elseif (isset($_POST['repair']))
					{
						doquery("REPAIR TABLE {$table}", "$table");
						$message	= $lang['od_rep'];
						$Log		= "\n".$lang['log_database_title']."\n".$lang['log_the_user'].$user['username'].$lang['log_database_view'].":\n".$lang['log_data_repair']."\n";
					}
					elseif (isset($_POST['check']))
					{
						doquery("CHECK TABLE {$table}", "$table");
						$message	= $lang['od_check_ok'];
						$Log		= "\n".$lang['log_database_title']."\n".$lang['log_the_user'].$user['username'].$lang['log_database_view'].":\n".$lang['log_data_check']."\n";
					}

					if ($db->errno)
					{
						$parse['tables']	.= '<div class="row">';
						$parse['tables']	.= '<div class="content">'.$table.'</div>';
						$parse['tables']	.= '<div class="content some_errors">'.$lang['od_not_opt'].'</div>';
						$parse['tables']	.= '</div>';
					}
					else
					{
						$parse['tables']	.= '<div class="row">';
						$parse['tables']	.= '<div class="content">'.$table.'</div>';
						$parse['tables']	.= '<div class="content no_errors">'.$message.'</div>';
						$parse['tables']	.= '</div>';
					}
				}
			}

			LogFunction($Log, "general");
		}
		else
		{
			while ($row = $tables->fetch_assoc())
			{
				foreach ($row as $table)
				{
					$parse['tables']		.= '<div class="row">';
					$parse['tables']		.= '<div class="content">'.$table.'</div>';
					$parse['tables']		.= '<div class="content">'.$lang['od_select_action'].'</div>';
					$parse['tables']		.= '</div>';
				}
			}
		}

		display(parsetemplate(gettemplate('adm/DatabaseViewBody'), $parse), TRUE, '', TRUE);
	}
}


/* End of file class.ShowDatabasePage.php */
/* Location: ./includes/pages/adm/class.ShowDatabasePage.php */