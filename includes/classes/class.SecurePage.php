<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 * @author	Jstar
 */

class SecurePage
{
	private static $instance = NULL;

	public function __construct()
	{
		$_GET		= array_map(array($this,'validate'), $_GET);
		$_POST		= array_map(array($this,'validate'), $_POST);
		$_REQUEST	= array_map(array($this,'validate'), $_REQUEST);
		$_SERVER	= array_map(array($this,'validate'), $_SERVER);
		$_COOKIE	= array_map(array($this,'validate'), $_COOKIE);
	}

	private function validate($value)
	{
		global $db;

		if ( ! is_array($value))
		{
			$value = str_ireplace("script","blocked", $value);
			$value = (get_magic_quotes_gpc()) ? htmlentities(stripslashes($value), ENT_QUOTES, 'UTF-8', FALSE) : htmlentities($value, ENT_QUOTES, 'UTF-8', FALSE);
			$value = $db->real_escape_string($value);
		}
		else
		{
			$c = 0;
			foreach ($value as $val)
			{
				$value[$c] = $this->validate($val);
				$c++;
			}
		}
		return $value;
	}

	public static function run()
	{
		if (is_null(self::$instance))
		{
			$c = __CLASS__;
			self::$instance = new $c();
		}
	}
}


/* End of file class.SecurePage.php */
/* Location: ./includes/classes/class.SecurePage.php */