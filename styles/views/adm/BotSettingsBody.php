<body>
<table width="700">
	<tr>
		<td class="c" colspan="8">{bot_list} <a href="BotSettingsPage.php?deleteall=yes" onclick="return confirm('{bot_delete_all_confirm}');">[{er_dlte_all}]</a><a href="BotSettingsPage.php?page=delete_log" onclick="return confirm('Si borra el archivo de logs borrará la información de lo que los bots han hecho hasta ahora. ¿Estás seguro?');">[{log_delete_link}]</a></td>
	</tr>
	<tr>
		<td class="b" colspan="8">{mu_user_logs}:<br><textarea style="resize: none;" rows="10" cols="500" overflow="scroll" readonly>{log}</textarea></td>
	</tr>
	<tr>
		<td class="c" colspan="8"><a href="BotSettingsPage.php?page=new_bot">[{bot_assign}]</a></td>
	</tr>
	<tr>
		<td class="c" width="25">{input_id}</td>
		<td class="c" width="25">{bot_user}</td>
		<td class="c" width="250">{bot_last_activity}</td>
		<td class="c" width="250">{bot_next_activity}</td>
		<td class="c" width="100">{bot_minutes_per_day}</td>
		<td class="c" width="230">{bot_last_planet}</td>
		<td class="c" width="100">{button_delete}</td>
		<td class="c" width="95">{bot_info}</td>
	</tr>
	{bots_list}
</table>
</body>