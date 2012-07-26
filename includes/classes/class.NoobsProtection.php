<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if(!defined('INSIDE')) die(header("location:../../"));

class NoobsProtection
{
	private static $instance = null;
	private $_protection;
	private $_protectiontime;
	private $_protectionmulti;

	// READ SOME CONFIG BY DEFAULT
	private function __construct()
	{
		$this->_protection      	= (bool) read_config('noobprotection');
		$this->_protectiontime  	= read_config('noobprotectiontime');
		$this->_protectionmulti 	= read_config('noobprotectionmulti');
	}

	// DETERMINES IF THE PLAYER IS WEAK OR NOT
	public function is_weak($current_points, $other_points)
	{
		if (($current_points > ($other_points*$this->_protectionmulti)) OR ($other_points < $this->_protectiontime)
			&& ($this->_protection))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// DETERMINES IF THE PLAYER IS STRONG OR NOT
	public function is_strong($current_points, $other_points)
	{
		if ((($current_points * $this->_protectionmulti) < $other_points) OR ($current_points < $this->_protectiontime)
		 && ($this->_protection))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

    public static function getInstance()
    {
        if (self::$instance == null)
        {
            //make new istance of this class and save it to field for next usage
            $c = __class__;
            self::$instance = new $c();
        }

        return self::$instance;
    }
}
?>