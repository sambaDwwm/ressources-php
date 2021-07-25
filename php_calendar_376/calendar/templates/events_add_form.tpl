<fieldset class="cal_fieldset">
{h:legend}
<table class="fieldset_content" align="center" border="0">
<tr><td colspan="3" align="center" style="padding-bottom:5px;"><div id="divEventsAdd_msg"></div></td></tr>
<tr>
    <td valign="top">
        <table align="center" border="0" width="325px">
        <tr valign="top">
            <td>
                {h:lan_event_name}: <span class="cal_star">*</span><br />
                <input type="text" style="width:320px" id="event_name" name="event_name" maxlength="70" />
            </td>
        </tr>
        <tr valign="top">
            <td>
                {h:lan_event_url}: <br />
                <input type="text" style="width:320px" id="event_url" name="event_url" maxlength="255" />
            </td>
        </tr>
        <tr valign="top">
            <td>
                {h:lan_event_description}:<br />
                <textarea style="width:320px;height:92px;" id="event_description" name="event_description"></textarea>
            </td>
        </tr>
        </table>
    </td>
    <td width="20px" nowrap="nowrap"></td>
    <td valign="top">
        <table border="0" width="400px">				
        <tr>
            <td>{h:lan_category_name}</td><td colspan="2">{h:ddl_categories}</td>
        <tr>
        </tr>
            <td>{h:lan_location_name}</td><td colspan="2">{h:ddl_locations}</td>
        </tr>
        <tr><td colspan="3" nowrap="nowrap" height="9px"></td></tr>
        <tr>
            <td colspan="3">
                <input type="radio" class="btn_radio" name="event_insertion_type" id="event_insertion_type_1" value="1" checked="checked" onclick="phpCalendar.eventInsertionType(1)" /> <label for="event_insertion_type_1">{h:lan_add_event_to_list}</label>
                <br />
                <input type="radio" class="btn_radio" name="event_insertion_type" id="event_insertion_type_2" value="2" onclick="phpCalendar.eventInsertionType(2)" /> <label for="event_insertion_type_2">{h:lan_add_event_occurrences}</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <input type="hidden" id="event_insertion_subtype" name="event_insertion_subtype" value="one_time" />
                <div id="ea_wrapper" style="display:none;width:415px;">
                <fieldset class="cal_fieldset">
                <legend class="cal_legend">
                    [ <a id="ea_lnk_1" style="font-weight:bold;" href="javascript:void(0);" onclick="javascript:phpCalendar.switchElements('ea_one_time','ea_repeatedly','1','ea_lnk_1','ea_lnk_2','event_insertion_subtype','one_time')">{h:lan_one_time}</a> ]
                    [ <a id="ea_lnk_2" href="javascript:void(0);" onclick="javascript:phpCalendar.switchElements('ea_one_time','ea_repeatedly','2','ea_lnk_1','ea_lnk_2','event_insertion_subtype','repeat')">{h:lan_repeatedly}</a> ]
                </legend>
                <div id="ea_one_time" style="display:;padding:4px;">
                    <table border=0>
                    <tr valign="middle">
                        <td class="cal_right" nowrap="nowrap">{h:lan_from}:</td>
                        <td></td>
                        <td class="cal_right" nowrap="nowrap">{h:ddl_from}</td>
                    </tr>
                    <tr valign="middle">
                        <td class="cal_right" nowrap="nowrap">{h:lan_to}:</td>
                        <td></td>
                        <td class="cal_right" nowrap="nowrap">{h:ddl_to}</td>
                    </tr>                
                    </table>
                </div>
                <div id="ea_repeatedly" style="display:none;padding:4px;">
                    <table border=0>
                    <tr valign="middle">
                        <td class="cal_right" nowrap="nowrap">{h:lan_repeats}:</td>
                        <td></td>
                        <td nowrap="nowrap">{h:ddl_repeat_type}</td>
                    </tr>
                    <tr valign="middle">
                        <td class="cal_right" nowrap="nowrap">{h:lan_repeat_every}:</td>
                        <td></td>
                        <td nowrap="nowrap">{h:ddl_repeat_every}
                            <span id="event_repeat_every_weeks">{h:lan_weeks}</span>
                            <span id="event_repeat_every_months" style="display:none;">{h:lan_months}</span>
                        </td>
                    </tr>                    
                    <tr valign="middle">
                        <td class="cal_right" nowrap="nowrap">{h:lan_from}:</td>
                        <td></td>
                        <td nowrap="nowrap">{h:ddl_from_date}</td>
                    </tr>
                    <tr valign="middle">
                        <td class="cal_right" nowrap="nowrap">{h:lan_to}:</td>
                        <td></td>
                        <td nowrap="nowrap">{h:ddl_to_date}</td>
                    </tr>                
                    <tr valign="middle">
                        <td class="cal_right" nowrap="nowrap">{h:lan_hours}:</td>
                        <td></td>
                        <td nowrap="nowrap">{h:ddl_from_time} - {h:ddl_to_time}</td>
                    </tr>
                    <tr><td colspan="3" nowrap height="5px"></td></tr>
                    <tr valign="middle">
                        <td class="cal_right" nowrap="nowrap">{h:lan_repeat_on}:</td>
                        <td></td>
                        <td>
                            <div id="repeat_on_weekly">
                                <input type="checkbox" name="repeat_sun" id="repeat_sun" /><label for="repeat_sun">{h:lan_sun}</label>
                                <input type="checkbox" name="repeat_mon" id="repeat_mon" /><label for="repeat_mon">{h:lan_mon}</label>
                                <input type="checkbox" name="repeat_tue" id="repeat_tue" /><label for="repeat_tue">{h:lan_tue}</label>
                                <input type="checkbox" name="repeat_wed" id="repeat_wed" /><label for="repeat_wed">{h:lan_wed}</label>
                                <input type="checkbox" name="repeat_thu" id="repeat_thu" /><label for="repeat_thu">{h:lan_thu}</label>
                                <input type="checkbox" name="repeat_fri" id="repeat_fri" /><label for="repeat_fri">{h:lan_fri}</label>
                                <input type="checkbox" name="repeat_sat" id="repeat_sat" /><label for="repeat_sat">{h:lan_sat}</label>
                            </div>
                            <div id="repeat_on_monthly" style="display:none;">
                                {h:ddl_repeat_on_weekday_num}
                                {h:ddl_repeat_on_weekday}
                            </div>
                        </td>
                    </tr>                
                    </table>
                </div>
                </fieldset>
                </div>
            </td>
        </tr>
        </table>
    </td>
</tr>  
<tr><td align="center" colspan="3" style="height:20px;padding:0px;"></td></tr>
<tr>
    <td align="center" colspan="3">
        <input class="form_button" type="button" name="btnSubmit" value="{h:lan_add_event}" onclick="javascript:phpCalendar.eventsAdd();" />
        &nbsp;- {h:lan_or} -&nbsp;
        <a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:phpCalendar.eventsCancel();">{h:lan_cancel}</a>
    </td>
</tr>
</table>
</fieldset>