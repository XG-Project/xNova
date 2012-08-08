<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location: ./../../"));
if ($user['authlevel'] < 3) die(message($lang['404_page']));

class ShowQueriesPage {

	public function __construct()
	{
		global $lang, $db, $user;

		$parse	=	$lang;
		$Query	=	isset($_POST['query']) && ! empty($_POST['query']) ? $_POST['query'] : NULL;

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! is_null($Query))
		{
			$FinalQuery	=	$db->real_escape_string($Query);

			if ( ! $db->query($FinalQuery))
			{
				$parse['display']	= '<div class="content some_errors">'.$db->error.'</div>';
			}
			else
			{
				$Log	.=	"\n".$lang['log_queries_title']."\n";
				$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_queries_succes']."\n";
				$Log	.=	$Query."\n";
				LogFunction($Log, "GeneralLog", $LogCanWork);

				$parse['display']	= '<div class="content no_errors">'.$lang['qe_succes'].'</div>';
			}
		}

		display(parsetemplate(gettemplate('adm/QueriesBody'), $parse), TRUE, '', TRUE, TRUE);
	}
}


/* End of file class.ShowQueriesPage.php */
/* Location: ./includes/pages/adm/class.ShowQueriesPage.php */