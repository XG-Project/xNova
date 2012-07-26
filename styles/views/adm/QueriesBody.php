<section class="page">
	<section class="content-table">
		<form action="admin.php?page=query" method="post" accept-charset="UTF-8">
			<h3>{qe_execute}</h3>
			{display}
			<div class="content note">{qe_note}</div>

			<div class="content">
				<textarea name="query"></textarea>
			</div>
			<div class="content">
				<input type="submit" value="{button_submit}">
			</div>
		</form>
	</section>
</section>