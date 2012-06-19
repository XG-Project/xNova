<?php defined('BASEPATH') OR exit('No direct script access allowed');

class XN_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		if ( ! $this->input->is_cli_request())
		{
			$this->output->enable_profiler($this->config->item('debug'));

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