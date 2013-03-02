<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

//TODO
if ( ! defined('INSIDE')) die(header("Location:../../"));

	function IsTechnologieAccessible($user, $planet, $Element)
	{
		global $requeriments, $resource;

		if (isset($requeriments[$Element]))
		{
			$enabled = TRUE;

			foreach ($requeriments[$Element] as $ReqElement => $EleLevel)
			{
				if (@$user[$resource[$ReqElement]] && $user[$resource[$ReqElement]] >= $EleLevel)
				{
					//BREAK
				}
				elseif ($planet[$resource[$ReqElement]] && $planet[$resource[$ReqElement]] >= $EleLevel)
				{
					$enabled = TRUE;
				}
				else
				{
					return FALSE;
				}
			}
			return $enabled;
		}
		else
		{
			return TRUE;
		}
	}
?>