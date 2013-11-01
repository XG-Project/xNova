<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com
 * @author	Razican <admin@razican.com>
 */

define('INSIDE' , TRUE);
define('INSTALL', FALSE);
define('IN_ADMIN', TRUE);
define('XN_ROOT', './../');

require(XN_ROOT.'global.php');
require('AdminFunctions/Autorization.php');

if ($EditUsers != 1) die();

$parse	=	$lang;

$name		=	$_POST['name'];
$pass 		= 	sha1($_POST['password']);
$email 		= 	$_POST['email'];
$galaxy		=	$_POST['galaxy'];
$system		=	$_POST['system'];
$planet		=	$_POST['planet'];
$auth		=	$_POST['authlevel'];
$time		=	time();
$i			=	0;
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$CheckUser = doquery("SELECT `username` FROM `{{table}}` WHERE `username` = '".$db->real_escape_string($_POST['name'])."' LIMIT 1", "users", TRUE);
	$CheckMail = doquery("SELECT `email` FROM `{{table}}` WHERE `email` = '".$db->real_escape_string($_POST['email'])."' LIMIT 1", "users", TRUE);
	$CheckRows = doquery("SELECT * FROM `{{table}}` WHERE `galaxy` = '".$galaxy."' && `system` = '".$system."' && `planet` = '".$planet."' LIMIT 1", "galaxy", TRUE);

	if ( ! is_numeric($galaxy) &&  ! is_numeric($system) && ! is_numeric($planet)){
		$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_only_numbers'].'</tr></th>';
		$i++;}
	elseif ($galaxy > MAX_GALAXY_IN_WORLD OR $system > MAX_SYSTEM_IN_GALAXY OR $planet > MAX_PLANET_IN_SYSTEM OR $galaxy < 1 OR $system < 1 OR $planet < 1){
		$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_coord'].'</tr></th>';
		$i++;}

	if ( ! $name OR ! $pass OR ! $email OR ! $galaxy OR ! $system OR ! $planet){
		$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_complete_all'].'</tr></th>';
		$i++;}

	if ( ! valid_email(strip_tags($email))){
		$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_email2'].'</tr></th>';
		$i++;}

	if ($CheckUser){
		$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_name'].'</tr></th>';
		$i++;}

	if ($CheckMail){
		$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_email'].'</tr></th>';
		$i++;}

	if ($CheckRows){
		$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_galaxy'].'</tr></th>';
		$i++;}


	if ($i	==	'0'){
		$Query1  = "INSERT INTO `{{table}}` SET ";
		$Query1 .= "`username` = '".$db->real_escape_string(strip_tags($name))."', ";
		$Query1 .= "`email` = '".$db->real_escape_string($email)."', ";
		$Query1 .= "`email_2` = '".$db->real_escape_string($email)."', ";
		$Query1 .= "`ip_at_reg` = '".$_SERVER["REMOTE_ADDR"]."', ";
		$Query1 .= "`id_planet` = '0', ";
		$Query1 .= "`register_time` = '".$time."', ";
		$Query1 .= "`onlinetime` = '".$time."', ";
		$Query1 .= "`authlevel` = '".$auth."', ";
		$Query1 .= "`password`='".$pass."';";
		doquery($Query1, "users");

		update_config('users_amount', read_config('users_amount') + 1);

		$ID_USER 	= doquery("SELECT `id` FROM `{{table}}` WHERE `username` = '".$db->real_escape_string($name)."' LIMIT 1", "users", TRUE);

		CreateOnePlanetRecord ($galaxy, $system, $planet, $ID_USER['id'], $UserPlanet, TRUE);

		$ID_PLANET 	= doquery("SELECT `id` FROM `{{table}}` WHERE `id_owner` = '".$ID_USER['id']."' LIMIT 1", "planets", TRUE);

		doquery("UPDATE `{{table}}` SET `id_level` = '".$auth."' WHERE `id` = '".$ID_PLANET['id']."'", "planets");

		$QryUpdateUser = "UPDATE `{{table}}` SET ";
		$QryUpdateUser .= "`id_planet` = '".$ID_PLANET['id']."', ";
		$QryUpdateUser .= "`current_planet` = '".$ID_PLANET['id']."', ";
		$QryUpdateUser .= "`galaxy` = '".$galaxy."', ";
		$QryUpdateUser .= "`system` = '".$system."', ";
		$QryUpdateUser .= "`planet` = '".$planet."' ";
		$QryUpdateUser .= "WHERE ";
		$QryUpdateUser .= "`id` = '".$ID_USER['id']."' ";
		$QryUpdateUser .= "LIMIT 1;";
		doquery($QryUpdateUser, "users");

		$parse['display']	=	'<tr><th colspan="2"><font color=lime>'.$lang['new_user_success'].'</font></tr></th>';
	}
}



display(parsetemplate(gettemplate('adm/CreateNewUserBody'), $parse), FALSE, '', TRUE, FALSE);
?>