<nav class="select">
	<select onchange="window.open('admin.php'+this.options[this.selectedIndex].value,'_top')">
		<option value selected>{mu_select_nav}</option>
		{topnav}
		{Config_select}
		{View_select}
		{Edit_select}
		{Tools_select}
	</select>
</nav>

<nav class="left">
	{ConfigTable}
	{ViewTable}
	{EditTable}
	{ToolsTable}
</nav>