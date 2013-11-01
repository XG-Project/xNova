<section class="page">
	<section class="content-table table thin divided settings">
		<form action="admin.php?page=settings" method="post" accept-charset="UTF-8">

			<h3>{se_server_parameters}</h3>

			<div class="row">
				<div class="content">{se_name}</div>
				<div class="content">
					<input type="text" name="game_name" value="{game_name}" maxlength="60">
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_server_name}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_cookie_name}</div>
				<div class="content">
					<input type="text" name="cookie_name" value="{cookie}" maxlength="15">
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_cookie_advert}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_lang}</div>
				<div class="content">
					<select name="language">{language_settings}</select>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_general_speed}</div>
				<div class="content">
					<input type="number" name="game_speed" value="{game_speed}" min="1">
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_normal_speed}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_fleet_speed}</div>
				<div class="content">
					<input type="number" name="fleet_speed" value="{fleet_speed}" min="1">
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_normal_speed_fleett}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_resources_production_speed}</div>
				<div class="content">
					<input type="number" name="resource_multiplier"  value="{resource_multiplier}" min="1">
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_normal_speed_resoruces}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_forum_link}</div>
				<div class="content">
					<input type="url" name="forum_url" size="20" maxlength="254" value="{forum_url}">
				</div>
			</div>

			<div class="row">
				<div class="content">{se_max_users}</div>
				<div class="content">
					<input type="number" name="max_users" value="{max_users_sett}" min="0">
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_max_users_info}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_server_op_close}</div>
				<div class="content">
					<input name="closed" type="checkbox"{closed}>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_server_status_message}</div>
				<div class="content">
					<textarea name="close_reason" class="medium">{close_reason}</textarea>
				</div>
			</div>

			<h3>{se_server_planet_parameters}</h3>

			<div class="row">
				<div class="content">{se_initial_fields}</div>
				<div class="content">
					<input type="number" name="initial_fields" maxlength="5" size="5" value="{initial_fields}"> {se_fields}
				</div>
			</div>

			<div class="row">
				<div class="content">{se_metal_production}</div>
				<div class="content">
					<input type="number" name="metal_basic_income" maxlength="10" size="10" value="{metal_basic_income}"> {se_per_hour}
				</div>
			</div>

			<div class="row">
				<div class="content">{se_crystal_production}</div>
				<div class="content">
					<input type="number" name="crystal_basic_income" maxlength="10" size="10" value="{crystal_basic_income}"> {se_per_hour}
				</div>
			</div>

			<div class="row">
				<div class="content">{se_deuterium_production}</div>
				<div class="content">
					<input type="number" name="deuterium_basic_income" maxlength="10" size="10" value="{deuterium_basic_income}"> {se_per_hour}
				</div>
			</div>

			<h3>{se_several_parameters}</h3>

			<div class="row">
				<div class="content">{se_admin_protection}</div>
				<div class="content">
					<input type="checkbox" name="adm_attack"{adm_attack}>
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_title_admins_protection}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_debug_mode}</div>
				<div class="content">
					<input type="checkbox" name="debug"{debug}>
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_debug_message}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_ships_cdr}</div>
				<div class="content">
					<input type="number" name="Fleet_Cdr" value="{ships}" min="0" max="100"> %
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_ships_cdr_message}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_def_cdr}</div>
				<div class="content">
					<input type="number" name="Defs_Cdr" value="{defenses}"  min="0" max="100"> %
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_def_cdr_message}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_noob_protect}</div>
				<div class="content">
					<input type="checkbox" name="noobprotection"{noobprot}>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_noob_protect2}</div>
				<div class="content">
					<input type="number" name="noobprotectiontime" value="{noobprot2}">
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_noob_protect_e2}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_noob_protect3}</div>
				<div class="content">
					<input type="number" name="noobprotectionmulti" value="{noobprot3}">
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_noob_protect_e3}</section>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_log_php_errors}</div>
				<div class="content">
					E_WARNING <input type="checkbox" name="errors_2"{errors_2}><br>
					E_NOTICE <input type="checkbox" name="errors_8"{errors_8}><br>
					E_STRICT <input type="checkbox" name="errors_2048"{errors_2048}><br>
					E_RECOVERABLE_ERROR <input type="checkbox" name="errors_4096"{errors_4096}><br>
					E_DEPRECATED <input type="checkbox" name="errors_8192"{errors_8192}><br>
					E_ALL <input type="checkbox" name="errors_32767"{errors_32767}><br>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_log_bots}</div>
				<div class="content">
					<input type="checkbox" name="log_bots"{log_bots}>
				</div>
			</div>

			<div class="row">
				<div class="content">{se_date_format}</div>
				<div class="content">
					<textarea class="medium" name="date_format">{date_format}</textarea>
				</div>
				<div class="content info">
					<figure class="i info"></figure>
					<section>{se_date_format_message}</section>
				</div>
			</div>

			<div class="content">
				<input type="submit" value="{se_save_parameters}">
			</div>

		</form>
	</section>
</section>