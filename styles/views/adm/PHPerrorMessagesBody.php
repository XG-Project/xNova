<section class="page">
	<section class="content-table">
		<h3>{er_php_error_list} [<a href="admin.php?page=errors&amp;errors=php&amp;deleteall=yes">{er_dlte_all}</a>]</h3>
		<div class="content">
			<form action="admin.php?page=errors&amp;errors=php" method="post" accept-charset="UTF-8">{er_php_show}:
				<input type="checkbox" name="show_2"{checked_2}> E_WARNING
				<input type="checkbox" name="show_8"{checked_8} > E_NOTICE
				<input type="checkbox" name="show_2048"{checked_2048}> E_STRICT
				<input type="checkbox" name="show_4096"{checked_4096}> E_RECOVERABLE_ERROR
				<input type="checkbox" name="show_8192"{checked_8192}> E_DEPRECATED
				<input type="checkbox" name="show_32767"{checked_32767}> E_ALL
				<input type="submit" name="submit" value="{er_filter}">
			</form>
		</div>
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