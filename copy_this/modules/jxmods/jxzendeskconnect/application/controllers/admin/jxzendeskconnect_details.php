<?php
/**
 *    This file is part of the module jxZendeskConnect for OXID eShop Community Edition.
 *
 *    The module jxZendeskConnect for OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    The module jxZendeskConnect for OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      https://github.com/job963/jxZendeskConnect
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) 2016 Joachim Barthel
 * @author    Joachim Barthel <jobarthel@gmail.com>
 *
 */

class jxzendeskconnect_details extends oxAdminDetails {

    protected $_sThisTemplate = "jxzendeskconnect_details.tpl";

    /**
     * Displays the latest log entries of selected object
     */
    public function render() 
    {
        parent::render();

        $myConfig = oxRegistry::getConfig();
        
        if ($myConfig->getBaseShopId() == 'oxbaseshop') {
            // CE or PE shop
            $sWhereShopId = "";
        } else {
            // EE shop
            $sWhereShopId = "AND l.oxshopid = {$myConfig->getBaseShopId()} ";
        }
        $blAdminLog = $myConfig->getConfigParam('blLogChangesInAdmin');

        //$sObjectId = $this->getEditObjectId();
        //echo "sObjectId=".$sObjectId;
        
        $this->_jxZendeskSearchIssues();
		

        return $this->_sThisTemplate;
    }
    
    
    public function jxZendeskConnectCreateIssue() 
    {
        $sIssueSummary = $this->getConfig()->getRequestParameter( 'jxzendesk_summary' );
        $sIssueDescription = $this->getConfig()->getRequestParameter( 'jxzendesk_description' );
        //echo $sIssueSummary;
        $sToken = $this->_jxZendeskCreateIssue();
    }
    
    
    /*
     * 
     */
    private function _jxZendeskSearchIssues() 
    {
        $myConfig = oxRegistry::getConfig();
        
        $sUrl = $myConfig->getConfigParam('sJxZendeskConnectServerUrl') . '/rest/api/2/search';
        $sProject = $myConfig->getConfigParam('sJxZendeskConnectProject');
        $sUsername = $myConfig->getConfigParam('sJxZendeskConnectUser');
        $sPassword = $myConfig->getConfigParam('sJxZendeskConnectPassword');
        //$sFieldCustomerNumber = 'cf[' . $myConfig->getConfigParam('sJxZendeskConnectCustomerNumber') . ']';
        //$sFieldCustomerEMail = 'cf[' . $myConfig->getConfigParam('sJxZendeskConnectCustomerEMail') . ']';

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oOrder = oxNew("oxorder");
            if ($oOrder->load($soxId)) {
                $oUser = $oOrder->getOrderUser();
                $sUserMail = $oUser->oxuser__oxusername->value;
                //$sCustomerEMail = $oOrder->oxorder__oxbillemail->value;
            } else {
                $oUser = oxNew("oxuser");
                if ($oUser->load($soxId)) {
                    $sUserMail = $oUser->oxuser__oxusername->value;
                }
            }
        }
/*        $aData = array(
                    'jql' => $sFieldCustomerNumber . ' ~ ' . $sCustomerNumber,
                    'maxResults' => '25'
            );        
/*
        $ch = curl_init();
  
        $aHeaders = array(
            'Accept: application/json',
            'Content-Type: application/json'
            );        
*/
        /*echo '<pre>';
        print_r(json_decode(json_encode($aData),JSON_PRETTY_PRINT));
        echo '</pre>';/**/
  
/*        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeaders);
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($aData));
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_USERPWD, "$sUsername:$sPassword");

        $result = curl_exec($ch);
        $ch_error = curl_error($ch);

        if ($ch_error) {
            echo "cURL Error: $ch_error";
        } else {
            $aResult = json_decode($result,true);
            $iIssueCount = $aResult['total'];
            $aIssues = $aResult['issues'];
*/
            /*echo '<pre>';
            print_r($aIssues);
            echo '</pre>';/**/
/*            
            $this->_aViewData["sIssueUrl"] = $myConfig->getConfigParam('sJxZendeskConnectServerUrl') . '/browse/';
            $this->_aViewData["iIssueCount"] = $iIssueCount;
            $this->_aViewData["aIssues"] = $aIssues;
        }

        curl_close($ch);
*/        
        
        $sQueryParam = urlencode( 'type:ticket status<solved "' . $sUserMail . '"' );
        //--echo '<pre>'.$sUrl.'</pre>';

        //$aResult = $this->_curlWrap('/search.json?query='.urlencode('type:ticket status<solved "jobarthel@gmail.com"'), null, 'GET');
        //echo '/search.json?query='.$sQueryParam;
        $aResult = $this->_curlWrap( '/search.json?query='.$sQueryParam, null, 'GET' );
        
        /*echo '<pre>';
        print_r($aResult);
        echo '</pre>';/**/
        $iIssueCount = $aResult['count'];
        /*echo '<pre>';
        print_r($aIssues);
        echo '</pre>';/**/
        
