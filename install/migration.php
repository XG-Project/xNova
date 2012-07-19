<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

function migrate_to_xml()
{
	$xml			= file_get_contents(XN_ROOT.'install/xml_template.xml');
	$config_file	= fopen(XN_ROOT.'includes/xml/config.xml', "wb");
	fwrite($config_file, $xml);
	fclose($config_file);

	$query		= doquery("SELECT * FROM {{table}}",'config');

	$search		=	array	(
								'¡',
								'¿',
								'º',
								'ª',
								'"',
								'#',
								'$',
								'%',
								'(',
								')',
								'¬',
								'€',
								'|',
								'~'
							);
	$replace	=	array	(
								'&#161;',
								'&#191;',
								'&#176;',
								'&#170;',
								'&#34;',
								'&#35;',
								'&#36;',
								'&#37;',
								'&#40;',
								'&#41;',
								'&#172;',
								'&#8364;',
								'&#124;',
								'&#126;'
							);

	while ($row = $query->fetch_assoc())
	{
		if ($row['config_name'] != 'BuildLabWhileRun')
		{
			update_config(strtolower($row['config_name']), str_replace($search, $replace, $row['config_value']));
		}
	}
}

function upgrade_xml()
{
	$xml			= file_get_contents(XN_ROOT.'install/xml_upgrade.xml');
	$config			= file_get_contents(XN_ROOT.'includes/xml/config.xml');
	$xml			= str_replace('</configurations>', $xml, $config);

	$config_file	= fopen(XN_ROOT.'install/xml_template.xml', "wb");
	fwrite($config_file, $xml);
	fclose($config_file);
}

?>