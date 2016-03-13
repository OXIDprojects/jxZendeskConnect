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


<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="jx_voucherserie_show">
</form>


    [{*$iIssueCount*}]
    <div id="liste" style="border:0px solid gray; padding:4px; width:99%; height:45%; overflow-y:scroll;">
            <table cellspacing="0" cellpadding="0" border="0" width="99%">
                <tr>
                    <td class="listheader">&nbsp;[{ oxmultilang ident="JXZENDESK_STATUSICON" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_KEY" }]</td>
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
                        <td class="[{ $listclass }]" style="height: 20px;">[{if $sUserID == $aIssue.requester_id}][{ oxmultilang ident="JXZENDESK_TICKETMODE_CUSTOMER" }][{else}]<span style="color:darkgoldenrod;">[{ oxmultilang ident="JXZENDESK_TICKETMODE_INTERNAL" }]</span>[{/if}]</td>
                        <td class="[{ $listclass }]"><a href="[{$sIssueUrl}][{$aIssue.key}]" target="_blank">[{$aIssue.via.channel}]</a></td>
                        <td class="[{ $listclass }]" title="[{$aIssue.fields.description}]"><a href="[{$sIssueUrl}][{$aIssue.key}]" title="[{$aIssue.fields.description}]" target="_blank">[{$aIssue.subject}]</a></td>
                        <td class="[{ $listclass }]">[{if $aIssue.priority != ""}][{ oxmultilang ident="JXZENDESK_PRIORITY_"|cat:$aIssue.priority|upper }][{/if}]</td>
                        <td class="[{ $listclass }]"><span class="jira-status-[{$aIssue.fields.status.statusCategory.colorName}]">&nbsp;[{ oxmultilang ident="JXZENDESK_STATUS_"|cat:$aIssue.status|upper }]&nbsp;</span></td>
                        <td class="[{ $listclass }]">[{$aIssue.created_at|substr:0:10}]</td>
                        <td class="[{ $listclass }]">[{$aIssue.submitter_id}]</td>
                        <td class="[{ $listclass }]">[{$aIssue.due_at|substr:0:10}]</td>
                    </tr>
                [{/foreach}]
            </table>
    </div>
            
    <div style="height:20px;">&nbsp;</div>
    
    <div>
        <form name="jxzendeskconnect_createissue" id="jxzendeskconnect_details" action="[{ $oViewConf->getSelfLink() }]" method="post">
            [{ $oViewConf->getHiddenSid() }]
            <input type="hidden" name="oxid" value="[{ $oxid }]">
            <input type="hidden" name="cl" value="jxzendeskconnect_details">
            <input type="hidden" name="fnc" value="jxZendeskConnectCreateIssue">
            <table>
                <tr>
                    <td valign="top">[{ oxmultilang ident="JXZENDESK_TICKETMODE_TITLE" }]</td>
                    <td>
                        <input type="radio" name="jxzendesk_ticketmode" id="mode-internal" value="internal"><label for="mode-internal"> <span style="color:darkgoldenrod;">[{ oxmultilang ident="JXZENDESK_TICKETMODE_INTERNAL" }]</span></label>
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="jxzendesk_ticketmode" id="mode-customer" value="customer"><label for="mode-customer"> [{ oxmultilang ident="JXZENDESK_TICKETMODE_CUSTOMER" }]</label>
                    </td>
                </tr>
                <tr>
                    <td>[{ oxmultilang ident="JXZENDESK_SUMMARY" }]</td><td>
                        <input type="text" name="jxzendesk_summary" size="50" />
                        <select name="jxzendesk_issuetype" width="20">
                            <option value="question">[{ oxmultilang ident="JXZENDESK_TICKETTYPE_QUESTION" }]</option>
                            <option value="incident">[{ oxmultilang ident="JXZENDESK_TICKETTYPE_INCIDENT" }]</option>
                            <option value="problem">[{ oxmultilang ident="JXZENDESK_TICKETTYPE_PROBLEM" }]</option>
                            <option value="task">[{ oxmultilang ident="JXZENDESK_TICKETTYPE_TASK" }]</option>
                        </select>
                        <select name="jxzendesk_priority" width="20">
                            <option value="low">[{ oxmultilang ident="JXZENDESK_PRIORITY_LOW" }]</option>
                            <option value="normal">[{ oxmultilang ident="JXZENDESK_PRIORITY_NORMAL" }]</option>
                            <option value="high" selected>[{ oxmultilang ident="JXZENDESK_PRIORITY_HIGH" }]</option>
                            <option value="urgent">[{ oxmultilang ident="JXZENDESK_PRIORITY_URGENT" }]</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td valign="top">[{ oxmultilang ident="JXZENDESK_DESCRIPTION" }]</td><td><textarea cols="80" rows="3" name="jxzendesk_description"></textarea></td>
                </tr>
                <tr>
                    <td>[{ oxmultilang ident="JXZENDESK_DUEDATE" }]</td><td><input type="text" name="jxzendesk_duedate" size="10" /></td>
                </tr>
                </tr>
                <tr>
                    <td></td><td><input type="submit" /></td>
                </tr>
            </table>
        </form>
    </div>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]

