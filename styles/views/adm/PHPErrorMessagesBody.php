<body>
<table width="70%">
    <tr>
    	<td class="c" colspan="8">{er_php_error_list} [<a href="?page=php&deleteall=yes">{er_dlte_all}</a>]</td>
    </tr>
    <tr>
    	<td colspan="8"><span style="font-size: 0.8em"><form action="ErrorPage.php?page=php" method="post">{er_php_show}:
    		<input type="checkbox" name="show_2" {checked_2} /> E_WARNING
    		<input type="checkbox" name="show_8" {checked_8} /> E_NOTICE
    		<input type="checkbox" name="show_2048" {checked_2048} /> E_STRICT
    		<input type="checkbox" name="show_4096" {checked_4096} /> E_RECOVERABLE_ERROR
    		<input type="checkbox" name="show_8192" {checked_8192} /> E_DEPRECATED
    		<input type="checkbox" name="show_32767" {checked_32767} /> E_ALL
    		<input type="submit" name="submit" value="{er_filter}" />
    	</form></span></td>
    </tr>
    <tr>
        <td class="c" width="25">{input_id}</td>
        <td class="c" width="50">{er_date}</td>
        <td class="c" width="70">{er_user}</td>
        <td class="c" width="50">{er_level}</td>
        <td class="c" width="100">{er_file}</td>
        <td class="c" width="100">{er_line}</td>
        <td class="c" width="100">{er_data}</td>
        <td class="c" width="25">{button_delete}</td>
    </tr>
    {errors_list}
</table>
</body>