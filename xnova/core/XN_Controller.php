<?php defined('BASEPATH') OR exit('No direct script access allowed');

class XN_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		if ( ! $this->input->is_cli_request())
		{
			if ((strtolower($this->uri->segment(1)) === 'admin') && ( ! $this->user->is_admin()))
			{
				log_message('error', 'User with IP '.$this->input->ip_address().' has tried to enter the admin section.');
				redirect('/');
			}

			$this->output->enable_profiler($this->config->item('debug'));

			if ($this->user->is_logged_in())
			{
				if ( ! $this->user->load())
				{
					//Error, el usuario no existe, ha sido eliminado
				}
				elseif ($this->user->is_banned())
				{
					//Error, el usuario está baneado!!!
				}
				elseif ($this->user->is_hibernating())
				{
					$this->user->finish_hibernation();
					//Puede haber problemas para comprobar otros usuarios en el panel de administración.
				}
			}

			//Loading config from database:
			$query = $this->db->get('config');
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $config_item)
				{
					$this->config->set_item($config_item->key, $config_item->value);
				}
			}
		}
	}
}


/* End of file XN_Controller.php */
/* Location: ./application/core/XN_Controller.php */