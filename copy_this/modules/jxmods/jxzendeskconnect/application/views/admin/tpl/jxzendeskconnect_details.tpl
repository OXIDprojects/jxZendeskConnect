[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign }]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

[{assign var="cssFilePath" value=$oViewConf->getModulePath('jxzendeskconnect','out/admin/src/jxzendeskconnect.css') }]
[{php}] 
    $sCssFilePath = $this->get_template_vars('cssFilePath');;
    $sCssTime = filemtime( $sCssFilePath );
    $this->assign('cssTime', $sCssTime);
[{/php}]
[{assign var="cssFileUrl" value=$oViewConf->getModuleUrl('jxzendeskconnect','out/admin/src/jxzendeskconnect.css') }]
[{assign var="cssFileUrl" value="$cssFileUrl?$cssTime" }]
<link href="[{$cssFileUrl}]" type="text/css" rel="stylesheet">
[{assign var="imgIconUrl" value=$oViewConf->getModuleUrl('jxzendeskconnect','out/admin/src/img') }]

<script type="text/javascript">
    function selectTicketType()
    {
        if (document.getElementById('tickettype').value == "task") {
            document.getElementById('duedatefield').disabled = false;
            document.getElementById('duedatelabel').className = "";
        }
        else {
            document.getElementById('duedatefield').disabled = true;   
            document.getElementById('duedatefield').value = "";
            document.getElementById('duedatelabel').className = "zendesk-disabled-text";
        }
    }
    
    function activateSubmit()
    {
        if ( (document.getElementById('summaryfield').value == "") || (document.getElementById('descriptionfield').value == "") ) {
            document.getElementById('submitbutton').disabled = true;
        }
        else {
            document.getElementById('submitbutton').disabled = false;
        }
    }
