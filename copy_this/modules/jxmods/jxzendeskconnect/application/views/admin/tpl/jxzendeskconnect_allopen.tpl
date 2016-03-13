[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="none"}]

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


<div style="height:92%;" >

    [{*$iIssueCount*}]
    <div id="liste" style="border:0px solid gray; padding:4px; width:99%; height:95%; overflow-y:scroll;">
            <table cellspacing="0" cellpadding="0" border="0" width="99%">
                <tr>
                    <td class="listheader">&nbsp;[{ oxmultilang ident="JXZENDESK_TICKETMODE" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_TICKETTYPE" }]</td>
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
            
        <br />

    </div>

    [{*<div style="float:right;/*position:relative;bottom:-40px;*/padding-right:10px;">
    <br />
            <a href="https://github.com/job963/jxAdminLog" target="_blank"><span style="color:gray;">jxAdminLog</span></a>
    </div>*}]

</div>

[{*include file="bottomnaviitem.tpl"*}]
[{include file="bottomitem.tpl"}]

