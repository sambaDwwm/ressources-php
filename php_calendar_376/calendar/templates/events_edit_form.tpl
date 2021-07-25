<fieldset class="cal_fieldset">
<legend class="cal_legend bold"><span class="single">{h:lan_edit_event}</span></legend>
<table class="fieldset_content" align="center" border="0" cellspacing="4">
<tr><td colspan="3" align="center" style="padding-bottom:5px;"><div id="divEventsEdit_msg"></div></td></tr>
<tr valign="middle">
    <td width="30%" class="cal_right"><label for="event_name">{h:lan_event_name}</label>: <span class="cal_star">*</span></td>
    <td>&nbsp;</td>
    <td class="cal_left"><input type="text" style="width:400px" id="event_name" name="event_name" value="{h:event_name}" maxlength="70" /></td>
</tr>
<tr valign="middle">
    <td width="30%" class="cal_right"><label for="event_name">{h:lan_event_url}</label>: </td>
    <td>&nbsp;</td>
    <td class="cal_left"><input type="text" style="width:400px" id="event_url" name="event_url" value="{h:event_url}" maxlength="255" /></td>
</tr>
<tr valign="top">
    <td class="cal_right"><label for="event_description">{h:lan_event_description}</label>:</td>
    <td></td>
    <td class="cal_left"><textarea style="width:400px;height:92px;" id="event_description" name="event_description">{h:event_description}</textarea></td>
</tr>
<tr valign="middle">
    <td class="cal_right"><label for="sel_category_name">{h:lan_category_name}</label></td>
    <td></td>
    <td class="cal_left">{h:ddl_categories}</td>
</tr>
<tr valign="middle">
    <td class="cal_right"><label for="sel_category_name">{h:lan_location_name}</label></td>
    <td></td>
    <td class="cal_left">{h:ddl_locations}</td>
</tr>
<tr valign="top">
    <td class="cal_right">{h:lan_event_date}</td>
    <td></td>
    <td class="cal_left"><label type="text">{h:event_date}</label></td>
</tr>
<tr valign="top">
    <td class="cal_right">{h:lan_start_time}</td>
    <td></td>
    <td class="cal_left"><label type="text">{h:event_time}</label></td>
</tr>
<tr><td colspan="3" align="center" style="height:10px;padding:0px;"></td></tr>
<tr>
    <td colspan="3" align="center">
        <input class="form_button" type="button" name="btnSubmit" value="{h:lan_update_event}" onclick="javascript:phpCalendar.eventsUpdate({h:event_id});"/>
        &nbsp;- {h:lan_or} -&nbsp;
        <a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:phpCalendar.eventsBack();">{h:lan_cancel}</a>
    </td>
</tr>
</table>
</fieldset>