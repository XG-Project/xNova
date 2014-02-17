<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);
define('XGP_ROOT', './../');

include(XGP_ROOT . 'global.php');
include('AdminFunctions/Autorization.php');

if ($EditUsers != 1) die();

$parse	=	$lang;

$name		=	$_POST['name'];
$pass 		= 	md5($_POST['password']);
$email 		= 	$_POST['email'];
$galaxy		=	$_POST['galaxy'];
$system		=	$_POST['system'];
$planet		=	$_POST['planet'];
$auth		=	$_POST['authlevel'];
$time		=	time();
$i			=	0;
if ($_POST)
{
	$CheckUser = doquery("SELECT `username` FROM {{table}} WHERE `username` = '" . mysql_escape_value($_POST['name']) . "' LIMIT 1", "users", TRUE);
	$CheckMail = doquery("SELECT `email` FROM {{table}} WHERE `email` = '" . mysql_escape_value($_POST['email']) . "' LIMIT 1", "users", TRUE);
	$CheckRows = doquery("SELECT * FROM {{table}} WHERE `galaxy` = '".$galaxy."' AND `system` = '".$system."' AND `planet` = '".$planet."' LIMIT 1", "galaxy", TRUE);


	if (!is_numeric($galaxy) &&  !is_numeric($system) && !is_numeric($planet)){
		$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_only_numbers'].'</tr></th>';
		$i++;}
	elseif ($galaxy > MAX_GALAXY_IN_WORLD || $system > MAX_SYSTEM_IN_GALAXY || $planet > MAX_PLANET_IN_SYSTEM || $galaxy < 1 || $system < 1 || $planet < 1){
		$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_error_coord'].'</tr></th>';
		$i++;}

	if (!$name || !$pass || !$email || !$galaxy || !$system || !$planet){
		$parse['display']	.=	'<tr><th colspan="2" class="red">'.$lang['new_complete_all'].'</tr></th>';
		$i++;}

	if (!valid_email(strip_tags($email))){
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
		$Query1  = "INSERT INTO {{table}} SET ";
		$Query1 .= "`username` = '" . mysql_escape_value(strip_tags($name)) . "', ";
		$Query1 .= "`email` = '" . mysql_escape_value($email) . "', ";
		$Query1 .= "`email_2` = '" . mysql_escape_value($email) . "', ";
		$Query1 .= "`ip_at_reg` = '" . $_SERVER["REMOTE_ADDR"] . "', ";
		$Query1 .= "`id_planet` = '0', ";
		$Query1 .= "`register_time` = '" .$time. "', ";
		$Query1 .= "`onlinetime` = '" .$time. "', ";
		$Query1 .= "`authlevel` = '" .$auth. "', ";
		$Query1 .= "`password`='" . $pass . "';";
		doquery($Query1, "users");

		$ID_USER 	= doquery("SELECT `id` FROM {{table}} WHERE `username` = '" . mysql_escape_value($name) . "' LIMIT 1", "users", TRUE);

		CreateOnePlanetRecord ($galaxy, $system, $planet, $ID_USER['id'], $UserPlanet, TRUE);

		$ID_PLANET 	= doquery("SELECT `id` FROM {{table}} WHERE `id_owner` = '". $ID_USER['id'] ."' LIMIT 1" , "planets", TRUE);

		doquery("UPDATE {{table}} SET `id_level` = '".$auth."' WHERE `id` = '".$ID_PLANET['id']."'", "planets");

		$QryUpdateUser = "UPDATE {{table}} SET ";
		$QryUpdateUser .= "`id_planet` = '" . $ID_PLANET['id'] . "', ";
		$QryUpdateUser .= "`current_planet` = '" . $ID_PLANET['id'] . "', ";
		$QryUpdateUser .= "`galaxy` = '" . $galaxy . "', ";
		$QryUpdateUser .= "`system` = '" . $system . "', ";
		$QryUpdateUser .= "`planet` = '" . $planet . "' ";
		$QryUpdateUser .= "WHERE ";
		$QryUpdateUser .= "`id` = '" . $ID_USER['id'] . "' ";
		$QryUpdateUser .= "LIMIT 1;";
		doquery($QryUpdateUser, "users");

		$parse['display']	=	'<tr><th colspan="2"><font color=lime>'.$lang['new_user_success'].'</font></tr></th>';
	}
}



display(parsetemplate(gettemplate('adm/CreateNewUserBody'), $parse), FALSE, '', TRUE, FALSE);
?>
