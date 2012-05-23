<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowChangelogPage
{
	public function __construct()
	{
		global $lang;

		includeLang ( 'CHANGELOG' );
		$template	=	gettemplate ( 'changelog/changelog_table' );

		foreach ( $lang['changelog'] as $version => $description )
		{
			$parse['version_number']	= $version;
			$parse['description'] 		= nl2br ( $description );

			$body .= parsetemplate ( $template , $parse );
		}

		$parse 			= $lang;
		$parse['body'] 	= $body;

		display ( parsetemplate ( gettemplate ( 'changelog/changelog_body' ) , $parse ) );
	}
}
?>