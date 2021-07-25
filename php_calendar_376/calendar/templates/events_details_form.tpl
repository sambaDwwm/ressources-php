<fieldset class="cal_fieldset">
<legend class="cal_legend"><span class="single">{h:lan_event_details}</span></legend>
<table class="fieldset_content" align="center" border="0" cellspacing="4" width="430px">				
<tr valign="top">
    <td width="45%" class="cal_right">{h:lan_event_name}:</td>
    <td></td>
    <td class="cal_left"><label type="text">{h:event_name}</label></td>
</tr>
<tr valign="top">
    <td class="cal_right">{h:lan_event_url}:</td>
    <td></td>
    <td class="cal_left"><label type="text">{h:event_url}</label></td>
</tr>
<tr valign="top">
    <td class="cal_right">{h:lan_event_description}:</td>
    <td></td>
    <td class="cal_left"><label type="text">{h:event_description}</label></td>
</tr>
<tr valign="top">
    <td class="cal_right">{h:lan_category_name}</td>
    <td></td>
    <td class="cal_left"><label type="text">{h:category_name}</label></td>
</tr>
<tr valign="top">
    <td class="cal_right">{h:lan_location_name}</td>
    <td></td>
    <td class="cal_left"><label type="text">{h:location_name}</label></td>
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
<tr><td colspan="3" align="center" style="height:20px;padding:0px;">&nbsp;</td></tr>
<tr>
    <td colspan="2"></td>
    <td class="cal_left">
        <a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:{h:js_back_function};">{h:lan_back}</a>
    </td>
</tr>
</table>
{h:assigned_participants_list}
</fieldset>