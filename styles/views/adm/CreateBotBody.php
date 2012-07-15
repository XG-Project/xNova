<script>document.body.style.overflow = "auto";</script>
<style>input{text-align:center;}.red{color:#FF3300;}</style>
<body>
<form action="" method="post">
<table width="45%">
{display}
<tr>
  <td class="c" colspan="2">Crear un nuevo Bot </td>
</tr>

<tr>
  <th>Id del Jugador</th><th><input type="text" name="player"></th></tr>
<tr>
  <th>Tiempo de Actualización </th><th><input type="text" name="every_time"></th></tr>
<tr><th colspan="2"><input type="submit" value="Crear Bot"></th></tr>
<tr>
   <th colspan="2" style="text-align:left;"><a href="BotListPage.php">Volver a la Lista de Bots</a>&nbsp;<a href="BotListPage.php?page=new_bot">{new_creator_refresh}</a></th>
</tr>
</table>
</form>
</body>