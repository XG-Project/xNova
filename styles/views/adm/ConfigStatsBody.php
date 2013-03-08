<section class="page">
	<section class="content-table table thin divided settings">
		<form action="admin.php?page=stats" method="post" accept-charset="UTF-8">
			<h3>{cs_title}</h3>

			<div class="row">
				<div class="content"><label for="stat_settings">{cs_point_per_resources_used} ({cs_resources})</label></div>
				<div class="content">
					<input type="number" name="stat_settings" id="stat_settings" value="{stat_settings}" min="1">
				</div>
			</div>

			<div class="row">
				<div class="content"><label for="stat_amount">{cs_users_per_block}</label></div>
				<div class="content">
					<input type="number" name="stat_amount" id="stat_amount" value="{stat_amount}" min="10">
				</div>
			</div>

			<div class="row">
				<div class="content"><label for="stat_flying">{cs_fleets_on_block}</label></div>
				<div class="content">
					<input type="checkbox" name="stat_flying" id="stat_flying"{flying_checked}>
				</div>
			</div>

			<div class="row">
				<div class="content"><label for="stat_update_time">{cs_time_between_updates} ({cs_minutes})</label></div>
				<div class="content">
					<input type="number" name="stat_update_time" id="stat_update_time" value="{stat_update_time}" min="1">
				</div>
			</div>

			<div class="row">
				<div class="content"><label for="stat">{cs_points_to_zero}</label></div>
				<div class="content">
					<input type="checkbox" name="stat" id="stat"{stat_checked}>
				</div>
			</div>

			<div class="row">
				<div class="content"><label for="stat_level">{cs_access_lvl}</label></div>
				<div class="content">
					<select name="stat_level" id="stat_level">
						<option value="1"{selected_l1}>{rank_0}</option>
						<option value="2"{selected_l2}>{rank_1}</option>
						<option value="3"{selected_l3}>{rank_2}</option>
						<option value="4"{selected_l4}>{rank_3}</option>
					</select>
				</div>
			</div>

			<div class="content">{cs_timeact_1} {timeact}</div>

			<div class="content"><input type="submit" value="{cs_save_changes}"></div>
		</form>
	</section>
</section>