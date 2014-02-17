<?php

/**
* @project XG Proyect
* @version 2.10.x build 0000
* @copyright Copyright (C) 2008 - 2012
*/

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);
define('XGP_ROOT', './../');

include(XGP_ROOT . 'global.php');

if ( $Observation != 1 )
{
	die(message ($lang['404_page']));
}

$parse	= $lang;


if ( $user['authlevel']	!= 3 )
{
	$NOSUPERMI	= "WHERE `authlevel` < '" . $user['authlevel'] . "'";
}

$UserWhileLogin		= doquery ( "SELECT `id`, `username`, `authlevel` FROM {{table}} " . $NOSUPERMI . " ORDER BY `username` ASC" , "users" );

while ( $UserList 	= mysql_fetch_array ( $UserWhileLogin ) )
{
	$parse['lista']	.=	"<option value=\"".$UserList['id']."\">" . $UserList['username'] . "&nbsp;&nbsp; (" . $lang['rank'][$UserList['authlevel']] . ")</option>";
}

if ( $_GET['id_u'] != NULL )
{
	$id_u	= $_GET['id_u'];
}
else
{
	$id_u	= $_GET['id_u2'];
}

$OnlyQueryLogin 	= doquery ( "SELECT `id`, `authlevel` FROM {{table}} WHERE `id` = '" . $id_u . "'" , "users" , TRUE );

