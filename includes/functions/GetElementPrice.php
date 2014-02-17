<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location:../../"));

	function GetElementPrice ($user, $planet, $Element, $userfactor = TRUE, $level = FALSE)
	{
		global $pricelist, $resource, $lang;

		if ($userfactor && ($level === FALSE))
			$level = (isset($planet[$resource[$Element]])) ? $planet[$resource[$Element]] : $user[$resource[$Element]];

		$is_buyeable = TRUE;

		$array = array(
			'metal'      => $lang['Metal'],
			'crystal'    => $lang['Crystal'],
			'deuterium'  => $lang['Deuterium'],
			'energy_max' => $lang['Energy']
		);

		$text = $lang['fgp_require'];
		foreach ($array as $ResType => $ResTitle)
		{
			if (isset($pricelist[$Element][$ResType]) && $pricelist[$Element][$ResType] != 0)
			{
				$text .= $ResTitle.": ";
				if ($userfactor)
					$cost = floor($pricelist[$Element][$ResType] * pow($pricelist[$Element]['factor'], $level));
				else
					$cost = floor($pricelist[$Element][$ResType]);

				if ($cost > $planet[$ResType])
				{
					$text .= "<b style=\"color:red;\"> <t title=\"-". Format::pretty_number($cost - $planet[$ResType])."\">";
					$text .= "<span class=\"noresources\">". Format::pretty_number($cost)."</span></t></b> ";
					$is_buyeable = FALSE;
				}
				else
					$text .= "<b style=\"color:lime;\">". Format::pretty_number($cost)."</b> ";
			}
		}
		return $text;
	}


/* End of file GetElementPrice.php */
/* Location: ./includes/functions/GetElementPrice.php */