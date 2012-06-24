<script>document.body.style.overflow = "auto";</script>
<body>
<form method="post" action="">
<table width="40%">
<tr>
	<td class="c" colspan="2">{et_md5_encripter}</td>
</tr>
<tr>
	<th>{et_pass}</th>
	<th><input type="text" name="pass" size="40" value="{sent_pass}"></th>
</tr><tr>
	<th>{et_md5}</th>
	<th><input type="text" name="md5w" size="40" style="font-family:monospace;" value="{md5_res}"></th>
</tr><tr>
	<th>{et_sha1}</th>
	<th><input type="text" name="sha1w" size="40" style="font-family:monospace;" value="{sha1_res}"></th>
</tr><tr>
	<th colspan="2"><input type="submit" name="ok" value="{et_encript}"></th>
</tr>
</table>
</form>
</body>