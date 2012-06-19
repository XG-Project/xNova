<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);

if ( $user['authlevel'] < 1 )
{
	die();
}

function LogFunction ( $Text , $Estado , $LogCanWork )
{
	global $lang;

	$Archive	=	"../adm/Log/" . $Estado . ".php";

	if ( $LogCanWork == 1 )
	{
		if ( !file_exists ( $Archive ) )
		{
			fopen ( $Archive , "w+" );
			fclose ( fopen ( $Archive , "w+" ) );
		}

		$FP		 =	fopen ( $Archive , "r+" );
		$Date	.=	$Text;
		$Date	.=	$lang['log_operation_succes'];
		$Date	.=	date ( "d-m-Y H:i:s" , time() ) . "\n";

		fputs ( $FP , $Date );
		fclose ( $FP );
	}
}

?>
