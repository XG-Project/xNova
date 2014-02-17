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

class ShowNotesPage
{
	function __construct($CurrentUser)
	{
		global $lang, $db;

		$parse 	= $lang;
		$a 		= isset($_GET['a']) ? (int) $_GET['a'] : 0;
		$n 		= isset($_GET['n']) ? (int) $_GET['n'] : 0;
		$s		= isset($_GET['s']) ? (int) $_GET['s'] : 0;

		if ($s === 1 OR $s === 2)
		{
			$time 		= time();
			$priority 	= isset($_POST['u']) ? (int) $_POST['u'] : 0;
			$title 		= (isset($_POST['title']) && ! empty($_POST['title'])) ? $db->real_escape_string(strip_tags($_POST['title'])) : "Sin título";
			$text 		= (isset($_POST['text']) && ! empty($_POST['text'])) ? str_replace("&lt;br /&gt;", "", stripslashes(strip_tags($db->real_escape_string($_POST['text'])))) : "Sin texto";

			if ($s === 1)
			{
				doquery("INSERT INTO `{{table}}` SET owner=".((int) $CurrentUser['id']).", time=$time, priority=$priority, title='$title', text='$text'", "notes");
				header("Location: ".GAMEURL."game.php?page=notes");
			}
			elseif ($s === 2)
			{
				$id = $n;
				$note_query = doquery("SELECT * FROM `{{table}}` WHERE id=".$id." && owner=".$CurrentUser['id']."", "notes");

				if ( ! $note_query)
					header("Location: ".GAMEURL."game.php?page=notes");

				doquery("UPDATE `{{table}}` SET time=$time, priority=$priority, title='$title', text='$text' WHERE id=".$id."", "notes");
				header("Location: ".GAMEURL."game.php?page=notes");
			}
		}
		elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$deleted = 0;
			foreach ($_POST as $a => $b)
			{
				if (preg_match("/delmes/i", $a) && $b == "y")
				{
					$id = (int) str_replace("delmes", "", $a);
					$note_query = doquery("SELECT * FROM `{{table}}` WHERE id=".$id." && owner=".((int) $CurrentUser['id']))."", "notes");

					if ($note_query)
					{
						$deleted++;
						doquery("DELETE FROM `{{table}}` WHERE `id`=".$id.";","notes");
					}
				}
			}

			header("Location: ".GAMEURL."game.php?page=notes");
		}
		else
		{
			if ($a === 1)
			{
				$parse['c_Options'] = "<option value=2 selected=selected>".$lang['nt_important']."</option>
				<option value=1>".$lang['nt_normal']."</option>
				<option value=0>".$lang['nt_unimportant']."</option>";
				$parse['TITLE'] 	= $lang['nt_create_note'];
				$parse['inputs']  	= "<input type=hidden name=s value=1>";

				display(parsetemplate(gettemplate('notes/notes_form'), $parse), FALSE, '', FALSE, FALSE);

			}
			elseif ($a === 2)
			{
				$note = doquery("SELECT * FROM `{{table}}` WHERE owner=".((int) $CurrentUser['id']))." && id=".$n, 'notes', TRUE);

				if ( ! $note)
					header("Location: ".GAMEURL."game.php?page=notes");

				$selected = array(0 => '', 1 => '', 2 => '');
				$selected[$note['priority']] = ' selected';

				$parse['c_Options'] = '<option value="2"'.$selected[2].'>'.$lang['nt_important'].'</option>
				<option value="1"'.$selected[1].'>'.$lang['nt_normal'].'</option>
				<option value="0"'.$selected[2].'>'.$lang['nt_unimportant'].'</option>';

				$parse['TITLE'] 	= $lang['nt_edit_note'];
				$parse['inputs'] 	= '<input type="hidden" name="s" value="2"><input type="hidden" name="n" value="'.$note['id'].'">';
				$parse['asunto']	= $note['title'];
				$parse['texto']		= $note['text'];

				display(parsetemplate(gettemplate('notes/notes_form'), $parse), FALSE, '', FALSE, FALSE);
			}
			else
			{
				$notes_query = doquery("SELECT * FROM `{{table}}` WHERE owner=".((int) $CurrentUser['id']))." ORDER BY time DESC", 'notes');

				$count 	= 0;
				$list	= '';

				$NotesBodyEntryTPL=gettemplate('notes/notes_body_entry');
				while ($note = $notes_query->fetch_array())
				{
					$count++;

					if ($note["priority"] == 0) $parse['NOTE_COLOR'] = "lime";
					elseif ($note["priority"] == 1) $parse['NOTE_COLOR'] = "yellow";
					elseif ($note["priority"] == 2) $parse['NOTE_COLOR'] = "red";

					$parse['NOTE_ID'] 		= $note['id'];
					$parse['NOTE_TIME'] 	= date("Y-m-d h:i:s", $note["time"]);
					$parse['NOTE_TITLE'] 	= $note['title'];
					$parse['NOTE_TEXT'] 	= strlen($note['text']);

					$list .= parsetemplate($NotesBodyEntryTPL, $parse);

				}

				if ($count == 0)
				{
					$list .= "<tr><th colspan=4>".$lang['nt_you_dont_have_notes']."</th>\n";
				}

				$parse['BODY_LIST'] = $list;

				display(parsetemplate(gettemplate('notes/notes_body'), $parse), FALSE, '', FALSE, FALSE);
			}
		}
	}
}


/* End of file class.ShowNotesPage.php */
/* Location: ./includes/pages/class.ShowNotesPage.php */