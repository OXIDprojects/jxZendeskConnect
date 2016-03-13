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
    'title'        => 'jxZendeskConnect - Connects OXID eShop with Zendesk',
    'description'  => array(
                        'de' => 'Anzeigen und Erstellen von Zendesk Tickets für Shop-Kunden',
                        'en' => 'Display and Creation of Zendesk Tickets for Shop Customers'
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
                        /*array(
                                'group' => 'JXZENDESKCONNECT_LOGIN', 
                                'name'  => 'sJxZendeskConnectPassword', 
                                'type'  => 'str', 
                                'value' => ''
                                ),*/
                        array(
                                'group' => 'JXZENDESKCONNECT_LOGIN', 
                                'name'  => 'sJxZendeskConnectToken', 
                                'type'  => 'str', 
                                'value' => ''
                                ),
                        /*array(
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
                                ),*/
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