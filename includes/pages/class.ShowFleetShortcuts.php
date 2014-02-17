<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowFleetShortcuts
{
	private $CurrentUser;

	public function __construct($CurrentUser)
	{
		$this->CurrentUser=$CurrentUser;

		if(!empty($_GET['mode']))
		{
			$mode=$_GET['mode'];

			if($mode == "add" && !empty($_POST['galaxy'])&&!empty($_POST['system'])&&!empty($_POST['planet']))
			{
				$this->addFleetShortcuts(mysql_escape_value(strip_tags($_POST["name"])),intval($_POST["galaxy"]),intval($_POST["system"]),intval($_POST["planet"]),intval($_POST["moon"]));
			}
			elseif($mode=="edit" && isset($_GET['a']) && !empty($_POST['galaxy'])&&!empty($_POST['system'])&&!empty($_POST['planet']) )
			{
				$this->saveFleetShortcuts(intval($_GET['a']),mysql_escape_value(strip_tags($_POST["name"])),intval($_POST["galaxy"]),intval($_POST["system"]),intval($_POST["planet"]),intval($_POST["moon"]));
			}
			elseif($mode=="delete" && isset($_GET['a']))
			{
				$this->deleteFleetShortcuts(intval($_GET['a']));
			}
			elseif(isset($_GET['a']))
			{
				$this->showEditPanelWithID(intval($_GET['a']));
			}
			else
			{
				$this->showEditPanel();
			}
		}
		else
		{
			$this->showAll();
		}
	}

	private function showEditPanel()
	{
		global $lang;

		$parse 					= $lang;
		$parse['mode']			="add";
		$parse['visibility'] 	="hidden";

		display(parsetemplate(gettemplate("shortcuts/shortcuts_editPanel"),$parse));
	}

	private function showEditPanelWithID($id)
	{
		global $lang;

		echo "entered";

		$parse					= $lang;
		$parse['shortcut_id'] 	="&a=".$id;
		$parse['mode']			="edit";

		$scarray 				= explode(";", $this->CurrentUser['fleet_shortcut']);
		$c 						= explode(',', $scarray[$id]);

		echo "name=".$id;

		$parse['name']			=$c[0];
		$parse['galaxy']		=$c[1];
		$parse['system']		=$c[2];
		$parse['planet']		=$c[3];
		$parse['moon'.$c[4]]	='selected="selected"';
      	$parse['visibility'] 	="button";

		display(parsetemplate(gettemplate("shortcuts/shortcuts_editPanel"),$parse));
	}

	private function saveFleetShortcuts($id,$name,$galaxy,$system,$planet,$moon)
	{
		$scarray 		= explode(";", $this->CurrentUser['fleet_shortcut']);
		$scarray[$id]	="{$name},{$galaxy},{$system},{$planet},{$moon};";

		$this->CurrentUser['fleet_shortcut'] = implode(";", $scarray);

		doquery("UPDATE {{table}} SET fleet_shortcut='".($this->CurrentUser['fleet_shortcut'])."' WHERE id=".($this->CurrentUser['id']), "users");

		header("location:game.php?page=shortcuts");
	}

    private function addFleetShortcuts($name,$galaxy,$system,$planet,$moon)
	{
		$this->CurrentUser['fleet_shortcut'] .= "{$name},{$galaxy},{$system},{$planet},{$moon};";

		doquery("UPDATE {{table}} SET fleet_shortcut='".($this->CurrentUser['fleet_shortcut'])."' WHERE id=".($this->CurrentUser['id']), "users");

		header("location:game.php?page=shortcuts");
	}

	private function deleteFleetShortcuts($id)
	{
		$scarray = explode(";", $this->CurrentUser['fleet_shortcut']);

		unset($scarray[$id]);

		$this->CurrentUser['fleet_shortcut'] = implode(";", $scarray);

		doquery("UPDATE {{table}} SET fleet_shortcut='".($this->CurrentUser['fleet_shortcut'])."' WHERE id=".($this->CurrentUser['id']), "users");

		header("location:game.php?page=shortcuts");
	}

	private function showAll()
	{
		global $lang;

		$parse = $lang;

		if ( $this->CurrentUser['fleet_shortcut'] )
		{
			$scarray 	= explode(";", $this->CurrentUser['fleet_shortcut']);
			$sx 		= TRUE;
			$e 			= 0;
			$ShortcutsRowTPL=gettemplate("shortcuts/shortcuts_row");

			foreach ( $scarray as $a => $b )
			{
				if (!empty($b))
				{
					$c = explode(',', $b);

					if ($sx)
					{
						$parse['block_rows'] .= "<tr height=\"20\">";
					}

					$block['shortcut_id']       = $e++;
					$block['shortcut_name']     = $c[0];
					$block['shortcut_galaxy']   = $c[1];
					$block['shortcut_system']   = $c[2];
					$block['shortcut_planet']   = $c[3];

					if ($c[4] == 2)
					{
						$block['shortcut_moon'] =  $lang['fl_debris_shortcut'];
					}
					elseif ($c[4] == 3)
					{
						$block['shortcut_moon'] = $lang['fl_moon_shortcut'];
					}
					else
					{
						$block['shortcut_moon'] = "";
					}

					$parse['block_rows'] .= parsetemplate($ShortcutsRowTPL,$block);

					if (!$sx)
					{
						$parse['block_rows'] .= "</tr>";
					}

					$sx=!$sx;
				}
			}
			if (!$sx)
			{
				$parse['block_rows'] .= "<td>&nbsp;</td></tr>";
			}
		}
		else
		{
			$parse['block_rows'] = "<th colspan=\"2\">".$lang['fl_no_shortcuts']."</th>";
		}

		display(parsetemplate(gettemplate("shortcuts/shortcuts_table"),$parse));
	}
}
?>