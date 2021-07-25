<div id="divAddEvent">
    <table id="divAddEvent_Header" width="100%">				
    <tr>
        <td>						
            <table class="header{h:class_move}">
            <tr>
                <td class="cal_left"><b>{h:lan_add_new_event}</b></td>
                <td class="cal_right">[ <a id="divAddEvent_lnkClose" href="javascript:phpCalendar.hideEventForm('divAddEvent');">{h:lan_close}</a> ]</td>						
            </tr>
            </table>					
        </td>					
    </tr>
    </table>
    
    <table id="divAddEvent_Content" width="100%" border="0">
    <tr>
        <td class="cal_left"><label for="sel_event_new">{h:lan_event_name}</label>: <span class="cal_star">*</span></td>
        <td class="cal_right">
            <input type="radio" id="sel_event_new" name="sel_event" value="new" checked="checked" onclick="javascript:phpCalendar.eventSelectedDDL(1);" title="{h:lan_add_new_event}" />
            <input type="text" id="event_name" name="event_name" maxlength="70" /><br />
        </td>
        <td></td>
    </tr>
    <tr>
        <td class="cal_left"></td>
        <td class="cal_right">
            <input type="radio" id="sel_event_current" name="sel_event" value="current" onclick="javascript:phpCalendar.eventSelectedDDL(2);" title="{h:lan_select_existing_event}" />
            {h:ddl_event_name}{h:ddl_category_name}
        </td>
        <td></td>
    </tr>
    <tr>
        <td class="cal_left"></td>
        <td class="cal_right">
            {h:ddl_location_name}
        </td>
        <td></td>
    </tr>    
    <tr>
        <td class="cal_left" valign="top" width="95px" wrap="wrap"><label for="event_description">{h:lan_event_url}</label>:</td>
        <td class="cal_right"><input type="text" style="width:240px" id="event_url" name="event_url" maxlength="255" /></td>
        <td></td>
    </tr>
    <tr>
        <td class="cal_left" valign="top" width="95px" wrap="wrap"><label for="event_description">{h:lan_event_description}</label>:</td>
        <td class="cal_right"><textarea style="width:240px;height:50px;" id="event_description" name="event_description"></textarea></td>
        <td></td>
    </tr>
    <tr>
        <td class="cal_left">{h:lan_from}:</td>
        <td class="cal_right" nowrap="nowrap">{h:ddl_from}</td>
        <td></td>
    </tr>
    <tr>
        <td class="cal_left">{h:lan_to}:</td>
        <td class="cal_right" nowrap="nowrap">{h:ddl_to}</td>
        <td></td>
    </tr>
    <tr><td colspan="3" align="center" style="height:25px;padding:0px;"><div id="divAddEvent_msg"></div></td></tr>
    <tr><td colspan="3" class="cal_right" style="padding-right:6px;"><input class="form_button" type="button" name="btnSubmit" value="{h:lan_add_event}" onclick="javascript:phpCalendar.addEvent();"/></td></tr>
    <tr><td colspan="3" align="center" style="height:4px;"></td></tr>
    </table>
</div>