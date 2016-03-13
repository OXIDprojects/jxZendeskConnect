            
            [{assign var="user" value=$edit->getOrderUser() }]
            <iframe src="[{$oViewConf->getSelfLink()}]&cl=jxzendeskconnect_issuecount&jxusername=[{$user->oxuser__oxusername->value}]" width="100%" height="28" 
                    frameborder="0" scrolling="no" name="jxzendeskconnect_issuecount" align="left" style="margin-left:-20px; border: 1px none blue;">
            </iframe><br clear="all" />
                
[{$smarty.block.parent}]
