<br>
<script type="text/javascript">
	<!--
	date = new Date({micronow});
	function time()
	{
		var start= (new Date()).getMilliseconds();
		var hours=date.getHours();
		var minutes=date.getMinutes();
		var seconds=date.getSeconds();
		if(hours<10){ hours='0'+hours;}
		if(minutes<10){minutes='0'+minutes;}
		if(seconds<10){seconds='0'+seconds;}
		output=hours+":"+minutes+":"+seconds;
		document.getElementById('live_time').innerHTML=output;
		date.setSeconds(date.getSeconds()+1);
		var end= (new Date()).getMilliseconds();

		setTimeout("time()",1000-(end-start));
	}
	-->
</script>
<div id="content">
	<table width="519">
		<tr>
			<td class="c" colspan="3"><a href="game.php?page=overview&mode=renameplanet" title="{Planet_menu}">{ov_planet} "{planet_name}"</a> ({user_username})</td>
		</tr>
			{Have_new_message}
		<tr>
			<th>{ov_server_time}</th>
			<th colspan="2">{date} <div style="display:inline-block;" id="live_time"></div></th>
		</tr>
		<tr>
			<td colspan="3" class="c">{ov_events}</td>
		</tr>
			{fleet_list}
		<tr>
			{moon}
			<th{colspan}><img src="{dpath}planeten/{planet_image}.jpg" height="200" width="200"><br>{building}</th>
			{anothers_planets}
		</tr>
		<tr>
			<th>{ov_diameter}</th>
			<th colspan="2">{planet_diameter} {ov_distance_unit} (<a title="{Developed_fields}">{planet_field_current}</a> / <a title="{max_eveloped_fields}">{planet_field_max}</a> {fields})</th>
		</tr>
		<tr>
			<th>{ov_temperature}</th>
			<th colspan="2">{ov_aprox} {planet_temp_min}{ov_temp_unit} {ov_to} {planet_temp_max}{ov_temp_unit}</th>
		</tr>
		<tr>
			<th>{ov_position}</th>
			<th colspan="2"><a href="game.php?page=galaxy&mode=0&galaxy={galaxy_galaxy}&system={galaxy_system}">[{galaxy_galaxy}:{galaxy_system}:{galaxy_planet}]</a></th>
		</tr>
		<tr>
			<th>{ov_points}</th>
			<th colspan="2">{user_rank}</td>
		</tr>
	</table>
</div>