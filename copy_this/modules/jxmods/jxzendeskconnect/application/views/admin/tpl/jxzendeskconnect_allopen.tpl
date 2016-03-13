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
                    <td class="listheader">&nbsp;[{ oxmultilang ident="JXZENDESK_STATUSICON" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_KEY" }]</td>
                    <td class="listheader">[{ oxmultilang ident="JXZENDESK_SUMMARY" }]</td>
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
                        <td class="[{ $listclass }]" style="height: 20px;">&nbsp;<img src="[{$aIssue.fields.issuetype.iconUrl}]" /></td>
                        <td class="[{ $listclass }]"><a href="[{$sIssueUrl}][{$aIssue.key}]" target="_blank">[{$aIssue.key}]</a></td>
                        <td class="[{ $listclass }]" title="[{$aIssue.fields.description}]"><a href="[{$sIssueUrl}][{$aIssue.key}]" title="[{$aIssue.fields.description}]" target="_blank">[{$aIssue.fields.summary}]</a></td>
                        <td class="[{ $listclass }]">[{$aIssue.fields.customfield_10206 }]</td>
                        <td class="[{ $listclass }]"><img src="[{$imgIconUrl}]/priority[{$aIssue.fields.priority.id}].png" /> [{ oxmultilang ident="JXZENDESK_PRIORITY_"|cat:$aIssue.fields.priority.id }]</td>
                        <td class="[{ $listclass }]"><span class="jira-status-[{$aIssue.fields.status.statusCategory.colorName}]">&nbsp;[{$aIssue.fields.status.name}]&nbsp;</span></td>
                        <td class="[{ $listclass }]">[{$aIssue.fields.created|substr:0:10}]</td>
                        <td class="[{ $listclass }]">[{$aIssue.fields.creator.displayName}]</td>
                        <td class="[{ $listclass }]">[{$aIssue.fields.duedate}]</td>
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

