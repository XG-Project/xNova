<section class="page">
	<script type="text/javascript">var UserList = new filterlist(document.users.id_1);</script>
	<section class="content-table">
		{display}
		<form action="admin.php?page=moderation&amp;moderation=2" method="post" accept-charset="UTF-8">
			<h3>{ad_authlevel_title}</h3>
			<div class="content">
				<select name="id_1" size="20">
					{list}
				</select>
			</div>
			<div class="content">
				<nav class="auth_selector">
					<a href="admin.php?page=moderate&amp;moderation=2&amp;get=adm">{ad_authlevel_aa}</a>
					<a href="admin.php?page=moderate&amp;moderation=2&amp;get=ope">{ad_authlevel_oo}</a>
					<a href="admin.php?page=moderate&amp;moderation=2&amp;get=mod">{ad_authlevel_mm}</a>
					<a href="admin.php?page=moderate&amp;moderation=2&amp;get=pla">{ad_authlevel_jj}</a>
					<a href="admin.php?page=moderate&amp;moderation=2">{ad_authlevel_tt}</a>
				</nav>
			</div>
			<div class="content">
				<nav class="a_to_z">
					{a_to_z}
				</nav>
			</div>
			<div class="content">
				<input name="regexp" onkeyup="UserList.set(this.value)">
				<input type="button" onclick="UserList.set(this.form.regexp.value)" value="{button_filter}">
				<input type="button" onclick="UserList.reset();this.form.regexp.value=''" value="{button_deselect}">
			</div>
			<div class="content">
				<span>{ad_authlevel_insert_id}: <input name="id_2" type="text"></span>
				<span>{ad_authlevel_auth}: <select name="authlevel">{authlevels}</select></span>
			</div>
			<div class="content">
				<input type="submit" value="{button_submit}">
			</div>
		</form>
	</section>
</section>