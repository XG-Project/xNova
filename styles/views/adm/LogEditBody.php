<section class="page">
	<section class="content-table medium">
		<h3>
			<a href="admin.php?page=logs&amp;option=delete&amp;file={file}" onclick=" return confirm('{log_alert}');">[{log_delete_link}]</a>
			<a href="admin.php?page=logs&amp;option=links&amp;file={file}">[{log_go_back}]</a>
		</h3>
		<form action="admin.php?page=logs&amp;option=edit&amp;file={file}" method="post" accept-charset="UTF-8">
			<div class="content">
				{filename}
			</div>
			<div class="content">
				<textarea name="text">{content}</textarea>
			</div>
			<div class="content">
				<input type="submit" value="{log_input_value}">
			</div>
		</form>
	</section>
</section>