if ($_GET)
{
	if ( $id_u == NULL )
	{
		$parse['error']	=	"<tr><th height=25 style=\"border: 2px red solid;\"><font color=red>" . $lang['ac_user_id_required'] . "</font></th></tr>";
	}
	elseif ( $_GET['id_u'] != NULL && $_GET['id_u2'] != NULL )
	{
		$parse['error']	=	"<tr><th height=25 style=\"border: 2px red solid;\"><font color=red>" . $lang['ac_select_one_id'] . "</font></th></tr>";
	}
	elseif ( !is_numeric ( $id_u ) )
	{
		$parse['error']	=	"<tr><th height=25 style=\"border: 2px red solid;\"><font color=red>" . $lang['ac_no_character'] . "</font></th></tr>";
	}
	elseif ( $OnlyQueryLogin == NULL or $OnlyQueryLogin == 0 )
	{
		$parse['error']	=	"<tr><th height=25 style=\"border: 2px red solid;\"><font color=red>" . $lang['ac_username_doesnt'] . "</font></th></tr>";
	}
	elseif ( $user['authlevel'] != 3 && $OnlyQueryLogin['authlevel'] > $user['authlevel'] )
	{
		$parse['error']	=	"<tr><th height=25 style=\"border: 2px red solid;\"><font color=red>" . $lang['ac_no_rank_level'] . "</font></th></tr>";
	}
	else
	{
		// COMIENZA SAQUEO DE DATOS DE LA TABLA DE USUARIOS
		$SpecifyItemsU	=
		"id,username,email,email_2,authlevel,id_planet,galaxy,system,planet,user_lastip,ip_at_reg,user_agent,register_time,onlinetime,noipcheck,urlaubs_modus,
		urlaubs_until,spy_tech,computer_tech,military_tech,defence_tech,shield_tech,energy_tech,hyperspace_tech,combustion_tech,impulse_motor_tech,
		hyperspace_motor_tech,laser_tech,ionic_tech,buster_tech,intergalactic_tech,expedition_tech,graviton_tech,ally_id,ally_name,ally_request,
		ally_request_text,ally_register_time,ally_rank_id,rpg_geologue,rpg_amiral,rpg_ingenieur,rpg_technocrate,darkmatter,bana,banaday";

		$UserQuery 	= 	doquery ( "SELECT " . $SpecifyItemsU . " FROM {{table}} WHERE `id` = '" . $id_u . "'" , "users" , TRUE );


		$parse['reg_time']		=	date("d-m-Y H:i:s", $UserQuery['register_time']);
		$parse['onlinetime']	=	date("d-m-Y H:i:s", $UserQuery['onlinetime']);
		$parse['id']			=	$UserQuery['id'];
		$parse['nombre']		=	$UserQuery['username'];
		$parse['email_1']		=	$UserQuery['email'];
		$parse['email_2']		=	$UserQuery['email_2'];
		$parse['ip']			=	$UserQuery['ip_at_reg'];
		$parse['ip2']			=	$UserQuery['user_lastip'];
		$parse['id_p']			=	$UserQuery['id_planet'];
		$parse['g']				=	$UserQuery['galaxy'];
		$parse['s']				=	$UserQuery['system'];
		$parse['p']				=	$UserQuery['planet'];
		$parse['info']			=	$UserQuery['user_agent'];
		$alianza				=	$UserQuery['ally_name'];
		$parse['nivel']			=	$lang['rank'][$UserQuery['authlevel']];
		$parse['ipcheck']		=	$lang['ac_checkip'][$UserQuery['noipcheck']];

		if ( $UserQuery['urlaubs_modus'] == 1 )
		{
			$parse['vacas'] = $lang['one_is_yes'][1];
		}
		else
		{
			$parse['vacas'] = $lang['one_is_yes'][0];
		}

		if ( $UserQuery['bana'] == 1 )
		{
			$parse['suspen'] = $lang['one_is_yes'][1];
		}
		else
		{
			$parse['suspen'] = $lang['one_is_yes'][0];
		}

		$parse['mo'] = "<a title=\"" . Format::pretty_number ( $UserQuery['darkmatter'] ) . "\">" . Format::shortly_number ( $UserQuery['darkmatter'] ) . "</a>";

		$Log	.=	"\n" . $lang['log_info_detail_title'] . "\n";
		$Log	.=	$lang['log_the_user'] . $user['username'] . $lang['log_searchto_1'] . $UserQuery['username'] . "\n";

		LogFunction ( $Log , "GeneralLog" , $LogCanWork );

		// TECNOLOGIAS
		$parse['tec_espia']				=	$UserQuery['spy_tech'];
		$parse['tec_compu']				=	$UserQuery['computer_tech'];
		$parse['tec_militar']			=	$UserQuery['military_tech'];
		$parse['tec_defensa']			=	$UserQuery['defence_tech'];
		$parse['tec_blindaje']			=	$UserQuery['shield_tech'];
		$parse['tec_energia']			=	$UserQuery['energy_tech'];
		$parse['tec_hiperespacio']		=	$UserQuery['hyperspace_tech'];
		$parse['tec_combustion']		=	$UserQuery['combustion_tech'];
		$parse['tec_impulso']			=	$UserQuery['impulse_motor_tech'];
		$parse['tec_hiperespacio_p']	=	$UserQuery['hyperspace_motor_tech'];
		$parse['tec_laser']				=	$UserQuery['laser_tech'];
		$parse['tec_ionico']			=	$UserQuery['ionic_tech'];
		$parse['tec_plasma']			=	$UserQuery['buster_tech'];
		$parse['tec_intergalactico']	=	$UserQuery['intergalactic_tech'];
		$parse['tec_expedicion']		=	$UserQuery['expedition_tech'];
		$parse['tec_graviton']			=	$UserQuery['graviton_tech'];

		//OFICIALES
		$parse['ofi_geologo']			=	$UserQuery['rpg_geologue'];
		$parse['ofi_almirante']			=	$UserQuery['rpg_amiral'];
		$parse['ofi_ingeniero']			=	$UserQuery['rpg_ingenieur'];
		$parse['ofi_tecnocrata']		=	$UserQuery['rpg_technocrate'];

		if ($UserQuery['bana'] != 0)
		{
			$parse['mas']			= "<a href=\"javascript:animatedcollapse.toggle('banned')\">" . $lang['ac_more'] . "</a>";

			$BannedQuery			= doquery ( "SELECT theme,time,longer,author FROM {{table}} WHERE `who` = '" . $UserQuery['username'] . "'" , "banned" , TRUE );

			$parse['sus_longer']	=	date ( "d-m-Y H-i-s" , $BannedQuery['longer'] );
			$parse['sus_time']		=	date ( "d-m-Y H-i-s" , $BannedQuery['time'] );
			$parse['sus_reason']	=	$BannedQuery['theme'];
			$parse['sus_author']	=	$BannedQuery['author'];
		}

		// COMIENZA EL SAQUEO DE DATOS DE LA TABLA DE PUNTAJE
		$SpecifyItemsS	= "tech_count,defs_count,fleet_count,build_count,build_points,tech_points,defs_points,fleet_points,tech_rank,build_rank,defs_rank,fleet_rank,total_points,stat_type";

		$StatQuery	=	doquery ( "SELECT " . $SpecifyItemsS . " FROM {{table}} WHERE `id_owner` = '" .$id_u . "' AND `stat_type` = '1'" , "statpoints" , TRUE );

		$parse['count_tecno']		=	Format::pretty_number ( $StatQuery['tech_count'] );
		$parse['count_def']			=	Format::pretty_number ( $StatQuery['defs_count'] );
		$parse['count_fleet']		=	Format::pretty_number ( $StatQuery['fleet_count'] );
		$parse['count_builds']		=	Format::pretty_number ( $StatQuery['build_count'] );

		$parse['point_builds']		=	Format::pretty_number ( $StatQuery['build_points'] );
		$parse['point_tecno']		=	Format::pretty_number ( $StatQuery['tech_points'] );
		$parse['point_def']			=	Format::pretty_number ( $StatQuery['defs_points'] );
		$parse['point_fleet']		=	Format::pretty_number ( $StatQuery['fleet_points'] );

		$parse['ranking_tecno']		=	$StatQuery['tech_rank'];
		$parse['ranking_builds']	=	$StatQuery['build_rank'];
		$parse['ranking_def']		=	$StatQuery['defs_rank'];
		$parse['ranking_fleet']		=	$StatQuery['fleet_rank'];

		$parse['total_points']		=	Format::pretty_number ( $StatQuery['total_points'] );

		// COMIENZA EL SAQUEO DE DATOS DE LA ALIANZA
		$AliID	= $UserQuery['ally_id'];


		if ( $alianza == 0 && $AliID == 0 )
		{
			$parse['alianza']		=	$lang['ac_no_ally'];
			$parse['AllianceHave']	=	"<span class=\"no_moon\"><img src=\"../styles/images/Adm/arrowright.png\" width=\"16\" height=\"10\"/>" . $lang['ac_alliance'] . "&nbsp;" . $lang['ac_no_alliance'] . "</span>";
		}
		elseif ( $alianza != NULL && $AliID != 0 )
		{
			include_once("AdminFunctions/BBCode-Panel-Adm.php");
			$bbcode = new bbcode;

			$parse['AllianceHave'] 			= "<a href=\"javascript:animatedcollapse.toggle('alianza')\" class=\"link\"><img src=\"../styles/images/Adm/arrowright.png\" width=\"16\" height=\"10\"/> " . $lang['ac_alliance'] . "</a>";

			$SpecifyItemsA					= "ally_owner,id,ally_tag,ally_name,ally_web,ally_description,ally_text,ally_request,ally_image,ally_members,ally_register_time";

			$AllianceQuery					=	doquery ( "SELECT " . $SpecifyItemsA . " FROM {{table}} WHERE `ally_name` = '" . $alianza . "'" , "alliance" , TRUE );

			$parse['alianza']				=	$alianza;
			$parse['id_ali']				=	" (".$lang['ac_ali_idid']."&nbsp;".$AliID.")";
			$parse['id_aliz']				=	$AllianceQuery['id'];
			$parse['tag']					=	$AllianceQuery['ally_tag'];
			$parse['ali_nom']				=	$AllianceQuery['ally_name'];
			$parse['ali_cant']				=	$AllianceQuery['ally_members'];
			$parse['ally_register_time']	=	date("d-m-Y H:i:s", $AllianceQuery['ally_register_time']);
			$ali_lider						=	$AllianceQuery['ally_owner'];

			if ( $AllianceQuery['ally_web'] != NULL )
			{
				$parse['ali_web'] = "<a href=" . $AllianceQuery['ally_web'] . " target=_blank>" . $AllianceQuery['ally_web'] . "</a>";
			}
			else
			{
				$parse['ali_web'] = $lang['ac_no_web'];
			}

			if ( $AllianceQuery['ally_description'] != NULL )
			{
				$parse['ali_ext2'] = $bbcode->reemplazo ( $AllianceQuery['ally_description'] );
				$parse['ali_ext']  = "<a href=\"#\" rel=\"toggle[externo]\">" . $lang['ac_view_text_ext'] . "</a>";
			}
			else
			{
				$parse['ali_ext'] = $lang['ac_no_text_ext'];
			}



			if ( $AllianceQuery['ally_text'] != NULL )
			{
				$parse['ali_int2'] = $bbcode->reemplazo($AllianceQuery['ally_text']);
				$parse['ali_int']  = "<a href=\"#\" rel=\"toggle[interno]\">".$lang['ac_view_text_int']."</a>";
			}
			else
			{
				$parse['ali_int'] = $lang['ac_no_text_int'];
			}


			if ( $AllianceQuery['ally_request'] != NULL )
			{
				$parse['ali_sol2'] = $bbcode->reemplazo($AllianceQuery['ally_request']);
				$parse['ali_sol']  = "<a href=\"#\" rel=\"toggle[solicitud]\">".$lang['ac_view_text_sol']."</a>";
			}
			else
			{
				$parse['ali_sol'] = $lang['ac_no_text_sol'];
			}


			if ( $AllianceQuery['ally_image'] != NULL )
			{
				$parse['ali_logo2'] = $AllianceQuery['ally_image'];
				$parse['ali_logo'] = "<a href=\"#\" rel=\"toggle[imagen]\">".$lang['ac_view_image2']."</a>";
			}
			else
			{
				$parse['ali_logo'] = $lang['ac_no_img'];
			}

			$SearchLeader			=	doquery("SELECT `username` FROM {{table}} WHERE `id` = '".$ali_lider."'", "users", TRUE);
			$parse['ali_lider']		=	$SearchLeader['username'];

			$StatQueryAlly			=	doquery("SELECT " . $SpecifyItemsS . " FROM {{table}} WHERE `id_owner` = '".$ali_lider."' AND `stat_type` = '2'" , "statpoints" , TRUE );

			$parse['count_tecno_ali']		=	Format::pretty_number ( $StatQueryAlly['tech_count'] );
			$parse['count_def_ali']			=	Format::pretty_number ( $StatQueryAlly['defs_count'] );
			$parse['count_fleet_ali']		=	Format::pretty_number ( $StatQueryAlly['fleet_count'] );
			$parse['count_builds_ali']		=	Format::pretty_number ( $StatQueryAlly['build_count'] );

			$parse['point_builds_ali']		=	Format::pretty_number ( $StatQueryAlly['build_points'] );
			$parse['point_tecno_ali']		=	Format::pretty_number ( $StatQueryAlly['tech_points'] );
			$parse['point_def_ali']			=	Format::pretty_number ( $StatQueryAlly['defs_points'] );
			$parse['point_fleet_ali']		=	Format::pretty_number ( $StatQueryAlly['fleet_points'] );

			$parse['ranking_tecno_ali']		=	Format::pretty_number ( $StatQueryAlly['tech_rank'] );
			$parse['ranking_builds_ali']	=	Format::pretty_number ( $StatQueryAlly['build_rank'] );
			$parse['ranking_def_ali']		=	Format::pretty_number ( $StatQueryAlly['defs_rank'] );
			$parse['ranking_fleet_ali']		=	Format::pretty_number ( $StatQueryAlly['fleet_rank'] );

			$parse['total_points_ali']		=	Format::pretty_number ( $StatQueryAlly['total_points'] );
		}

		// COMIENZA EL SAQUEO DE DATOS DE LOS PLANETAS
		$SpecifyItemsP	=
		"planet_type,id,name,galaxy,system,planet,destruyed,diameter,field_current,field_max,temp_min,temp_max,metal,crystal,deuterium,energy_max,
		metal_mine,crystal_mine,deuterium_sintetizer,solar_plant,fusion_plant,robot_factory,nano_factory,hangar,metal_store,crystal_store,deuterium_store,
		laboratory,terraformer,ally_deposit,silo,small_ship_cargo,big_ship_cargo,light_hunter,heavy_hunter,crusher,battle_ship,colonizer,recycler,
		spy_sonde,bomber_ship,solar_satelit,destructor,dearth_star,battleship,misil_launcher,small_laser,big_laser,gauss_canyon,ionic_canyon,
		buster_canyon,small_protection_shield,big_protection_shield,interceptor_misil,interplanetary_misil,mondbasis,phalanx,sprungtor,
		energy_used";

		$PlanetsQuery = doquery ( "SELECT " . $SpecifyItemsP . " FROM {{table}} WHERE `id_owner` = '" . $id_u . "'" , "planets" );

		while ( $PlanetsWhile = mysql_fetch_array ( $PlanetsQuery ) )
		{
			if ( $PlanetsWhile['planet_type'] == 3 )
			{
				$Planettt = $PlanetsWhile['name'] . "&nbsp;(" . $lang['ac_moon'] . ")<br><font color=aqua>[" . $PlanetsWhile['galaxy'] . ":" . $PlanetsWhile['system'] . ":" . $PlanetsWhile['planet'] . "]</font>";

				$MoonZ		=	0;
				$Moons 		= $PlanetsWhile['name'] . "&nbsp;(" . $lang['ac_moon'] . ")<br><font color=aqua>[" . $PlanetsWhile['galaxy'] . ":" . $PlanetsWhile['system'] . ":" . $PlanetsWhile['planet'] . "]</font>";
				$MoonZ++;
			}
			else
			{
				$Planettt = $PlanetsWhile['name'] . "<br><font color=aqua>[" . $PlanetsWhile['galaxy'] . ":" . $PlanetsWhile['system'] . ":" . $PlanetsWhile['planet'] . "]</font>";
			}

			if ($PlanetsWhile["destruyed"] == 0)
			{
				$parse['planets_moons']	.=	"
				<tr>
				<th>" .$Planettt . "</th>
				<th>" . $PlanetsWhile['id'] . "</th>
				<th>" . Format::pretty_number ( $PlanetsWhile['diameter'] ) . "</th>
				<th>" . Format::pretty_number ( $PlanetsWhile['field_current'] ) . "/" . Format::pretty_number ( $PlanetsWhile['field_max'] ) ."</th>
				<th>" . Format::pretty_number ( $PlanetsWhile['temp_min'] ) . "/" . Format::pretty_number ( $PlanetsWhile['temp_max'] ) ."</th>
				</tr>";

				$SumOfEnergy = ( $PlanetsWhile['energy_max'] + $PlanetsWhile['energy_used'] );

				if ( $SumOfEnergy < 0 )
				{
					$Color	=	"<font color=#FF6600>" . Format::shortly_number ( $SumOfEnergy ) . "</font>";
				}

				elseif ($SumOfEnergy > 0)
				{
					$Color	=	"<font color=lime>" . Format::shortly_number ( $SumOfEnergy ) . "</font>";
				}

				else
				{
					$Color	=	Format::shortly_number ( $SumOfEnergy );
				}

				$parse['resources']	.=	"
				<tr>
				<th>".$Planettt."</th>
				<th><a title=\"".Format::pretty_number($PlanetsWhile['metal'])."\">".Format::shortly_number($PlanetsWhile['metal'])."</a></th>

				<th><a title=\"".Format::pretty_number($PlanetsWhile['crystal'])."\">".Format::shortly_number($PlanetsWhile['crystal'])."</a></th>
				<th><a title=\"".Format::pretty_number($PlanetsWhile['deuterium'])."\">".Format::shortly_number($PlanetsWhile['deuterium'])."</a></th>
				<th><a title=\"".Format::pretty_number($SumOfEnergy)."\">".$Color."</a>/<a title=\"".Format::pretty_number($PlanetsWhile['energy_max'])."\">".Format::shortly_number($PlanetsWhile['energy_max'])."</a></th>
				</tr>";

				$parse['ships']	.=	"
				<tr>
				<th width=\"10%\">".$Planettt."</th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['small_ship_cargo'])."\">".Format::shortly_number($PlanetsWhile['small_ship_cargo'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['big_ship_cargo'])."\">".Format::shortly_number($PlanetsWhile['big_ship_cargo'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['light_hunter'])."\">".Format::shortly_number($PlanetsWhile['light_hunter'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['heavy_hunter'])."\">".Format::shortly_number($PlanetsWhile['heavy_hunter'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['crusher'])."\">".Format::shortly_number($PlanetsWhile['crusher'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['battle_ship'])."\">".Format::shortly_number($PlanetsWhile['battle_ship'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['colonizer'])."\">".Format::shortly_number($PlanetsWhile['colonizer'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['recycler'])."\">".Format::shortly_number($PlanetsWhile['recycler'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['spy_sonde'])."\">".Format::shortly_number($PlanetsWhile['spy_sonde'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['bomber_ship'])."\">".Format::shortly_number($PlanetsWhile['bomber_ship'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['solar_satelit'])."\">".Format::shortly_number($PlanetsWhile['solar_satelit'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['destructor'])."\">".Format::shortly_number($PlanetsWhile['destructor'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['dearth_star'])."\">".Format::shortly_number($PlanetsWhile['dearth_star'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['battleship'])."\">".Format::shortly_number($PlanetsWhile['battleship'])."</a></th>
				</tr>";

				$parse['defenses']	.=	"
				<tr>
				<th width=\"10%\">".$Planettt."</th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['misil_launcher'])."\">".Format::shortly_number($PlanetsWhile['misil_launcher'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['small_laser'])."\">".Format::shortly_number($PlanetsWhile['small_laser'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['big_laser'])."\">".Format::shortly_number($PlanetsWhile['big_laser'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['gauss_canyon'])."\">".Format::shortly_number($PlanetsWhile['gauss_canyon'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['ionic_canyon'])."\">".Format::shortly_number($PlanetsWhile['ionic_canyon'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['buster_canyon'])."\">".Format::shortly_number($PlanetsWhile['buster_canyon'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['small_protection_shield'])."\">".Format::shortly_number($PlanetsWhile['small_protection_shield'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['big_protection_shield'])."\">".Format::shortly_number($PlanetsWhile['big_protection_shield'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['interceptor_misil'])."\">".Format::shortly_number($PlanetsWhile['interceptor_misil'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['interplanetary_misil'])."\">".Format::shortly_number($PlanetsWhile['interplanetary_misil'])."</a></th>
				</tr>";

				$parse['buildings']	.=	"
				<tr>
				<th width=\"10%\">".$Planettt."</th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['metal_mine'])."\">".Format::shortly_number($PlanetsWhile['metal_mine'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['crystal_mine'])."\">".Format::shortly_number($PlanetsWhile['crystal_mine'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['deuterium_sintetizer'])."\">".Format::shortly_number($PlanetsWhile['deuterium_sintetizer'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['solar_plant'])."\">".Format::shortly_number($PlanetsWhile['solar_plant'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['fusion_plant'])."\">".Format::shortly_number($PlanetsWhile['fusion_plant'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['robot_factory'])."\">".Format::shortly_number($PlanetsWhile['robot_factory'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['nano_factory'])."\">".Format::shortly_number($PlanetsWhile['nano_factory'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['hangar'])."\">".Format::shortly_number($PlanetsWhile['hangar'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['metal_store'])."\">".Format::shortly_number($PlanetsWhile['metal_store'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['crystal_store'])."\">".Format::shortly_number($PlanetsWhile['crystal_store'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['deuterium_store'])."\">".Format::shortly_number($PlanetsWhile['deuterium_store'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['laboratory'])."\">".Format::shortly_number($PlanetsWhile['laboratory'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['terraformer'])."\">".Format::shortly_number($PlanetsWhile['terraformer'])."</a></th>
				<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['ally_deposit'])."\">".Format::shortly_number($PlanetsWhile['ally_deposit'])."</a></th>
				<th width=\"30%\"><a title=\"".Format::pretty_number($PlanetsWhile['silo'])."\">".Format::shortly_number($PlanetsWhile['silo'])."</a></th>
				</tr>";

				if ( $PlanetsWhile['planet_type'] == 3 )
				{
					$parse['moon_buildings'] .=	"
					<tr>
					<th width=\"10%\">".$Moons."</th>
					<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['mondbasis'])."\">".Format::shortly_number($PlanetsWhile['mondbasis'])."</a></th>
					<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['phalanx'])."\">".Format::shortly_number($PlanetsWhile['phalanx'])."</a></th>
					<th width=\"10%\"><a title=\"".Format::pretty_number($PlanetsWhile['sprungtor'])."\">".Format::shortly_number($PlanetsWhile['sprungtor'])."</a></th>
					</tr>";
				}




				if ( $MoonZ != 0 )
				{
					$parse['MoonHave']	=	"<a href=\"javascript:animatedcollapse.toggle('especiales')\" class=\"link\">
					<img src=\"../styles/images/Adm/arrowright.png\" width=\"16\" height=\"10\"/> ".$lang['moon_build']."</a>";
				}
				else
				{
					$parse['MoonHave']	=	"<span class=\"no_moon\"><img src=\"../styles/images/Adm/arrowright.png\" width=\"16\" height=\"10\"/>
					".$lang['moon_build']."&nbsp;".$lang['ac_moons_no']."</span>";
				}
			}

			$DestruyeD	=	0;

			if ( $PlanetsWhile["destruyed"] > 0 )
			{
				$parse['destroyed']	.=	"
				<tr>
				<th>".$PlanetsWhile['name']."</th>
				<th>".$PlanetsWhile['id']."</th>
				<th>[".$PlanetsWhile['galaxy'].":".$PlanetsWhile['system'].":".$PlanetsWhile['planet']."]</th>
				<th>".date("d-m-Y   H:i:s", $PlanetsWhile['destruyed'])."</th>
				</tr>";

				$DestruyeD++;
			}


			if ($DestruyeD != 0)
			{
				$parse['DestructionHave']	=	"<a href=\"javascript:animatedcollapse.toggle('destr')\" class=\"link\">
				<img src=\"../styles/images/Adm/arrowright.png\" width=\"16\" height=\"10\"/> ".$lang['ac_recent_destroyed_planets']."</a>";
			}
			else
			{
				$parse['DestructionHave']	=	"<span class=\"no_moon\"><img src=\"../styles/images/Adm/arrowright.png\" width=\"16\" height=\"10\"/>
				".$lang['ac_recent_destroyed_planets']."&nbsp;".$lang['ac_isnodestruyed']."</span>";
			}
		}


		display (parsetemplate(gettemplate("adm/AccountDataBody"), $parse), FALSE, '', TRUE, FALSE);
	}
}

display (parsetemplate(gettemplate("adm/AccountDataIntro"), $parse), FALSE, '', TRUE, FALSE);
?>