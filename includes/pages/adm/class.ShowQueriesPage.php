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
if (AUTHLEVEL < 3) die(message($lang['not_enough_permissions']));

class ShowQueriesPage {

	public function __construct()
	{
		global $lang, $db, $user;

		$parse	=	$lang;
		$query	=	isset($_POST['query']) && ! empty($_POST['query']) ? $_POST['query'] : NULL;

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! is_null($query))
		{
			if ( ! $db->query($db->real_escape_string($query)))
			{
				$parse['display']	= '<div class="content some_errors">'.$db->error.'</div>';
			}
			else
			{
				$Log	=	"\n".$lang['log_queries_title']."\n";
				$Log	.=	$lang['log_the_user'].$user['username']." ".$lang['log_queries_succes']."\n";
				$Log	.=	$query."\n";
				LogFunction($Log, "general");

				$parse['display']	= '<div class="content no_errors">'.$lang['qe_succes'].'</div>';
			}
		}

		display(parsetemplate(gettemplate('adm/QueriesBody'), $parse), TRUE, '', TRUE);
	}
}


/* End of file class.ShowQueriesPage.php */
/* Location: ./includes/pages/adm/class.ShowQueriesPage.php */