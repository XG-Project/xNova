<script>document.body.style.overflow = "auto";</script>
<body>
<table width="700">
	<tr>
		<td class="c" colspan="8">Lista de Bots [<a href="BotSettingsPage.php?deleteall=yes" onClick="return confirm('Vas a borrar todos los bots. Estas seguro?');">{er_dlte_all}</a>][<a href="BotSettingsPage.php?page=delete_log" onClick="return confirm('Solo borre este archivo si está lento el servidor, presenta errores, o el archivo pesa demasiado. Desea borrarlo de todas formas?');">Purgar Log</a>]</td>
	</tr>
	<tr>
		<td class="b" colspan="8">Log:<br><textarea style="resize: none;" rows="10" cols="500" overflow="scroll" readonly>{log}</textarea></td>
	</tr>
	<tr>
		<td class="c" colspan="8">[<a href="BotSettingsPage.php?page=new_bot">Crear un Bot</a>]</td>
	</tr>
	<tr>
		<td class="c" width="25">Id</td>
		<td class="c" width="25">Jugador</td>
		<td class="c" width="250">Última Actividad</td>
		<td class="c" width="250">Próxima Actividad</td>
		<td class="c" width="100">Minutos diarios</td>
		<td class="c" width="230">Último Planeta</td>
		<td class="c" width="100">{button_delete}</td>
		<td class="c" width="95">Info</td>
	</tr>
	{bots_list}
</table>
</body>