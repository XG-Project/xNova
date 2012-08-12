<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

define('INSIDE' , TRUE);
define('INSTALL', FALSE);
define('IN_ADMIN', TRUE);
define('XN_ROOT', './../');

include(XN_ROOT.'global.php');

if (AUTHLEVEL < 1) die(message($lang['404_page']));

$parse		=	$lang;
$Archive	=	"Log/".$_GET['file'].".php";

switch ($_GET['options'])
{
	case 'delete':
		if (AUTHLEVEL	!=	3) die();
		$FP	=	fopen($Archive, "w+");
		fclose($FP);

		message($lang['log_delete_succes'].$_GET['file'], "LogToolPage.php?options=links&file=".$_GET['file']."", 2);
	break;

	case 'edit':
		if (AUTHLEVEL	!=	3) die();
		$Fopen		=	fopen($Archive, "r+");

		while ( ! feof($Fopen))
		{
			$parse['display']	.= fgets($Fopen);
		}
		fclose($Fopen);


		if ($_POST['editnow'])
		{
			$Fopen2	=	fopen($Archive, "w+");
			fputs($Fopen2, $_POST['text']);
			fclose($Fopen2);
			message($lang['log_edit_succes'], "LogToolPage.php?options=edit&file=".$_GET['file']."", 2);
		}

		$FileSize				=	filesize($Archive);
		$FinalSize				=	$FileSize / 1000;
		$parse['setsize']		=	"&nbsp;&nbsp;(".$FinalSize." KB)";
		$parse['setarchive']	=	$_GET['file'];

		display(parsetemplate(gettemplate('adm/LogEditBody'), $parse), FALSE, '', TRUE, FALSE);
	break;

	case 'links':
		$Archive	=	"Log/".$_GET['file'].".php";
		if ( ! file_exists($Archive))
		{
			fopen($Archive, "w+");
			fclose(fopen($Archive, "w+"));
		}


		$Log	=	fopen($Archive, "r");


		if (AUTHLEVEL	==	3)
		{
			$Excuse_me		=
			"<a href=\"LogToolPage.php?options=delete&file=".$_GET['file']."\" onclick=\" return confirm('".$lang['log_alert']."');\">
			[".$lang['log_delete_link']."]</a>&nbsp;
			<a href=\"LogToolPage.php?options=edit&file=".$_GET['file']."\">[".$lang['log_edit_link']."]</a>";
		}
		else
		{
			$Excuse_me		=	$lang['log_log_title_22'];
		}
		$EditAndDelete	=
			"<tr><td class=\"c\" colspan=2>".$Excuse_me."</td></tr>";

		$parse['display']	=	$EditAndDelete;
		if (filesize($Archive) == 0)
		{
			$parse['display']	.= "<tr><th align=\"left\" colspan=2>".$lang['log_filesize_0']."</th></tr>";
		}
		else
		{
			$parse['display']	.=	"<tr><th align=\"left\" colspan=2><font color=#E6E6E6>";
			while ( ! feof($Log))
			{
				$parse['display']	.= fgets($Log)."<br>";
			}
			$parse['display']	.=	"</font></th></tr>";
			$parse['display']	.=	$EditAndDelete;
		}

		fclose($Log);

		$FileSize				=	filesize($Archive);
		$FinalSize				=	$FileSize / 1000;
		$parse['setsize']		=	"&nbsp;&nbsp;(".$FinalSize." KB)";
		$parse['setarchive']	=	$_GET['file'];
		display(parsetemplate(gettemplate('adm/LogBody'), $parse), FALSE, '', TRUE, FALSE);
	break;

	default:
		display(parsetemplate(gettemplate('adm/LogBody'), $parse), FALSE, '', TRUE, FALSE);
}
?>