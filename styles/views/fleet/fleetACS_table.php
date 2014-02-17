		<table width="519" border="0" cellpadding="0" cellspacing="1">
			<tr height="20">
				<td class="c" colspan="2">{fl_sac_of_fleet} "{acs_code}"</td>
			</tr>
			<tr height="20">
				<td class="c" colspan="2">{fl_modify_sac_name}</td>
			</tr>
			<tr>
				<th colspan="2">
					<form action="game.php?page=fleetACS" method="POST">
						<input name="fleetid" value="{fleetid}" type="hidden">
						<input name="txt_name_acs" type="text" id="txt_name_acs" value="{acs_code}" />
						<br />
						<input type="submit" value="{fl_continue}" />
					</form>
				</th>
			</tr>
			<tr>
				<th>
					<table width="100%" border="0" cellpadding="0" cellspacing="1">
						<tr height="20">
							<td class="c">{fl_members_invited}</td>
							<td class="c">{fl_invite_members}</td>
						</tr>
						<tr>
							<th width="50%">
								<select size="5">
									{invited_members}
								</select>
							</th>
							<form action="game.php?page=fleetACS" method="POST">
								<input type="hidden" name="add_member_to_acs" value="madnessred" />
								<input name="fleetid" value="{fleetid}" type="hidden">
								<input name="acs_invited" value="{acs_invited}" type="hidden">
							<td>
								<input name="addtogroup" type="text" /> <br /><input type="submit" value="{fl_continue}" />
							</td>
							</form>
							<br />
							{add_user_message}
						</tr>
					</table>
				</th>
			</tr>
        </table>