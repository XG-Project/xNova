<section class="page">
	<section class="content-table table thin">
		<form action="admin.php?page=moderate&amp;moderation=1" method="post" accept-charset="UTF-8">
			<h3>{mod_title}</h3>
			<div class="row">
				<div class="content">
					<figure title="{mod_range}" class="arrow"></figure>
				</div>
				<div class="content">
					<figure title="{mod_power_config}" class="r4"></figure>
				</div>
				<div class="content">
					<figure title="{mod_power_edit}" class="r3"></figure>
				</div>
				<div class="content">
					<figure title="{mod_power_view}" class="r2"></figure>
				</div>
				<div class="content">
					<figure title="{mod_power_tools}" class="r5"></figure>
				</div>
				<div class="content">
					<figure title="{mod_power_loog}" class="r6"></figure>
				</div>
			</div>
			<div class="row">
				<div class="content">{mods}</div>
				<div class="content">
					<input type="checkbox" name="config_m"{config_m}>
				</div>
				<div class="content">
					<input type="checkbox" name="edit_m"{edit_m}>
				</div>
				<div class="content">
					<input type="checkbox" name="view_m"{view_m}>
				</div>
				<div class="content">
					<input type="checkbox" name="tools_m"{tools_m}>
				</div>
				<div class="content">
					<input type="checkbox" name="log_m"{log_m}>
				</div>
			</div>
			<div class="row">
				<div class="content">{oper}</div>
				<div class="content">
					<input type="checkbox" name="config_o"{config_o}>
				</div>
				<div class="content">
					<input type="checkbox" name="edit_o"{edit_o}>
				</div>
				<div class="content">
					<input type="checkbox" name="view_o"{view_o}>
				</div>
				<div class="content">
					<input type="checkbox" name="tools_o"{tools_o}>
				</div>
				<div class="content">
					<input type="checkbox" name="log_o"{log_o}>
				</div>
			</div>
			<div class="row">
				<div class="content">{adm}</div>
				<div class="content">
					<input type="checkbox" checked disabled>
				</div>
				<div class="content">
					<input type="checkbox" checked disabled>
				</div>
				<div class="content">
					<input type="checkbox" checked disabled>
				</div>
				<div class="content">
					<input type="checkbox" checked disabled>
				</div>
				<div class="content">
					<input type="checkbox" name="log_a"{log_a}>
				</div>
			</div>

			<div class="content"><input type="submit" value="{button_submit}"></div>
		</form>
	</section>

	<section class="content-table table thin">
		<div class="row">
			<div class="content">
				<figure title="{mod_power_config}" class="r4"></figure>
			</div>
			<div class="content">{mod_power_config}</div>
		</div>
		<div class="row">
			<div class="content">
				<figure title="{mod_power_config}" class="r3"></figure>
			</div>
			<div class="content">{mod_power_edit}</div>
		</div>
		<div class="row">
			<div class="content">
				<figure title="{mod_power_config}" class="r2"></figure>
			</div>
			<div class="content">{mod_power_view}</div>
		</div>
		<div class="row">
			<div class="content">
				<figure title="{mod_power_config}" class="r5"></figure>
			</div>
			<div class="content">{mod_power_tools}</div>
		</div>
		<div class="row">
			<div class="content">
				<figure title="{mod_power_config}" class="r6"></figure>
			</div>
			<div class="content">{mod_power_loog}</div>
		</div>
	</section>
</section>