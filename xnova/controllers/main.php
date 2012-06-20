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
				if ($this->user->login($this->input->post('username'),
					$this->input->post('password'),
					(bool) $this->input->post('rememberme')))
				{
					redirect('/');
				}
				else
				{
					message(lang('login.error'));
				}
			}
			else
			{
				$this->lang->load('login');
				$this->load->view('login');
			}
		}
	}
}


/* End of file main.php */
/* Location: ./application/controllers/main.php */