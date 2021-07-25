<fieldset class="cal_fieldset">
{h:legend}
<table class="fieldset_content" align="center" border="0">
<tr><td colspan="2"></td><td class="cal_left" style="padding-bottom:5px;"><div id="divLocationsAdd_msg"></div></td></tr>
<tr valign="top">
    <td width="30%" class="cal_right"><label for="location_name">{h:lan_loc_name}</label>: <span class="cal_star">*</span></td>
    <td>&nbsp;</td>
    <td class="cal_left"><input type="text" style="width:400px" id="location_name" name="location_name" maxlength="50" /></td>
</tr>
<tr valign="top">
    <td class="cal_right"><label for="location_description">{h:lan_loc_description}</label>:</td>
    <td></td>
    <td class="cal_left"><textarea style="width:400px;height:80px;" id="location_description" name="location_description"></textarea></td>
</tr>
<tr valign="middle">
    <td class="cal_right"><label for="show_in_filter">{h:lan_show_in_filter}</label>:</td>
    <td></td>
    <td class="cal_left">{h:chk_show_in_filter}</td>
</tr>
<tr><td align="center" colspan="3" style="height:20px;padding:0px;"></td></tr>
<tr>
    <td align="center" colspan="3">
        <input class="form_button" type="button" name="btnSubmit" value="{h:lan_add_location}" onclick="javascript:phpCalendar.locationsAdd();" />
        &nbsp;- {h:lan_or} -&nbsp;
        <a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:phpCalendar.locationsCancel();">{h:lan_cancel}</a>
    </td>
</tr>
</table>
</fieldset>