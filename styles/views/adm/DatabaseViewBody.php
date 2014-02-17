<section class="page">
	<section class="content-table table thin medium">
		<form action="admin.php?page=database" method="post" accept-charset="UTF-8">
			<h3>{od_opt_db}</h3>
			{tables}
			<div class="content">
				<input type="submit" value="{od_optimize}" name="optimize">
				<input type="submit" value="{od_repair}" name="repair">
				<input type="submit" value="{od_check}" name="check">
			</div>
		</form>
	</section>
</section>