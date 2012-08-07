<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ($user['authlevel'] < 1 )
{
	die();
}

function LogFunction($Text, $Estado, $LogCanWork)
{
	global $lang;

	$Archive	=	XN_ROOT."includes/logs/".$Estado.".php";

	if ($LogCanWork == 1 && is_writable($Archive))
	{
		if ( ! file_exists($Archive))
		{
			fopen($Archive, "w+");
			fclose(fopen($Archive, "w+"));
		}

		$FP		 =	fopen ($Archive , "r+" );
		$Date	 =	$Text;
		$Date	.=	$lang['log_operation_succes'];
		$Date	.=	date("d-m-Y H:i:s", time())."\n";

		fputs($FP, $Date);
		fclose($FP);
	}
}


/* End of file LogFunction.php */
/* Location: ./includes/functions/adm/LogFunction.php */