<script>document.body.style.overflow = "auto";</script>
<body>
<table width="400">
    <tr>
    	<td class="c" colspan="8">Lista de Bots [<a href="BotSettingsPage.php?deleteall=yes" onClick="return confirm('Vas a borrar todos los bots. Estas seguro?');">{er_dlte_all}</a>][<a href="BotSettingsPage.php?page=deletenew_bot" onClick="return confirm('Solo borre este archivo si está lento el servidor, presenta errores, o el archivo pesa demasiado. Desea borrarlo de todas formas?');">Purgar Bots</a>]</td>
    </tr>
    <tr>
    	<td class="c" colspan="8">[<a href="BotSettingsPage.php?page=new_bot">Crear un Bot</a>]</td>
    </tr>
    <tr>
        <td class="c" width="25">Id</td>
        <td class="c" width="170">Jugador</td>
        <td class="c" width="170">Última Actividad</td>
        <td class="c" width="230">Tiempo de Actualización</td>
        <td class="c" width="230">Último Planeta</td>
        <td class="c" width="170">Tipo</td>
        <td class="c" width="95">{button_delete}</td>
        <td class="c" width="95">Info</td>
    </tr>
    <tr>{bots_list}</tr>
</table>
</body>