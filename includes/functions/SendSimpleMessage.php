<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

	function SendSimpleMessage ( $Owner, $Sender, $Time, $Type, $From, $Subject, $Message)
	{

		if ($Time == '')
		{
			$Time = time();
		}

		$Message = (strpos($Message, "/adm/") === FALSE ) ? $Message : "";

		$QryInsertMessage  = "INSERT INTO {{table}} SET ";
		$QryInsertMessage .= "`message_owner` 	= '". $Owner 	."', ";
		$QryInsertMessage .= "`message_sender` 	= '". $Sender 	."', ";
		$QryInsertMessage .= "`message_time` 	= '". $Time 	."', ";
		$QryInsertMessage .= "`message_type` 	= '". $Type 	."', ";
		$QryInsertMessage .= "`message_from` 	= '". $From 	."', ";
		$QryInsertMessage .= "`message_subject` = '".  $Subject ."', ";
		$QryInsertMessage .= "`message_text` 	= '". $Message 	."';";

		doquery( $QryInsertMessage, 'messages');

		$QryUpdateUser  = "UPDATE `{{table}}` SET ";
		$QryUpdateUser .= "`new_message` = `new_message` + 1 ";
		$QryUpdateUser .= "WHERE ";
		$QryUpdateUser .= "`id` = '". $Owner ."';";
		doquery($QryUpdateUser, "users");
	}

?>