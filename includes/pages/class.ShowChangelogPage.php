<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location:../../"));

class ShowChangelogPage
{
	public function __construct()
	{
		global $lang;

		includeLang('CHANGELOG');
		$template	=	gettemplate('changelog/changelog_table');

		foreach ($lang['changelog'] as $version => $description)
		{
			$parse['version_number']	= $version;
			$parse['description'] 		= nl2br($description);

			$body .= parsetemplate($template, $parse);
		}

		$parse 			= $lang;
		$parse['body'] 	= $body;

		display(parsetemplate(gettemplate('changelog/changelog_body'), $parse));
	}
}
?>