<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Class
 *
 * @subpackage	Libraries
 * @author		Razican
 * @category	Libraries
 * @link		http://www.razican.com/
 */
class User {

	public	$username;
	public	$password;
	public	$reg_email;
	public	$email;
	public	$name;
	public	$last_planet;
	public	$reg_ip;
	public	$last_ip;
	public	$last_user_agent;
	public	$reg_time;
	public	$last_active;
	public	$espionage_probes;
	public	$new_messages;
	public	$settings;
	public	$tecnology;
	public	$officers;
	public	$dark_matter;
	public	$ban;
	public	$hibernating;

	/**
	 * Check whether a user is an administrator
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function is_admin()
	{
		$CI	=& get_instance();

		return ((bool) $CI->session->userdata('is_admin'));
	}

	/**
	 * Check whether a user is logged in
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function is_logged_in()
	{
		$CI	=& get_instance();

		return (bool) $CI->session->userdata('logged_in');
	}

	/**
	 * Check whether a user is hibernating
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function is_hibernating()
	{
		$CI	=& get_instance();

		return ($this->hibernating > now());
	}

	/**
	 * Check whether a user is hibernating
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function is_banned()
	{
		$CI	=& get_instance();

		return ($this->hibernating > now());
	}

	/**
	 * Finish current user's hibernation
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function finish_hibernation()
	{
		$CI	=& get_instance();

		$CI->db->where('id', $this->id);
		$CI->db->set('hibernating', 0);
		return ($CI->db->update('users') && $this->hibernating = 0);
	}

	/**
	 * Loads the user that is logged in
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function load()
	{
		$CI		=& get_instance();

		if ($CI->session->userdata('is_banned'))
		{
			$this->ban	= now()+3600;
			return TRUE;
		}

		$CI->db->where('user', $CI->session->userdata('user_id'));
		$query	= $CI->db->get('users');
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $user)
			{
				$this->id				= $user->id;
				$this->username			= $user->username;
				$this->password			= $user->pasword;
				$this->reg_email		= $user->email;
				$this->email			= $user->email;
				$this->name				= $user->name;
				$this->last_planet		= $user->last_planet;
				$this->reg_ip			= $user->reg_ip;
				$this->last_ip			= $user->last_ip;
				$this->last_user_agent	= $user->last_user_agent;
				$this->reg_time			= $user->reg_time;
				$this->last_active		= $user->last_active;
				$this->espionage_probes	= $user->espionage_probes;
				$this->settings			= unserialize($user->settings);
				$this->new_messages		= $user->new_messages;
				$this->technology		= unserialize($user->technology);
				$this->officers			= unserialize($user->officers);
				$this->dark_matter		= $user->dark_matter;
				$this->ban				= $user->ban;
				$this->hibernating		= $user->hibernating;
			}

			if (($CI->session->userdata('user_agent') != $this->last_user_agent) OR
				($CI->input->ip_address() != $this->last_ip) OR
				($this->last_active < now() - config_item('sess_time_to_update')))
			{
				$CI->db->where('id', $this->id);
				$CI->db->set(array(
									'last_user_agent'	=> $CI->session->userdata('user_agent'),
									'last_ip'			=> $CI->input->ip_address(),
									'last_active'		=> now()
							));
				return ($CI->db->update('users') &&
						$this->last_user_agent	= $CI->session->userdata('user_agent') &&
						$this->last_ip			= $CI->input->ip_address() &&
						$this->last_active		= now());
			}

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Logs in a user
	 *
	 * @access	public
	 * @param	string	Username
	 * @param	string	Password
	 * @param	boolean	Remember the user
	 * @return	boolean
	 */
	public function login($username, $password, $remember)
	{
		$CI			=& get_instance();
		$username	= strtolower($username);

		$CI->db->where('username', $username);
		$CI->db->where('password', sha1($password));
		$CI->db->limit(1);
		$query		= $CI->db->get('users');

		if($query->num_rows() === 1)
		{
			foreach($query->result() as $user);

			if($remember)
				$CI->session->set_expiration(config_item('sess_expiration'));

			$userdata	= array(
				'id'			=> $user->id,
				'logged_in'		=> TRUE
				);

			$CI->session->set_userdata($userdata);

			return TRUE;
		}

		return FALSE;
	}
}


/* End of file User.php */
/* Location: ./application/libraries/User.php */