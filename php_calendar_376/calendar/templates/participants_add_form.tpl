<table class="fieldset_content" align="center" border="0">
<tr><td align="center" colspan="3" style="padding-bottom:5px;"><div id="divParticipantsAdd_msg"></div></td></tr>
<tr valign="middle">
    <td width="35%" class="cal_right"><label for="first_name">{h:lan_participant_first_name}</label>: <span class="cal_star">*</span></td>
    <td>&nbsp;</td>
    <td class="cal_left"><input type="text" style="width:210px" id="first_name" name="first_name" value="" maxlength="50" /></td>
</tr>
<tr valign="middle">
    <td class="cal_right"><label for="last_name">{h:lan_participant_last_name}</label>: <span class="cal_star">*</span></td>
    <td>&nbsp;</td>
    <td class="cal_left"><input type="text" style="width:210px" id="last_name" name="last_name" value="" maxlength="50" /></td>
</tr>
<tr valign="middle">
    <td class="cal_right"><label for="email">{h:lan_participant_email}</label>:</td>
    <td>&nbsp;</td>
    <td class="cal_left"><input type="text" style="width:250px" id="email" name="email" value="" maxlength="50" /></td>
</tr>
<tr><td align="center" colspan="3" style="height:20px;padding:0px;"></td></tr>
<tr>
    <td colspan="3" align="center">
        <input class="form_button" type="button" name="btnSubmit" value="{h:lan_add_participant}" onclick="javascript:phpCalendar.participantsAdd();"/>
        &nbsp;- {h:lan_or} -&nbsp;
        <a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:phpCalendar.participantsCancel();">{h:lan_cancel}</a>
    </td>
</tr>
</table>