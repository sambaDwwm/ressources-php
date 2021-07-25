<fieldset class="cal_fieldset">
{h:legend}
<table class="fieldset_content" align="center" border="0">
<tr><td colspan="3" align="center" style="padding-bottom:10px;"><div id="divEventsExport_msg"></div></td></tr>
<tr valign="top">
    <td width="49%" class="cal_right" nowrap="nowrap">{h:lan_from}</td>
    <td></td>
    <td width="49%" class="cal_left" nowrap="nowrap">{h:ddl_from}</td>
</tr>
<tr valign="top">
    <td class="cal_right" nowrap="nowrap">{h:lan_to}:</td>
    <td></td>
    <td class="cal_left" nowrap="nowrap">{h:ddl_to}</td>
</tr>
<tr valign="top" align="center">
    <td class="cal_right" nowrap="nowrap">{h:lan_category_name}</td>
    <td></td>
    <td class="cal_left" nowrap="nowrap">{h:ddl_categories}</td>
</tr>
<tr valign="top" align="center">
    <td class="cal_right" nowrap="nowrap">{h:lan_location_name}</td>
    <td></td>
    <td class="cal_left" nowrap="nowrap">{h:ddl_locations}</td>
</tr>
<tr valign="top">
    <td class="cal_right" nowrap="nowrap">{h:lan_export_format}:</td>
    <td></td>
    <td class="cal_left" nowrap="nowrap">{h:ddl_export_formats}</td>
</tr>
<tr><td colspan="3" align="center" style="height:20px;padding:0px;"></td></tr>
<tr>
    <td colspan="3" align="center">
        <input class="form_button" type="button" name="btnSubmit" value="{h:lan_export_events}" onclick="javascript:phpCalendar.eventsExport();"/>
        &nbsp;- {h:lan_or} -&nbsp;
        <a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:phpCalendar.eventsBack();">{h:lan_cancel}</a>
    </td>
</tr>
</table>
</fieldset>