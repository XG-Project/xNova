<section class="page">
	<section class="content-table">
		<h3>
			{bot_list} <a href="admin.php?page=bots&amp;deleteall=yes" onclick="return confirm('{bot_delete_all_confirm}');">[{er_dlte_all}]</a><a href="admin.php?page=bots&amp;mode=delete_log" onclick="return confirm('{log_delete_confirm}');">[{log_delete_link}]</a>
		</h3>
		<div class="content">{mu_user_logs}:</div>
		<div class="content">
				<textarea class="log fixed" name="text" readonly>{log}</textarea>
		</div>
	</section>

	<section class="content-table"><h3 class="unique"><a href="admin.php?page=bots&amp;page=new_bot">[{bot_assign}]</a></h3></section>

	<section class="content-table table">
		<div class="row title">
			<div class="content no_imp">{input_id}</div>
			<div class="content">{bot_user}</div>
			<div class="content no_imp">{bot_last_activity}</div>
			<div class="content no_imp">{bot_next_activity}</div>
			<div class="content no_imp">{bot_minutes_per_day}</div>
			<div class="content no_imp">{bot_last_planet}</div>
			<div class="content">{button_delete}</div>
			<div class="content no_imp">{bot_info}</div>
		</div>

		{bots_list}
	</section>
</section>