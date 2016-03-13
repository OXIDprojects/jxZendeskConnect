<?php
/**
 * Metadata version
 */
$sMetadataVersion = '1.1';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'jxzendeskconnect',
    'title'        => 'jxZendeskConnect - Connect OXID eShop with Zendesk',
    'description'  => array(
                        'de' => 'Anzeige der protokollierten Admin Aktionen an jedem Objekt und als Gesamtbericht.<br /><br />'
                                . '(um das Logging zu aktivieren muss in der Datei config.inc.php die Einstellung<br />'
                                . '<code>$this->blLogChangesInAdmin = false;</code> auf <code>True</code> geändert werden)<br /><br />',
                        'en' => 'Display of Logged Administrative Actions for each Object and as full Report.<br /><br />'
                                . '(for enabling the logging you have to change the setting<br />'
                                . '<code>$this->blLogChangesInAdmin = false;</code> to <code>True</code>)<br /><br />'
                        ),
    'thumbnail'    => 'jxzendeskconnect.png',
    'version'      => '0.1',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxZendeskConnect',
    'email'        => 'jobarthel@gmail.com',
    'extend'       => array(
                        ),
    'files'        => array(
                        'jxzendeskconnect_allopen'     	=> 'jxmods/jxzendeskconnect/application/controllers/admin/jxzendeskconnect_allopen.php',
                        'jxzendeskconnect_issuecount'     	=> 'jxmods/jxzendeskconnect/application/controllers/admin/jxzendeskconnect_issuecount.php',
                        'jxzendeskconnect_details' 	=> 'jxmods/jxzendeskconnect/application/controllers/admin/jxzendeskconnect_details.php'/*,
                        'jxzendeskconnect_events' 	=> 'jxmods/jxzendeskconnect/core/jxzendeskconnect_events.php'*/
                        ),
    'templates'    => array(
                        'jxzendeskconnect_allopen.tpl'     => 'jxmods/jxzendeskconnect/application/views/admin/tpl/jxzendeskconnect_allopen.tpl',
                        'jxzendeskconnect_issuecount.tpl'  => 'jxmods/jxzendeskconnect/application/views/admin/tpl/jxzendeskconnect_issuecount.tpl',
                        'jxzendeskconnect_details.tpl'     => 'jxmods/jxzendeskconnect/application/views/admin/tpl/jxzendeskconnect_details.tpl'
                        ),
    'blocks'       => array(
                        array(
                            'template' => 'user_main.tpl', 
                            'block'    => 'admin_user_main_form',                     
                            'file'     => '/out/blocks/admin_user_main_form.tpl'
                          ),
                        array(
                            'template' => 'order_overview.tpl', 
                            'block'    => 'admin_order_overview_billingaddress',                     
                            'file'     => '/out/blocks/admin_order_overview_billingaddress.tpl'
                          ),
                        ),
    'events'       => array(/*
                        'onActivate'   => 'jxzendeskconnect_events::onActivate', 
                        'onDeactivate' => 'jxzendeskconnect_events::onDeactivate'
                        */),
    'settings' => array(
                        array(
                                'group' => 'JXZENDESKCONNECT_SERVER', 
                                'name'  => 'sJxZendeskConnectServerUrl', 
                                'type'  => 'str', 
                                'value' => ''
                                ),
                        array(
                                'group' => 'JXZENDESKCONNECT_LOGIN', 
                                'name'  => 'sJxZendeskConnectUser', 
                                'type'  => 'str', 
                                'value' => ''
                                ),
                        array(
                                'group' => 'JXZENDESKCONNECT_LOGIN', 
                                'name'  => 'sJxZendeskConnectPassword', 
                                'type'  => 'str', 
                                'value' => ''
                                ),
                        array(
                                'group' => 'JXZENDESKCONNECT_LOGIN', 
                                'name'  => 'sJxZendeskConnectToken', 
                                'type'  => 'str', 
                                'value' => ''
                                ),
                        array(
                                'group' => 'JXZENDESKCONNECT_DEFAULTS', 
                                'name'  => 'sJxZendeskConnectProject', 
                                'type'  => 'str', 
                                'value' => ''
                                ),
                        array(
                                'group' => 'JXZENDESKCONNECT_DEFAULTS', 
                                'name'  => 'sJxZendeskConnectAssignee', 
                                'type'  => 'str', 
                                'value' => ''
                                ),
                        array(
                                'group' => 'JXZENDESKCONNECT_FIELDS', 
                                'name'  => 'sJxZendeskConnectCustomerEMail', 
                                'type'  => 'str', 
                                'value' => ''
                                ),
                        array(
                                'group' => 'JXZENDESKCONNECT_FIELDS', 
                                'name'  => 'sJxZendeskConnectOrderNumber', 
                                'type'  => 'str', 
                                'value' => ''
                                )
                        )
);