<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header ( 'location:../../' ));}

class debug
{
	var $log,$numqueries;

	function __construct()
	{
		$this->vars = $this->log = '';
		$this->numqueries = 0;
	}

	function add($mes)
	{
		$this->log .= $mes;
		$this->numqueries++;
	}

	function echo_log()
	{
		return  "<br><table><tr><td class=k colspan=4><a href=".XGP_ROOT."adm/settings.php>Debug Log</a>:</td></tr>".$this->log."</table>";
		die();
	}

	function error($message,$title)
	{
		global $link, $lang, $user;

		if ( read_config ( 'debug' ) == 1 )
		{
			echo "<h2>$title</h2><br><font color=red>$message</font><br><hr>";
			echo  "<table>".$this->log."</table>";
		}

		include(XGP_ROOT . 'config.php');

		if(!$link)
			die($lang['cdg_mysql_not_available']);

		$query = "INSERT INTO {{table}} SET
		`error_sender` = '".intval($user['id'])."' ,
		`error_time` = '".time()."' ,
		`error_type` = '".mysql_escape_string($title)."' ,
		`error_text` = '".mysql_escape_string($message)."';";

		$sqlquery = mysql_query(str_replace("{{table}}", $dbsettings["prefix"].'errors',$query)) or die($lang['cdg_fatal_error']);

		$query = "explain select * from {{table}}";

		$q = mysql_fetch_array(mysql_query(str_replace("{{table}}", $dbsettings["prefix"].'errors', $query))) or die($lang['cdg_fatal_error'].': ');

		if (!function_exists('message'))
			echo $lang['cdg_error_message']." <b>".$q['rows']."</b>";
		else
			message($lang['cdg_error_message']." <b>".$q['rows']."</b>", '', '', FALSE, FALSE);

		die();
	}
}
?>