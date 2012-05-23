<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

	function formatCR (&$result_array,&$steal_array,&$moon_int,&$moon_string,&$time_float)
	{
		global $lang;

		$html 		= "";
		$bbc 		= "";
		$html 		.= $lang['sys_attack_title']." ".date("D M j H:i:s", time()).".<br /><br />";
		$round_no 	= 1;
		$destroyed	= 0;

		foreach( $result_array['rw'] as $round => $data1)
		{
			if($round_no <= 6)
			{
				$html 		.= $lang['sys_attack_round']." ".$round_no." :<br /><br />";
				$attackers1 = $data1['attackers'];
				$attackers2 = $data1['infoA'];
				$attackers3 = $data1['attackA'];
				$defenders1 = $data1['defenders'];
				$defenders2 = $data1['infoD'];
				$defenders3 = $data1['defenseA'];
				$coord4 	= 0;
				$coord5 	= 0;
				$coord6 	= 0;

				foreach( $attackers1 as $fleet_id1 => $data2)
				{
					$name 	= $data2['user']['username'];
					$coord1 = $data2['fleet']['fleet_start_galaxy'];
					$coord2 = $data2['fleet']['fleet_start_system'];
					$coord3 = $data2['fleet']['fleet_start_planet'];
					$weap 	= ($data2['user']['military_tech'] * 10);
					$shie 	= ($data2['user']['defence_tech'] * 10);
					$armr 	= ($data2['user']['shield_tech'] * 10);

					if($coord4 == 0){$coord4 += $data2['fleet']['fleet_end_galaxy'];}
					if($coord5 == 0){$coord5 += $data2['fleet']['fleet_end_system'];}
					if($coord6 == 0){$coord6 += $data2['fleet']['fleet_end_planet'];}

					$fl_info1  	= "<table><tr><th>";
					$fl_info1 	.= $lang['sys_attack_attacker_pos']." ".$name." ([".$coord1.":".$coord2.":".$coord3."])<br />";
					$fl_info1 	.= $lang['sys_ship_weapon']." ".$weap."% - ".$lang['sys_ship_shield']." ".$shie."% - ".$lang['sys_ship_armour']." ".$armr."%";
					$table1  	= "<table border=1 align=\"center\">";

					if (number_format($data1['attack']['total']) >= 0 && $round_no == 1)
					{
						if(number_format($data1['attack']['total']) == 0)
						{
							$ships1 = "<tr><br /><br />". $lang['sys_destroyed']."<br /></tr>";
							$count1 = "";
							$destroyed = 1;
						}
						else
						{
							$destroyed = 0;
						}

						$ships1  = "<tr><th>".$lang['sys_ship_type']."</th>";
						$count1  = "<tr><th>".$lang['sys_ship_count']."</th>";

						foreach( $data2['detail'] as $ship_id1 => $ship_count1)
						{
						   if ($ship_count1 > 0)
						   {
						       $ships1 .= "<th>[ship[".$ship_id1."]]</th>";
						       $count1 .= "<th>".number_format($ship_count1)."</th>";
						   }
						}

						$ships1 .= "</tr>";
						$count1 .= "</tr>";
					}
					elseif(number_format($data1['attack']['total']) > 0)
					{
						$ships1  = "<tr><th>".$lang['sys_ship_type']."</th>";
						$count1  = "<tr><th>".$lang['sys_ship_count']."</th>";

						foreach( $data2['detail'] as $ship_id1 => $ship_count1)
						{
							if ($ship_count1 > 0)
							{
								$ships1 .= "<th>[ship[".$ship_id1."]]</th>";
								$count1 .= "<th>".number_format($ship_count1)."</th>";
							}
						}

						$ships1 .= "</tr>";
						$count1 .= "</tr>";
					}
					else
					{
						$ships1 = "<tr><br /><br />". $lang['sys_destroyed']."<br /></tr>";
						$count1 = "";
					}

					$info_part1[$fleet_id1] = $fl_info1.$table1.$ships1.$count1;
				}

				foreach( $attackers2 as $fleet_id2 => $data3)
				{
					$weap1  = "<tr><th>".$lang['sys_ship_weapon']."</th>";
					$shields1  = "<tr><th>".$lang['sys_ship_shield']."</th>";
					$armour1  = "<tr><th>".$lang['sys_ship_armour']."</th>";

					foreach( $data3 as $ship_id2 => $ship_points1)
					{
						if ($ship_points1['shield'] > 0)
						{
						   $weap1 		.= "<th>".number_format($ship_points1['att'])."</th>";
						   $shields1 	.= "<th>".number_format($ship_points1['def'])."</th>";
						   $armour1 	.= "<th>".number_format($ship_points1['shield'])."</th>";
						}
					}

					$weap1 		.= "</tr>";
					$shields1 	.= "</tr>";
					$armour1 	.= "</tr>";
					$endtable1 	.= "</table></th></tr></table>";

					$info_part2[$fleet_id2] = $weap1.$shields1.$armour1.$endtable1;

					if (number_format($data1['attackA']['total']) > 0)
					{
						$html .= $info_part1[$fleet_id2].$info_part2[$fleet_id2];
						$html .= "<br /><br />";
					}
					else
					{
						$html .= $info_part1[$fleet_id2];
						$html .= "</table></th></tr></table><br /><br />";
					}
				}

				foreach( $defenders1 as $fleet_id1 => $data2)
				{
					$name = $data2['user']['username'];
					$weap = ($data2['user']['military_tech'] * 10);
					$shie = ($data2['user']['defence_tech'] * 10);
					$armr = ($data2['user']['shield_tech'] * 10);

					$fl_info1  = "<table><tr><th>";
					$fl_info1 .= $lang['sys_attack_defender_pos']." ".$name." ([".$coord4.":".$coord5.":".$coord6."])<br />";
					$fl_info1 .= $lang['sys_ship_weapon']." ".$weap."% - ".$lang['sys_ship_shield']." ".$shie."% - ".$lang['sys_ship_armour']." ".$armr."%";

					$table1  = "<table border=1 align=\"center\">";

					if (number_format($data1['defenseA']['total']) > 0)
					{
						$ships1  = "<tr><th>".$lang['sys_ship_type']."</th>";
						$count1  = "<tr><th>".$lang['sys_ship_count']."</th>";

						foreach( $data2['def'] as $ship_id1 => $ship_count1)
						{
							if ($ship_count1 > 0)
							{
								$ships1 .= "<th>[ship[".$ship_id1."]]</th>";
								$count1 .= "<th>".number_format($ship_count1)."</th>";
							}
						}

						$ships1 .= "</tr>";
						$count1 .= "</tr>";
					}
					else
					{
						$ships1 = "<tr><br /><br />". $lang['sys_destroyed']."<br /></tr>";
						$count1 = "";
					}

					$info_part1[$fleet_id1] = $fl_info1.$table1.$ships1.$count1;
				}

				foreach( $defenders2 as $fleet_id2 => $data3)
				{
					$weap1  	= "<tr><th>".$lang['sys_ship_weapon']."</th>";
					$shields1  	= "<tr><th>".$lang['sys_ship_shield']."</th>";
					$armour1  	= "<tr><th>".$lang['sys_ship_armour']."</th>";

					foreach( $data3 as $ship_id2 => $ship_points1)
					{
						if ($ship_points1['shield'] > 0)
						{
							$weap1 .= "<th>".number_format($ship_points1['att'])."</th>";
							$shields1 .= "<th>".number_format($ship_points1['def'])."</th>";
							$armour1 .= "<th>".number_format($ship_points1['shield'])."</th>";
						}
					}

					$weap1 		.= "</tr>";
					$shields1 	.= "</tr>";
					$armour1 	.= "</tr>";
					$endtable1 	.= "</table></th></tr></table>";

					$info_part2[$fleet_id2] = $weap1.$shields1.$armour1.$endtable1;

					if (number_format($data1['defenseA']['total']) > 0)
					{
						$html .= $info_part1[$fleet_id2].$info_part2[$fleet_id2];
						$html .= "<br /><br />";
					}
					else
					{
						$html .= $info_part1[$fleet_id2];
						$html .= "</table></th></tr></table><br /><br />";
					}
				}
				$html .=  $lang['fleet_attack_1']." ".number_format($data1['attack']['total'])." ".$lang['fleet_attack_2']." ".number_format($data1['defShield'], 0, ' ', ' ')." ".$lang['damage']."<br />";
				$html .= $lang['fleet_defs_1']." ".number_format($data1['defense']['total'])." ".$lang['fleet_defs_2']." ".number_format($data1['attackShield'], 0, ' ', ' ')." ".$lang['damage']."<br /><br />";
				$round_no++;
			}
		}

		if ($result_array['won'] == "r")
		{
			$result1  = $lang['sys_defender_won']."<br />";
		}
		elseif ($result_array['won'] == "a")
		{
			$result1  = $lang['sys_attacker_won']."<br />";
			$result1 .= $lang['sys_stealed_ressources']." ".$steal_array['metal']." ".$lang['Metal'].", ".$steal_array['crystal']." ".$lang['Crystal']." ".$lang['and']." ".$steal_array['deuterium']." ".$lang['Deuterium']."<br />";
		}
		else
		{
			$result1  = $lang['sys_both_won'].".<br />";
		}

		$html .= "<br /><br />";
		$html .= $result1;
		$html .= "<br />";

		$debirs_meta = ($result_array['debree']['att'][0] + $result_array['debree']['def'][0]);
		$debirs_crys = ($result_array['debree']['att'][1] + $result_array['debree']['def'][1]);

		$html .= $lang['sys_attacker_lostunits']." ".$result_array['lost']['att']." ".$lang['sys_units']."<br />";
		$html .= $lang['sys_defender_lostunits']." ".$result_array['lost']['def']." ".$lang['sys_units']."<br />";
		$html .= $lang['debree_field_1']." ".$debirs_meta." ".$lang['Metal']." ".$lang['sys_and']." ".$debirs_crys." ".$lang['Crystal']." ".$lang['debree_field_2']."<br /><br />";
		$html .= $lang['sys_moonproba']." ".floor($moon_int)." %<br />";
		$html .= $moon_string."<br /><br />";

		return array('html' => $html, 'bbc' => $bbc, 'destroyed' => $destroyed);
	}
?>