<style>input{text-align:center;}.red{color:#FF3300;}</style>
<body>
<form action="" method="post">
<table width="45%">
{display}
<tr>
	<td class="c" colspan="2">{bot_assign}</td>
</tr>

<tr>
	<th>{bot_user_id}</th><th><input type="text" name="user"></th></tr>
<tr>
	<th>{bot_minutes_per_day}</th><th><input maxlength="5" type="text" name="minutes_per_day"></th></tr>
<tr><th colspan="2"><input type="submit" value="{bot_create}"></th></tr>
<tr>
	<th colspan="2" style="text-align:left;"><a href="BotSettingsPage.php">[{log_go_back}]</a>&nbsp;<a href="BotSettingsPage.php?page=new_bot">{new_creator_refresh}</a></th>
</tr>
</table>
</form>
</body>