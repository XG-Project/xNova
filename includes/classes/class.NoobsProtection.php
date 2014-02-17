<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright © 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class NoobsProtection
{
	private static $instance = null;
	private $_protection; // 1 OR 0
	private $_protectiontime;
	private $_protectionmulti;
	
	// READ SOME CONFIG BY DEFAULT
	private function __construct()
	{	
		$this->_protection      	= read_config ( 'noobprotection' );
		$this->_protectiontime  	= read_config ( 'noobprotectiontime' );
		$this->_protectionmulti 	= read_config ( 'noobprotectionmulti' );		
	}
	
	// DETERMINES IF THE PLAYER IS WEAK OR NOT
	public function is_weak ( $current_points , $other_points )
    {                        
        return  ( $current_points > $other_points * $this->_protectionmulti  or  $other_points < $this->_protectiontime )  &&  $this->_protection;
    }
    
    // DETERMINES IF THE PLAYER IS STRONG OR NOT
    public function is_strong ( $current_points , $other_points )
    {
    	return (  $current_points * $this->_protectionmulti  < $other_points  ||  $current_points < $this->_protectiontime  )  &&  $this->_protection;               
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