</script>

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="jx_zendeskconnect_details">
</form>

    [{if $iNewTicketId }]
        <div class="zendesk-msg-success">
            &nbsp;[{ oxmultilang ident="JXZENDESK_TICKET" }] [{ $iNewTicketId }] "[{ $sNewTicketSubject }]" [{ oxmultilang ident="JXZENDESK_TICKETCREATED" }]
        </div>
    [{/if}]
    
    [{*$iIssueCount*}]
    <div id="liste" style="border:0px solid gray; padding:4px; width:99%; height:45%; overflow-y:scroll;">
            <table cellspacing="0" cellpadding="0" border="0" width="99%">
                <tr>
                    <td class="listheader">&nbsp;[{ oxmultilang ident="JXZENDESK_TICKETMODE" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_TICKETTYPE" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_TIMEPAST" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_SUMMARY" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_PRIORITY" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_STATUS" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_CREATED" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_CREATOR" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_DUEDATE" }]</td>
                </tr>
                [{foreach item=aIssue from=$aIssues}]
                    [{ cycle values="listitem,listitem2" assign="listclass" }]
                    <tr>
                        <td class="[{ $listclass }]" style="height: 20px;">
                            [{if $sUserID == $aIssue.requester_id}]
                                <div class="zendesk-icon" style="background-color:darkgray;">[{ oxmultilang ident="JXZENDESK_TICKETMODE_SHORT_CUSTOMER" }]</div> [{ oxmultilang ident="JXZENDESK_TICKETMODE_CUSTOMER" }]
                            [{else}]
                                <div class="zendesk-icon" style="background-color:darkgoldenrod;">[{ oxmultilang ident="JXZENDESK_TICKETMODE_SHORT_INTERNAL" }]</div> [{ oxmultilang ident="JXZENDESK_TICKETMODE_INTERNAL" }]
                            [{/if}]
                        </td>
                        <td class="[{ $listclass }]">[{if $aIssue.type != ""}][{ oxmultilang ident="JXZENDESK_TICKETTYPE_"|cat:$aIssue.type|upper }][{/if}]</td>
                        <td class="[{ $listclass }]" align="right">[{ $aIssue.time_past }]&nbsp;&nbsp;&nbsp;</td>
                        <td class="[{ $listclass }]" title="[{$aIssue.description}]"><a href="[{$sServerUrl}]/agent/tickets/[{$aIssue.id}]" title="[{$aIssue.description}]" target="_blank">[{$aIssue.subject}]</a></td>
                        <td class="[{ $listclass }]">[{if $aIssue.priority != ""}][{ oxmultilang ident="JXZENDESK_PRIORITY_"|cat:$aIssue.priority|upper }][{/if}]</td>
                        <td class="[{ $listclass }]">
                            [{if $aIssue.status == "open"}]
                                <div class="zendesk-icon" style="background-color:crimson;">[{ oxmultilang ident="JXZENDESK_STATUS_SHORT_OPEN" }]</div>
                            [{elseif $aIssue.status == "pending"}]
                                <div class="zendesk-icon" style="background-color:cornflowerblue;">[{ oxmultilang ident="JXZENDESK_STATUS_SHORT_PENDING" }]</div>
                            [{elseif $aIssue.status == "solved"}]
                                <div class="zendesk-icon" style="background-color:darkgray;">[{ oxmultilang ident="JXZENDESK_STATUS_SHORT_SOLVED" }]</div>
                            [{/if}] [{ oxmultilang ident="JXZENDESK_STATUS_"|cat:$aIssue.status|upper }]
                        </td>
                        <td class="[{ $listclass }]">[{$aIssue.created_at|substr:0:10}]</td>
                        <td class="[{ $listclass }]">[{$aIssue.requester_name}]</td>
                        <td class="[{ $listclass }]">[{$aIssue.due_at|substr:0:10}]</td>
                    </tr>
                [{/foreach}]
            </table>
    </div>
            
    [{*<div style="height:20px;">&nbsp;</div>*}]
    <hr>
    
    <div>
        <form name="jxzendeskconnect_createissue" id="jxzendeskconnect_details" action="[{ $oViewConf->getSelfLink() }]" method="post">
            [{ $oViewConf->getHiddenSid() }]
            <input type="hidden" name="oxid" value="[{ $oxid }]">
            <input type="hidden" name="cl" value="jxzendeskconnect_details">
            <input type="hidden" name="fnc" value="jxZendeskConnectCreateIssue">
            <table>
                <tr>
                    <td valign="top">[{ oxmultilang ident="JXZENDESK_TICKETMODE" }]</td>
                    <td>
                        <input type="radio" name="jxzendesk_ticketmode" id="mode-internal" value="internal" checked="checked"><label for="mode-internal"> <span style="color:darkgoldenrod;">[{ oxmultilang ident="JXZENDESK_TICKETMODE_INTERNAL" }]</span></label>
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="jxzendesk_ticketmode" id="mode-customer" value="customer"><label for="mode-customer"> [{ oxmultilang ident="JXZENDESK_TICKETMODE_CUSTOMER" }]</label>
                    </td>
                </tr>
                <tr>
                    <td>[{ oxmultilang ident="JXZENDESK_SUMMARY" }]</td><td>
                        <input type="text" name="jxzendesk_summary" id="summaryfield" size="50" onkeyup="activateSubmit();" />
                        <select name="jxzendesk_issuetype" id="tickettype" width="20" onchange="selectTicketType();">
                            <option value="question" selected="selected">[{ oxmultilang ident="JXZENDESK_TICKETTYPE_QUESTION" }]</option>
                            <option value="incident">[{ oxmultilang ident="JXZENDESK_TICKETTYPE_INCIDENT" }]</option>
                            <option value="problem">[{ oxmultilang ident="JXZENDESK_TICKETTYPE_PROBLEM" }]</option>
                            <option value="task">[{ oxmultilang ident="JXZENDESK_TICKETTYPE_TASK" }]</option>
                        </select>
                        <select name="jxzendesk_priority" width="20">
                            <option value="low">[{ oxmultilang ident="JXZENDESK_PRIORITY_LOW" }]</option>
                            <option value="normal" selected="selected">[{ oxmultilang ident="JXZENDESK_PRIORITY_NORMAL" }]</option>
                            <option value="high">[{ oxmultilang ident="JXZENDESK_PRIORITY_HIGH" }]</option>
                            <option value="urgent">[{ oxmultilang ident="JXZENDESK_PRIORITY_URGENT" }]</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td valign="top">[{ oxmultilang ident="JXZENDESK_DESCRIPTION" }]</td>
                    <td><textarea cols="80" rows="3" name="jxzendesk_description" id="descriptionfield" onkeyup="activateSubmit();"></textarea></td>
                </tr>
                <tr>
                    <td><label id="duedatelabel" class="zendesk-disabled-text">[{ oxmultilang ident="JXZENDESK_DUEDATE" }]</label></td>
                    <td><input type="text" name="jxzendesk_duedate" id="duedatefield" size="10" disabled /></td>
                </tr>
                </tr>
                <tr>
                    <td></td><td><input type="submit" id="submitbutton" disabled /></td>
                </tr>
            </table>
        </form>
    </div>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]

