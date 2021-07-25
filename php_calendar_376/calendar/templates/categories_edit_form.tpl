<fieldset class="cal_fieldset">
<legend class="cal_legend bold"><span class="single">{h:lan_edit_category}</span></legend>
<table class="fieldset_content" align="center" border="0">
<tr><td colspan="2"></td><td class="cal_left" style="padding-bottom:5px;"><div id="divCategoriesEdit_msg"></div></td></tr>
<tr valign="middle">
    <td width="30%" class="cal_right"><label for="category_name">{h:lan_category_name}</label>: <span class="cal_star">*</span></td>
    <td>&nbsp;</td>
    <td class="cal_left"><input type="text" style="width:400px" id="category_name" name="category_name" value="{h:cat_name}" maxlength="50" /></td>
</tr>
<tr valign="top">
    <td class="cal_right"><label for="category_description">{h:lan_category_description}</label>:</td>
    <td></td>
    <td class="cal_left"><textarea style="width:400px;height:80px;" id="category_description" name="category_description">{h:cat_description}</textarea></td>
</tr>
<tr valign="middle">
    <td class="cal_right"><label for="category_color">{h:lan_category_color}</label></td>
    <td></td>
    <td>{h:ddl_colors}</td>
</tr>
<tr valign="middle">
    <td class="cal_right"><label for="category_duration">{h:lan_duration}</label>:</td>
    <td></td>
    <td class="cal_left">{h:ddl_durations}</td>
</tr>
<tr valign="middle">
    <td class="cal_right"><label for="show_in_filter">{h:lan_show_in_filter}</label>:</td>
    <td></td>
    <td class="cal_left">{h:chk_show_in_filter}</td>
</tr>
<tr><td align="center" colspan="3" style="height:20px;padding:0px;"></td></tr>
<tr>
    <td colspan="3" align="center">
        <input class="form_button" type="button" name="btnSubmit" value="{h:lan_update_category}" onclick="javascript:phpCalendar.categoriesUpdate({h:category_id});"/>
        &nbsp;- {h:lan_or} -&nbsp;
        <a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:phpCalendar.categoriesCancel();">{h:lan_cancel}</a>
    </td>
</tr>
</table>
</fieldset>