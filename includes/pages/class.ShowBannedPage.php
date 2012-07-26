<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("location:../../"));

class ShowBannedPage
{
	function ShowBannedPage()
	{
		global $lang;

		$parse	= $lang;
		$query	= doquery ( "SELECT * FROM {{table}} ORDER BY `id`;" , 'banned' );

		$i 				= 0;
		$subTemplate	= gettemplate ( 'banned/banned_row' );

		while ($u = $query->fetch_array())
		{
			$parse['player']	= $u[1];
			$parse['reason']	= $u[2];
			$parse['since']		= date ( "d/m/Y G:i:s" , $u[4] );
			$parse['until']		= date ( "d/m/Y G:i:s" , $u[5] );
			$parse['by']		= $u[6];

			$i++;

			$body .= parsetemplate ( $subTemplate , $parse );
		}

		if ( $i == 0 )
		{
			$parse['banned_msg']	.= $lang['bn_no_players_banned'];
		}
		else
		{
			$parse['banned_msg']	.= $lang['bn_exists'] . $i . $lang['bn_players_banned'];
		}

		$parse['banned_players']	= $body;

		display ( parsetemplate ( gettemplate ( 'banned/banned_body' ) , $parse ) );
	}
}
?>