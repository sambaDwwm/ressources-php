<fieldset class="cal_fieldset">
<legend class="cal_legend"><span class="single">{h:lan_category_details}</span></legend>
<table class="fieldset_content" align="center" border="0" width="430px">				
<tr valign="top">
    <td width="45%" class="cal_right">{h:lan_category_name}:</td>
    <td></td>
    <td class="cal_left"><label type="text">{h:category_name}</label></td>
</tr>
<tr valign="top">
    <td class="cal_right">{h:lan_category_description}:</td>
    <td></td>
    <td class="cal_left"><label type="text">{h:category_description}</label></td>
</tr>
<tr valign="top">
    <td class="cal_right">{h:lan_cat_color}</td>
    <td></td>
    <td class="cal_left">{h:ddl_colors}</td>
</tr>
<tr valign="top">
    <td class="cal_right">{h:lan_duration}</td>
    <td></td>
    <td class="cal_left">{h:ddl_durations}</td>
</tr>
<tr valign="top">
    <td class="cal_right">{h:lan_show_in_filter}:</td>
    <td></td>
    <td class="cal_left">{h:lbl_show_in_filter}</td>
</tr>
<tr><td colspan="3" align="center" style="height:20px;padding:0px;">&nbsp;</td></tr>
<tr>
    <td colspan="2"></td>
    <td class="cal_left">
        <a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:{h:js_back_function};">{h:lan_back}</a>
    </td>
</tr>
</table>
</fieldset>