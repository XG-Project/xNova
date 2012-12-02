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

class ShowOfficerPage
{
	public function __construct(&$CurrentUser)
	{
		global $resource, $reslist, $lang;

		$parse 	= $lang;
		$bloc	= $lang;

		if ($_GET['mode'] == 2)
		{
			$Selected    = $_GET['offi'];

			if (in_array($Selected, $reslist['officer']))
			{
				$Result =	$this->IsOfficerAccessible ($CurrentUser, $Selected);
				$Price	=	$this->GetOfficerPrice ($Selected);

				if ($Result)
				{
					$CurrentUser[$resource[$Selected]] += 1;
					$CurrentUser['darkmatter']         -= $Price;

					$QryUpdateUser  = "UPDATE `{{table}}` SET ";
					$QryUpdateUser .= "`darkmatter` = '".$CurrentUser['darkmatter']."', ";
					$QryUpdateUser .= "`".$resource[$Selected]."` = '".$CurrentUser[$resource[$Selected]]."' ";
					$QryUpdateUser .= "WHERE ";
					$QryUpdateUser .= "`id` = '".$CurrentUser['id']."';";
					doquery($QryUpdateUser, 'users');
				}
				else
				{
					header("Location: ".GAMEURL."game.php?page=officer");
				}
			}

			header("Location: ".GAMEURL."game.php?page=officer");

		}
		else
		{
			$OfficerRowTPL			=	gettemplate('officer/officer_row');

			foreach ($lang['tech'] as $Element => $ElementName)
			{
				$Result = $this->IsOfficerAccessible ($CurrentUser, $Element);
				$Price	= $this->GetOfficerPrice ($Element);

				if ($Element >= 601 && $Element <= 604)
				{
					$bloc['dpath']		= DPATH;
					$bloc['off_id']   	= $Element;
					$bloc['off_status']	= (($CurrentUser[$resource[$Element]] == 1) ? $lang['of_active'] : $lang['of_inactive']);
					$bloc['off_name']	= $ElementName;
					$bloc['off_desc'] 	= $lang['res']['descriptions'][$Element];

					if ($Result)
					{
						$bloc['off_link']  = "<font color=\"lime\"><strong>".Format::pretty_number($Price).'</strong><br>'.$lang['Darkmatter']."</font>";
						$bloc['off_link'] .= "<br><a href=\"game.php?page=officer&mode=2&offi=".$Element."\"><font color=\"#00ff00\">".$lang['of_recruit']."</font>";
					}
					else
					{
						$bloc['off_link'] = "<font color=\"red\"><strong>".Format::pretty_number($Price).'</strong><br>'.$lang['Darkmatter']."</font>";
					}
					$parse['disp_off_tbl'] .= parsetemplate($OfficerRowTPL, $bloc);
				}
			}
			$page = parsetemplate(gettemplate('officer/officer_table'), $parse);
		}

		display($page);
	}

	private function IsOfficerAccessible($CurrentUser, $Offcer)
	{
		global $resource, $pricelist;

		if ($CurrentUser[$resource[$Offcer]] < $pricelist[$Offcer]['max'])
		{
			$cost['darkmatter']  = floor($pricelist[$Offcer]['darkmatter']);

			return ( ! ($cost['darkmatter'] > $CurrentUser['darkmatter']));
		}
		else
		{
			return FALSE;
		}
	}

	private function GetOfficerPrice($Officer)
	{
		global $pricelist;

		return floor($pricelist[$Officer]['darkmatter']);
	}
}
?>