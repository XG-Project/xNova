<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location: ./../../"));
if ( ! ADM_CONFIGURATION) die(message($lang['404_page']));

class ShowDatabasePage {

	public function __construct()
	{
		global $lang;
		$parse = $lang;

		$tables = doquery("SHOW TABLES");
		//TODO HTML5

		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			while ($row = $tables->fetch_assoc())
			{
				foreach ($row as $table)
				{
					if (isset($_POST['optimize']))
					{
						doquery("OPTIMIZE TABLE {$table}", "$table");
						$Message	= $lang['od_opt'];
						$Log		= "\n".$lang['log_database_title']."\n".$lang['log_the_user'].$user['username'].$lang['log_database_view'].":\n".$lang['log_data_optimize']."\n";
					}
					elseif (isset($_POST['repair']))
					{
						doquery("REPAIR TABLE {$table}", "$table");
						$Message	= $lang['od_rep'];
						$Log		= "\n".$lang['log_database_title']."\n".$lang['log_the_user'].$user['username'].$lang['log_database_view'].":\n".$lang['log_data_repair']."\n";
					}
					elseif (isset($_POST['check']))
					{
						doquery("CHECK TABLE {$table}", "$table");
						$Message	= $lang['od_check_ok'];
						$Log		= "\n".$lang['log_database_title']."\n".$lang['log_the_user'].$user['username'].$lang['log_database_view'].":\n".$lang['log_data_check']."\n";
					}

					if ($db->errno)
					{
						$parse['tabla'] .= "<tr>";
						$parse['tabla'] .= "<th width=\"50%\">".$table."</th>";
						$parse['tabla'] .= "<th width=\"50%\" style=\"color:red\">".$lang['od_not_opt']."</th>";
						$parse['tabla'] .= "</tr>";
					}
					else
					{
						$parse['tabla'] .= "<tr>";
						$parse['tabla'] .= "<th width=\"50%\">".$table."</th>";
						$parse['tabla'] .= "<th width=\"50%\" style=\"color:lime\">".$Message."</th>";
						$parse['tabla'] .= "</tr>";
					}
				}
			}

			LogFunction($Log, "GeneralLog");
		}
		else
		{
			while ($row = $tables->fetch_assoc())
			{
				foreach ($row as $table)
				{
					$parse['tabla'] .= "<tr>";
					$parse['tabla'] .= "<th width=\"50%\">".$table."</th><th width=\"50%\"><font color=aqua>".$lang['od_select_action']."</font></th>";
					$parse['tabla'] .= "</tr>";
				}
			}
		}

		display(parsetemplate(gettemplate('adm/DatabaseViewBody'), $parse), TRUE, '', TRUE, TRUE);
	}
}


/* End of file class.ShowDatabasePage.php */
/* Location: ./includes/pages/adm/class.ShowDatabasePage.php */