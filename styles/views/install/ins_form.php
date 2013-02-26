<section class="warning">Antes de instalar cambia los permisos de los archivos <i>config.php</i> e <i>includes/xml/config.xml</i> a <i>CHMOD 0777</i></section>

<form action="install.php?mode=ins&amp;page=2" method="post" accept-charset="UTF-8">
	<label for="host">Servidor SQL: <span class="example">Ej. localhost</span></label>
	<input type="text" name="host" id="host" value="">

	<label for="db">Base de datos: <span class="example">Ej. xnova</span></label>
	<input type="text" name="db" id="db" value="">

	<label for="user">Usuario: <span class="example">Ej. root</span></label>
	<input type="text" name="user" id="user" value="">

	<label for="pass">Contraseña: <span class="example">Ej. 12345</span></label>
	<input type="password" name="password" id="pass" value="">

	<label for="prefix">Prefijo de las tablas: <span class="example">Ej. xn_</span></label>
	<input type="text" name="prefix" id="prefix" value="xn_">

	<input type="submit" value="Instalar">
</form>