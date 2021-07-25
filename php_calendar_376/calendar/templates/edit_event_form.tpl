<div id="divEditEvent">
    <input type="hidden" id="event_unique_key" name="event_unique_key" value="" />    
    <table id="divEditEvent_Header" width="100%">				
    <tr>
        <td>						
            <table class="header{h:class_move}">
            <tr>
                <td class="cal_left"><b>{h:lan_edit_event}</b></td>
                <td class="cal_right">[ <a id="divEditEvent_lnkClose" href="javascript:phpCalendar.hideEventForm('divEditEvent');">{h:lan_close}</a> ]</td>						
            </tr>
            </table>					
        </td>					
    </tr>
    </table>
    
    <table id="divEditEvent_Content" width="100%" border="0" class="cal_right">				
    <tr>
        <td class="cal_left" width="130px">{h:lan_event_name}: </td>
        <td class="cal_right"><input type="text" style="width:99%" id="event_name_edit" name="event_name_edit" maxlength="70" /></td>
        <td></td>
    </tr>
    <tr>
        <td class="cal_left" width="130px">{h:lan_event_url}: </td>
        <td class="cal_right"><input type="text" style="width:99%" id="event_url_edit" name="event_url_edit" maxlength="255" /></td>
        <td></td>
    </tr>
    <tr>
        <td class="cal_left" valign="top" wrap="wrap"><label for="event_description_edit">{h:lan_event_description}</label>:</td>
        <td class="cal_right"><textarea style="width:99%;height:50px;" id="event_description_edit" name="event_description_edit"></textarea></td>
        <td></td>
    </tr>
    <tr>
        <td class="cal_left">{h:lan_category_name}</td>
        <td class="cal_left">
            {h:ddl_category_name}
        </td>
        <td></td>
    </tr>
    <tr>
        <td class="cal_left">{h:lan_location_name}</td>
        <td class="cal_left"><label id="lbl_location_name"></label></td>
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
    <tr><td colspan="3" align="center" style="height:25px;padding:0px;"><div id="divEditEvent_msg"></div></td></tr>
    <tr>
        <td class="cal_left" style="padding-left:6px;" valign="bottom">{h:delete_button}</td>
        <td colspan="2" class="cal_right" style="padding-right:6px;" valign="bottom">{h:update_button}</td>
    </tr>
    <tr><td colspan="3" align="center" style="height:4px;"></td></tr>
    </table>
</div>