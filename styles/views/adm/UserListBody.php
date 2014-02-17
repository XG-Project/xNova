<script>document.body.style.overflow = "auto";</script> 
<body>
<table width="100%">
<tr>
	<td class="c" colspan="10">{ul_player_list}</td>
</tr>
<tr>
	<th><a href="?cmd=sort&type=id">{ul_id}</a></th>
	<th><a href="?cmd=sort&type=username">{ul_user}</a></th>
	<th><a href="?cmd=sort&type=email">{ul_email}</a></th>
	<th><a href="?cmd=sort&type=ip_at_reg">{ul_reg_ip}</a></th>
	<th><a href="?cmd=sort&type=user_lastip">{ul_last_ip}</a></th>
	<th><a href="?cmd=sort&type=register_time">{ul_register_date}</a></th>
	<th><a href="?cmd=sort&type=onlinetime">{ul_last_visit}</a></th>
	<th><a href="?cmd=sort&type=bana">{ul_state}</a></th>
	<th>{ul_delete}</th>
</tr>
{adm_ul_table}
<tr>
	<th class="b" colspan="10">{ul_there_are} {adm_ul_count} {ul_total_players}</th>
</tr>
</table>
</body>