        $aUser = $this->_jxZendeskSearchUser();
        
        $this->_aViewData["sUserID"] = $aUser['id'];
        $this->_aViewData["iIssueCount"] = $iIssueCount;
        $this->_aViewData["aIssues"] = $aResult['results'];

    }
    
    
    /*
     * 
     */
    private function _jxZendeskSearchUser() 
    {
        $myConfig = oxRegistry::getConfig();
        
        $sUrl = $myConfig->getConfigParam('sJxZendeskConnectServerUrl') . '/rest/api/2/search';
        $sUsername = $myConfig->getConfigParam('sJxZendeskConnectUser');
        $sPassword = $myConfig->getConfigParam('sJxZendeskConnectPassword');

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oOrder = oxNew("oxorder");
            if ($oOrder->load($soxId)) {
                $oUser = $oOrder->getOrderUser();
                $sUserMail = $oUser->oxuser__oxusername->value;
                //$sCustomerEMail = $oOrder->oxorder__oxbillemail->value;
            } else {
                $oUser = oxNew("oxuser");
                if ($oUser->load($soxId)) {
                    $sUserMail = $oUser->oxuser__oxusername->value;
                }
            }
        }
/*        $aData = array(
                    'jql' => $sFieldCustomerNumber . ' ~ ' . $sCustomerNumber,
                    'maxResults' => '25'
            );        
/*
        $ch = curl_init();
  
        $aHeaders = array(
            'Accept: application/json',
            'Content-Type: application/json'
            );        
*/
        /*echo '<pre>';
        print_r(json_decode(json_encode($aData),JSON_PRETTY_PRINT));
        echo '</pre>';/**/
  
/*        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeaders);
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($aData));
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_USERPWD, "$sUsername:$sPassword");

        $result = curl_exec($ch);
        $ch_error = curl_error($ch);

        if ($ch_error) {
            echo "cURL Error: $ch_error";
        } else {
            $aResult = json_decode($result,true);
            $iIssueCount = $aResult['total'];
            $aIssues = $aResult['issues'];
*/
            /*echo '<pre>';
            print_r($aIssues);
            echo '</pre>';/**/
/*            
            $this->_aViewData["sIssueUrl"] = $myConfig->getConfigParam('sJxZendeskConnectServerUrl') . '/browse/';
            $this->_aViewData["iIssueCount"] = $iIssueCount;
            $this->_aViewData["aIssues"] = $aIssues;
        }

        curl_close($ch);
*/        
        
        $sQueryParam = urlencode( 'type:user "' . $sUserMail . '"' );
        //--echo '<pre>'.$sUrl.'</pre>';

        //$aResult = $this->_curlWrap('/search.json?query='.urlencode('type:ticket status<solved "jobarthel@gmail.com"'), null, 'GET');
        //echo '/search.json?query='.$sQueryParam;
        $aResult = $this->_curlWrap( '/search.json?query='.$sQueryParam, null, 'GET' );
        
        /*echo '<pre>';
        print_r($aResult);
        echo '</pre>';/**/
        
        $iIssueCount = $aResult['count'];
        
        //$this->_aViewData["sUserMail"] = $sUserMail;
        //$this->_aViewData["iIssueCount"] = $iIssueCount;
        //$this->_aViewData["aIssues"] = $aResult['results'];
        
        if ($aResult['count'] == 1) {
            return $aResult['results']['0'];
        } else {
            return null;
        }

    }
    
    
    /*
     * 
     */
    private function _jxZendeskCreateIssue() 
    {
        $myConfig = oxRegistry::getConfig();
        
        $sUrl = $myConfig->getConfigParam('sJxZendeskConnectServerUrl') . '/rest/api/2/issue/';
        //--$sProject = $myConfig->getConfigParam('sJxZendeskConnectProject');
        $sAssignee = $myConfig->getConfigParam('sJxZendeskConnectAssignee');
        $sUsername = $myConfig->getConfigParam('sJxZendeskConnectUser');
        $sPassword = $myConfig->getConfigParam('sJxZendeskConnectPassword');
        $sFieldCustomerNumber = 'customfield_' . $myConfig->getConfigParam('sJxZendeskConnectCustomerNumber');
        $sFieldCustomerEMail = 'customfield_' . $myConfig->getConfigParam('sJxZendeskConnectCustomerEMail');

        $sTicketMode = $this->getConfig()->getRequestParameter( 'jxzendesk_ticketmode' );
        $sTicketSubject = $this->getConfig()->getRequestParameter( 'jxzendesk_summary' );
        $sTicketDescription = $this->getConfig()->getRequestParameter( 'jxzendesk_description' );
        $sIssueType = $this->getConfig()->getRequestParameter( 'jxzendesk_issuetype' );
        $sPriority = $this->getConfig()->getRequestParameter( 'jxzendesk_priority' );
        $sDueDate = $this->getConfig()->getRequestParameter( 'jxzendesk_duedate' );

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oOrder = oxNew("oxorder");
            if ($oOrder->load($soxId)) {
                $oUser = $oOrder->getOrderUser();
                $sCustomerNumber = $oUser->oxuser__oxcustnr->value;
                $sUserName = $oUser->oxuser__oxfname->value . ' ' . $oUser->oxuser__oxlname->value;
                $sUserMail = $oUser->oxuser__oxusername->value;
            } else {
                $oUser = oxNew("oxuser");
                if ($oUser->load($soxId)) {
                    $sCustomerNumber = $oUser->oxuser__oxcustnr->value;
                    $sUserName = $oUser->oxuser__oxfname->value . ' ' . $oUser->oxuser__oxlname->value;
                    $sUserMail = $oUser->oxuser__oxusername->value;
                }
            }
        }
        
