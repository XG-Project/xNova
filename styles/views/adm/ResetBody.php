<section class="page">
	<section class="content-table table thin divided">
		{result}
		<form action="admin.php?page=reset" method="post" accept-charset="UTF-8">
			<h3>{re_defenses_and_ships}</h3>
			<div class="row">
				<div class="content">{re_defenses}</div>
				<div class="content">
					<input type="checkbox" name="defenses">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_ships}</div>
				<div class="content">
					<input type="checkbox" name="ships">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_hangar}</div>
				<div class="content">
					<input type="checkbox" name="h_d">
				</div>
			</div>

			<h3>{re_buildings}</h3>
			<div class="row">
				<div class="content">{re_buildings_pl}</div>
				<div class="content">
					<input type="checkbox" name="dif_p">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_buildings_lu}</div>
				<div class="content">
					<input type="checkbox" name="edif_l">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_buildings}</div>
				<div class="content">
					<input type="checkbox" name="edif">
				</div>
			</div>

			<h3>{re_inve_ofis}</h3>
			<div class="row">
				<div class="content">{re_ofici}</div>
				<div class="content">
					<input type="checkbox" name="ofis">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_investigations}</div>
				<div class="content">
					<input type="checkbox" name="inves">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_invest}</div>
				<div class="content">
					<input type="checkbox" name="inves_c">
				</div>
			</div>

			<h3>{re_resources}</h3>
			<div class="row">
				<div class="content">{re_resources_dark}</div>
				<div class="content">
					<input type="checkbox" name="dark">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_resources_met_cry}</div>
				<div class="content">
					<input type="checkbox" name="resources">
				</div>
			</div>

			<h3>{re_general}</h3>
			<div class="row">
				<div class="content">{re_reset_moons}</div>
				<div class="content">
					<input type="checkbox" name="moons">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_notes}</div>
				<div class="content">
					<input type="checkbox" name="notes">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_rw}</div>
				<div class="content">
					<input type="checkbox" name="rw">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_buddies}</div>
				<div class="content">
					<input type="checkbox" name="friends">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_allys}</div>
				<div class="content">
					<input type="checkbox" name="alliances">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_fleets}</div>
				<div class="content">
					<input type="checkbox" name="fleet">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_errors}</div>
				<div class="content">
					<input type="checkbox" name="errors">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_banned}</div>
				<div class="content">
					<input type="checkbox" name="banned">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_messages}</div>
				<div class="content">
					<input type="checkbox" name="messages">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_statpoints}</div>
				<div class="content">
					<input type="checkbox" name="statpoints">
				</div>
			</div>
			<div class="row">
				<div class="content">{re_reset_bots}</div>
				<div class="content">
					<input type="checkbox" name="bots">
				</div>
			</div>

			<div class="row">
				<div class="content reset-universe">{re_reset_all}</div>
				<div class="content">
					<input type="checkbox" name="resetall">
				</div>
			</div>

			<div class="content"><input type="submit" value="{button_submit}" onclick="return confirm('{re_reset_universe_confirmation}');"></div>
		</form>
	</section>
</section>