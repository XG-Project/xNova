<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if (AUTHLEVEL < 1)	die(message($lang['404_page']));

function LogFunction($text, $category)
{
	global $lang;

	$log_file	=	XN_ROOT."includes/logs/".$category.".php";

	if ( ! file_exists($log_file) && is_writable(XN_ROOT."includes/logs/")) touch($log_file);

	if (ADM_LOGS && file_exists($log_file) && is_writable($log_file))
	{
		$lf		 =	fopen ($log_file, "r+");
		$data	 =	$text;
		$data	.=	$lang['log_operation_succes'];
		$data	.=	date("d-m-Y H:i:s", time())."\n";

		fwrite($lf, $data);
		fclose($lf);
	}
}


/* End of file LogFunction.php */
/* Location: ./includes/functions/adm/LogFunction.php */