/*        $aData = array(
                    'fields' => array(
                        'project' => array(
                            'key' => $sProject,
                            ),
                        'summary' => $sIssueSummary,
                        'description' => $sIssueDescription,
                        $sFieldCustomerNumber => $sCustomerNumber,
                        $sFieldCustomerEMail => $sCustomerEMail,
                        'issuetype' => array(
                            /*"self" => "xxxx",
                            "id" => "xxxx",
                            "description" => "xxxxx",
                            "iconUrl" => "xxxxx",*/
                            /*'name' => $sIssueType,
                            'subtask' => false
                            ),
                        'assignee' => array(
                            'name' => $sAssignee
                            ),
                        'priority' => array(name => $sPriority)
                        ),
            );        
        if (!empty($sDueDate)) {
            $aData['fields']['duedate'] = $sDueDate;
        }*/

	/*echo '<pre>';
	print_r(json_encode(json_decode(json_encode($aData)),JSON_PRETTY_PRINT));
	echo '</pre>';*/
        
        /*$ch = curl_init();
  
        $aHeaders = array(
            'Accept: application/json',
            'Content-Type: application/json'
            );        

        $test = "This is the content of the custom field.";
  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeaders);
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($aData));
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_USERPWD, "$sUsername:$sPassword");

        $result = curl_exec($ch);
        $ch_error = curl_error($ch);

        if ($ch_error) {
            echo "cURL Error: $ch_error";
        } else {
            //echo $result;
        }

        curl_close($ch);*/

        if ($sTicketMode == 'internal') {
            $aPostData = array(
                            'ticket' => array(
                                            'requester' => array(
                                                            'name' => 'Kathrin Barthel',
                                                            'email' => 'support-admin@jaspona.de'
                                                            ),
                                            'subject' => $sTicketSubject,
                                            'description' => $sTicketDescription,
                                            "custom_fields" => array(
                                                                "id" => 25857169, 
                                                                "value" => $sUserName . ' (' . $sUserMail . ')'
                                                                )
                                            )
                            );

        } else {
            $aPostData = array(
                            'ticket' => array(
                                            'requester' => array(
                                                            'name' => $sUserName,
                                                            'email' => $sUserMail
                                                            ),
                                            'subject' => $sTicketSubject,
                                            'description' => $sTicketDescription
                                            )
                            );
        }
        
        
        $sPostData = json_encode( $aPostData, JSON_FORCE_OBJECT );
        //-------$sQueryParam = urlencode( 'type:ticket status<solved "' . $sUserMail . '"' );
        //--echo '<pre>'.$sUrl.'</pre>';

        //$aResult = $this->_curlWrap('/search.json?query='.urlencode('type:ticket status<solved "jobarthel@gmail.com"'), null, 'GET');
        //echo '/search.json?query='.$sQueryParam;
        $aResult = $this->_curlWrap( '/tickets.json', $sPostData, 'POST' );
        /*echo '<pre>';
        print_r($aResult);
        echo '</pre>';/**/

        
    }
    
    
    private function _curlWrap($url, $json, $action)
    {
        $myConfig = oxRegistry::getConfig();
        $sUrl = $myConfig->getConfigParam('sJxZendeskConnectServerUrl') . '/api/v2';
        $sUsername = $myConfig->getConfigParam('sJxZendeskConnectUser');
        $sPassword = $myConfig->getConfigParam('sJxZendeskConnectPassword');
        $sToken = $myConfig->getConfigParam('sJxZendeskConnectToken');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_URL, $sUrl.$url);
        //--echo  $sUrl.$url;
        curl_setopt($ch, CURLOPT_USERPWD, $sUsername."/token:".$sToken);
        switch($action){
            case "POST":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                break;
            case "GET":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                break;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        /*print_r($ch);*/
        $output = curl_exec($ch);
        if ($ch_error) {
            echo "cURL Error: $ch_error";
        }
        /*var_dump($output);*/
        curl_close($ch);
        $decoded = json_decode($output, true);
        /*echo '<pre>';
        print_r($decoded);
        echo '</pre>';*/
        return $decoded;
    }    
    
    
}
