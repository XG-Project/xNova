<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * XN_Session Class
 *
 * @subpackage	Libraries
 * @author		Razican
 * @category	Libraries
 * @link		http://www.razican.com/
 */
class XN_Session extends CI_Session {

  function __construct()
  {
    parent::__construct();
  }
	/**
	 * Get the "now" time
	 *
	 * @access	private
	 * @return	string
	 */
	function _get_time()
	{
		$timezone = config_item('time_reference');

		if ($timezone === 'local' OR $timezone === date_default_timezone_get())
		{
			return time();
		}

		$datetime = new DateTime('now', new DateTimeZone($timezone));
		sscanf($datetime->format('j-n-Y G:i:s'), '%d-%d-%d %d:%d:%d', $day, $month, $year, $hour, $minute, $second);

		return mktime($hour, $minute, $second, $month, $day, $year);
	}

	// --------------------------------------------------------------------

	/**
	 * Change the session expiration
	 *
	 * @access	public
	 * @return	void
	 */
	public function set_expiration($seconds = 0)
	{
		if ($seconds !== 0)
		{
			$expire						= time() + $seconds;
			$this->sess_expire_on_close	= FALSE;
		}
		else
		{
			$this->sess_expire_on_close	= TRUE;
		}

		// Serialize the userdata for the cookie
		$cookie_data = $this->_serialize($this->userdata);

		if ($this->sess_encrypt_cookie == TRUE)
		{
			$cookie_data = $this->CI->encrypt->encode($cookie_data);
		}
		else
		{
			// if encryption is not used, we provide an md5 hash to prevent userside tampering
			$cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);
		}

		// Set the cookie
		setcookie(
					$this->sess_cookie_name,
					$cookie_data,
					$expire,
					$this->cookie_path,
					$this->cookie_domain,
					$this->cookie_secure
				);
	}
}


/* End of file XN_Session.php */
/* Location: ./application/libraries/XN_Session.php */