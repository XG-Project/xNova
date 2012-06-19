<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Get "now" time
 *
 * Returns time() based on the timezone parameter or on the "timezone"
 * setting
 *
 * @param	string	Timezone
 * @return	int		Unix timestamp
 */
function now($timezone = NULL)
{
	if (empty($timezone))
	{
		$timezone = config_item('timezone');
	}

	if ($timezone === 'local' OR $timezone === date_default_timezone_get())
	{
		return time();
	}

	$datetime = new DateTime('now', new DateTimeZone($timezone));
	sscanf($datetime->format('j-n-Y G:i:s'), '%d-%d-%d %d:%d:%d', $day, $month, $year, $hour, $minute, $second);

	return mktime($hour, $minute, $second, $month, $day, $year);
}

/**
 * Show date
 *
 * Shows date based on the format parameter
 *
 * @param	string		Format
 * @return	string		Date
 */
function show_date($format = NULL)
{
	$CI			=& get_instance();

	$format		= $format ? $format : $CI->config->item('date_format');
	$CI->lang->load('time');

	$date	= str_replace("%WEEKDAY%",		lang('time.day_f_'.date("w", now())),	$format);
	$date	= str_replace("%WEEKDAY_S%",	lang('time.day_s_'.date("w", now())),	$date);
	$date	= str_replace("%DAY%",			date("d", now()),						$date);
	$date	= str_replace("%DAY-0%",		date("j", now()),						$date);
	$date	= str_replace("%MONTHNAME%",	lang('time.month_f_'.date("m", now())),	$date);
	$date	= str_replace("%MONTHNAME_S%",	lang('time.month_s_'.date("m", now())),	$date);
	$date	= str_replace("%MONTH%",		date("m", now()),						$date);
	$date	= str_replace("%MONTH-0%",		date("n",now()),						$date);
	$date	= str_replace("%YEAR%",			date("Y", now()),						$date);
	$date	= str_replace("%YEAR_S%",		substr(date("Y", now()), -2),			$date);
	$date	= str_replace("%HOUR%",			date("H", now()),						$date);
	$date	= str_replace("%MINUTE%",		date("i", now()),						$date);
	$date	= str_replace("%SECOND%",		date("s", now()),						$date);
	$date	= str_replace("%OF%",			lang('overal.of'),						$date);

	return $date;
}


/* End of file XN_date_helper.php */
/* Location: ./application/helpers/XN_date_helper.php */