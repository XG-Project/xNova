<body>
<table width="70%">
	<tr>
		<td class="c" colspan="5">{er_sql_error_list} [<a href="?page=sql&deleteall=yes">{er_dlte_all}</a>]</td>
	</tr>
	<tr>
		<td class="c" width="25">{input_id}</td>
		<td class="c" width="70">{er_user}</td>
		<td class="c" width="100">{er_type}</td>
		<td class="c" width="230">{er_date}</td>
		<td class="c" width="95">{button_delete}</td>
	</tr>
	{errors_list}
</table>
</body>
<section class="page">
	<section class="content-table">
		<h3>{er_sql_error_list} [<a href="admin.php?page=errors&amp;errors=sql&amp;deleteall=yes">{er_dlte_all}</a>]</h3>
		<div class="content">{total_errors} {er_errors}</div>
	</section>

	<section class="content-table table">
		<div class="row title">
			<div class="content">{input_id}</div>
			<div class="content">{er_date}</div>
			<div class="content">{er_user}</div>
			<div class="content">{er_level}</div>
			<div class="content">{er_file}</div>
			<div class="content">{er_line}</div>
			<div class="content">{er_data}</div>
			<div class="content">{button_delete}</div>
		</div>

		{errors_list}
	</section>
</section>