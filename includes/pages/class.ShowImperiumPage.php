<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

class ShowImperiumPage
{
	function __construct ( $CurrentUser )
	{
		global $lang, $resource, $reslist;

		$planetsrow = doquery("
		SELECT `id`,`name`,`galaxy`,`system`,`planet`,`planet_type`,
		`image`,`field_current`,`field_max`,`metal`,`metal_perhour`,
		`crystal`,`crystal_perhour`,`deuterium`,`deuterium_perhour`,
		`energy_used`,`energy_max`,`metal_mine`,`crystal_mine`,`deuterium_sintetizer`,
		`solar_plant`,`fusion_plant`,`robot_factory`,`nano_factory`,`hangar`,`metal_store`,
		`crystal_store`,`deuterium_store`,`laboratory`,`terraformer`,`ally_deposit`,`silo`,
		`small_ship_cargo`,`big_ship_cargo`,`light_hunter`,`heavy_hunter`,`crusher`,`battle_ship`,
		`colonizer`,`recycler`,`spy_sonde`,`bomber_ship`,`solar_satelit`,`destructor`,`dearth_star`,
		`battleship`,`misil_launcher`,`small_laser`,`big_laser`,`gauss_canyon`,`ionic_canyon`,
		`buster_canyon`,`small_protection_shield`,`big_protection_shield`,`interceptor_misil`,
		`interplanetary_misil`, `mondbasis`, `phalanx`, `sprungtor`
		FROM {{table}}
		WHERE `id_owner` = '" . intval($CurrentUser['id']) . "' AND `destruyed` = 0;", 'planets');

		$parse 	= $lang;
		$planet = array();

		while ( $p = mysql_fetch_array ( $planetsrow ) )
		{
			$planet[] = $p;
		}

		$parse['mount'] = count ( $planet ) + 1;
		$EmpireRowTPL	= gettemplate ( 'empire/empire_row' );
		$r				= array();
		
		foreach ( $planet as $p )
		{
			$metal_prod 	= $p['metal_perhour'] + read_config ( 'metal_basic_income' );
			$crystal_prod 	= $p['crystal_perhour'] + read_config ( 'crystal_basic_income' );
			$deuterium_prod	= $p['deuterium_perhour'] + read_config ( 'deuterium_basic_income' );
			
			$datat  = array ( '<a href="game.php?page=overview&cp=' . $p['id'] . '&amp;re=0"><img src="' . DPATH . 'planeten/small/s_' . $p['image'] . '.jpg" border="0" height="80" width="80"></a>', $p['name'], "[<a href=\"game.php?page=galaxy&mode=3&galaxy={$p['galaxy']}&system={$p['system']}\">{$p['galaxy']}:{$p['system']}:{$p['planet']}</a>]", $p['field_current'] . '/' . $p['field_max'], '<a href="game.php?page=resources&cp=' . $p['id'] . '&amp;re=0&amp;planettype=' . $p['planet_type'] . '">' . Format::pretty_number($p['metal']) . '</a> / ' . Format::pretty_number($metal_prod), '<a href="game.php?page=resources&cp=' . $p['id'] . '&amp;re=0&amp;planettype=' . $p['planet_type'] . '">' . Format::pretty_number($p['crystal']) . '</a> / ' . Format::pretty_number($crystal_prod), '<a href="game.php?page=resources&cp=' . $p['id'] . '&amp;re=0&amp;planettype=' . $p['planet_type'] . '">' . Format::pretty_number($p['deuterium']) . '</a> / ' . Format::pretty_number($deuterium_prod), Format::pretty_number($p['energy_max'] - $p['energy_used']) . ' / ' . Format::pretty_number($p['energy_max']));
			$f 		= array ( 'file_images' , 'file_names' , 'file_coordinates' , 'file_fields' , 'file_metal', 'file_crystal' , 'file_deuterium' , 'file_energy' );

			for ($k = 0; $k < 8; $k++)
			{
				if ( ! isset ( $parse[$f[$k]] ) )
				{
					$parse[$f[$k]] = '';	
				}
					
				$data['text'] 	= $datat[$k];
				$parse[$f[$k]] .= parsetemplate($EmpireRowTPL, $data);
			}

			foreach ($resource as $i => $res)
			{
				if ( ! in_array ( $i , $reslist['officier'] ) ) // EXCLUDE OFFICIERS
				{
					if ( ! isset ( $r[$i] ) )
					{
						$r[$i] = '';	
					}

					if ( ! isset ( $p[$resource[$i]] ) )
					{
						$p[$resource[$i]] = 0;
					}
					
					if ( ! isset ( $CurrentUser[$resource[$i]] ) )
					{
						$CurrentUser[$resource[$i]] = 0;
					}
					
					$data['text'] = ($p[$resource[$i]] == 0 && $CurrentUser[$resource[$i]] == 0) ? '-' : ((in_array($i, $reslist['build'])) ? "<a href=\"game.php?page=buildings&cp={$p['id']}&amp;re=0&amp;planettype={$p['planet_type']}\">{$p[$resource[$i]]}</a>" : ((in_array($i, $reslist['tech'])) ? "<a href=\"game.php?page=buildings&mode=research&cp={$p['id']}&amp;re=0&amp;planettype={$p['planet_type']}\">{$CurrentUser[$resource[$i]]}</a>" : ((in_array($i, $reslist['fleet'])) ? "<a href=\"game.php?page=buildings&mode=fleet&cp={$p['id']}&amp;re=0&amp;planettype={$p['planet_type']}\">{$p[$resource[$i]]}</a>" : ((in_array($i, $reslist['defense'])) ? "<a href=\"game.php?page=buildings&mode=defense&cp={$p['id']}&amp;re=0&amp;planettype={$p['planet_type']}\">{$p[$resource[$i]]}</a>" : '-'))));
					$r[$i] 		 .= parsetemplate($EmpireRowTPL, $data);
				}
			}
		}

		$m = array ( 'build' , 'tech' , 'fleet' , 'defense' );
		$n = array ( 'building_row' , 'technology_row' , 'fleet_row' , 'defense_row' );

		for ( $j = 0; $j < 4; $j++ )
		{
			foreach ( $reslist[$m[$j]] as $a => $i )
			{
				if ( ! isset ( $parse[$n[$j]] ) )
				{
					$parse[$n[$j]] = '';
				}
			
				$data['text'] 	= $lang['tech'][$i];
				$parse[$n[$j]] .= "<tr>" . parsetemplate ( $EmpireRowTPL , $data ) . $r[$i] . "</tr>";
			}
		}

		return display ( parsetemplate ( gettemplate ( 'empire/empire_table' ) , $parse ) , FALSE );
	}
}
?>