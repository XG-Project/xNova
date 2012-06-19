<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

function migrate_to_xml ()
{
	$query = doquery("SELECT * FROM {{table}}",'config');

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

	while ($row = mysql_fetch_assoc($query))
	{
		if ( $row['config_name'] != 'BuildLabWhileRun' )
		{
			update_config ( strtolower ( $row['config_name'] ) , str_replace ( $search , $replacement , $row['config_value'] )  );
		}
	}
}

?>