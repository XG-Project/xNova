<br />
<div id="content">
<table border="0" cellpadding="0" cellspacing="1" width="519">
	<tr height="20">
		<td class="c" colspan="2"><span class="success">{fl_fleet_sended}</span></td>
	</tr>
    <tr height="20">
        <th>{fl_mission}</th>
        <th>{mission}</th>
	</tr>
    <tr height="20">
        <th>{fl_distance}</th>
        <th>{distance}</th>
    </tr>
    <tr height="20">
        <th>{fl_fleet_speed}</th>
        <th>{speedallsmin}</th>
    </tr>
    <tr height="20">
        <th>{fl_fuel_consumption}</th>
        <th>{consumption}</th>
    </tr>
    <tr height="20">
        <th>{fl_from}</th>
        <th>{from}</th>
    </tr>
    <tr height="20">
        <th>{fl_destiny}</th>
        <th>{destination}</th>
    </tr>
    <tr height="20">
        <th>{fl_arrival_time}</th>
        <th>{start_time}</th>
    </tr>
    <tr height="20">
        <th>{fl_return_time}</th>
        <th>{end_time}</th>
    </tr>
    <tr height="20">
        <td class="c" colspan="2">{fl_fleet}</td>
        {fleet_list}
    </tr>
</table>
</div>
<script type="text/javascript">
var zeit = new Date();
var ende = zeit.getTime();
ende = ende + 100;
function countdown() {
var zeit2 = new Date();
var jetzt = zeit2.getTime();
if(jetzt >= ende) {
window.location.href="game.php?page=fleet";
}
}
setInterval(countdown, 4000);
</script>