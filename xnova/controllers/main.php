<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends XN_Controller {

	public function index()
	{
		if ($this->uri->segment(1))
			redirect('/', 'location', 301);

		if ($this->user->is_logged_in())
		{
			echo 'Overview';
		}
		else
		{
			if ($this->input->server('REQUEST_METHOD  ') === 'POST')
			{
				echo 'Logging in';
			}
			else
			{
				echo 'Login form';
			}
		}
	}
}


/* End of file main.php */
/* Location: ./application/controllers/main.php */