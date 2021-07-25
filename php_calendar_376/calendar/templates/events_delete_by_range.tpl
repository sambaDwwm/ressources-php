<fieldset class="cal_fieldset">
{h:legend}
<table class="fieldset_content" align="center" border="0">
<tr><td colspan="3" align="center" style="padding-bottom:5px;"><div id="divEventsDeleteByRange_msg"></div></td></tr>
<tr valign="top">
    <td>
        <table border="0" align="right">
        <tr valign="top">
            <td class="cal_right" nowrap="nowrap">{h:lan_from}:</td>
            <td></td>
            <td class="cal_right" nowrap="nowrap">{h:ddl_from}</td>
        </tr>
        <tr valign="top">
            <td class="cal_right" nowrap="nowrap">{h:lan_to}:</td>
            <td></td>
            <td class="cal_right" nowrap="nowrap">{h:ddl_to}</td>
        </tr>
        </table>
    </td>
    <td width="20px"></td>
    <td class="cal_left">
        {h:lan_category_name}
        {h:ddl_categories}
        {h:lan_location_name}
        {h:ddl_locations}
    </td>
</tr>
<tr><td colspan="3" align="center" style="height:20px;padding:0px;"></td></tr>
<tr>
    <td colspan="3" align="center">
        <input class="form_button" type="button" name="btnSubmit" value="{h:lan_delete_events}" onclick="javascript:phpCalendar.eventsDeleteByRange();"/>
        &nbsp;- {h:lan_or} -&nbsp;
        <a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:phpCalendar.eventsBack();">{h:lan_cancel}</a>
    </td>
</tr>
</table>
</fieldset>