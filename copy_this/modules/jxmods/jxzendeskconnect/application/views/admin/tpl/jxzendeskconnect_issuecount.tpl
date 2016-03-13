[{include file="headitem.tpl" box="none }]

[{if $iIssueCount > 0 }]
    <div style="font-weight:bold;color:firebrick;background-color:#ffeeee;border:1px solid darkred;padding:2px;margin-bottom:6px;border-radius:3px;">
        &nbsp;[{$iIssueCount}] [{if $iIssueCount == 1 }][{ oxmultilang ident="JXZENDESK_OPENISSUE" }][{else}][{ oxmultilang ident="JXZENDESK_OPENISSUES" }][{/if}]&nbsp;[zd]
    </div>
[{/if}]