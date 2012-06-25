<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Show message
 *
 * @param	string
 * @param	string
 * @return	void
 */
function message($message, $dest = '/')
{
	$CI					=& get_instance();

	$data['topbar']		= '';
	$data['menu']		= '';
	$data['license']	= $CI->load->view('license', '', TRUE);
	if (defined('INGAME'))
	{
		$CI->lang->load('menu');
		$CI->lang->load('topbar');
		$data['topbar']		= $CI->load->view('ingame/topbar', '', TRUE).'<div class="clear"></div>';
		$data['menu']		= $CI->load->view('ingame/menu', '', TRUE);
	}

	$data['message']	= $message;
	$data['dest']		= anchor($dest, lang('overal.go_back'), 'title="'.lang('overal.go_back').'"');

	$CI->load->view('message', $data);
}

/**
 * Return current skin
 *
 * @return	string
 */
function skin()
{
	$CI		=& get_instance();

	$skin	=  $CI->session->userdata('logged_in') && isset($CI->user->settings['skin']) ? $CI->user->settings['skin'] : config_item('skin');
	$skin	= ( ! empty($skin)) ? $skin : 'default';

	return $skin;
}

/**
 * Return the current languaje key
 *
 * @return	string	Language key
 */
function current_lang()
{
	$CI =& get_instance();
	require_once(APPPATH.'language/'.config_item('language').'/config.php');
	if( ! defined('LANG_KEY')) show_error('ERROR! language not configured correctly!');

	return LANG_KEY;
}


/* End of file overal_helper.php */
/* Location: ./application/helpers/overal_helper.php */