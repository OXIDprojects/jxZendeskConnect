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

class jxzendeskconnect_allopen extends oxAdminDetails {

    protected $_sThisTemplate = "jxzendeskconnect_allopen.tpl";

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

        $sObjectId = $this->getEditObjectId();
        
        $this->_jxZendeskSearchIssues();
		

        return $this->_sThisTemplate;
    }
    
    
    
    /*
     * 
     */
    private function _jxZendeskSearchIssues() 
    {
        $myConfig = oxRegistry::getConfig();
        $sServerUrl = $myConfig->getConfigParam('sJxZendeskConnectServerUrl');

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
        
        $aTickets = $this->_jxZendeskAddUserDetails( $aResult['results'] );
        
        $aUser = $this->_jxZendeskSearchUser();
        
        $this->_aViewData["sServerUrl"] = $myConfig->getConfigParam('sJxZendeskConnectServerUrl');
        $this->_aViewData["sUserID"] = $aUser['id'];
        $this->_aViewData["iIssueCount"] = $iIssueCount;
        $this->_aViewData["aIssues"] = $aTickets;

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
        
        $sQueryParam = urlencode( 'type:user "' . $sUserMail . '"' );

        $aResult = $this->_curlWrap( '/search.json?query='.$sQueryParam, null, 'GET' );
        
        /*echo '<pre>';
        print_r($aResult);
        echo '</pre>';/**/
        
        $iIssueCount = $aResult['count'];
        
        if ($aResult['count'] == 1) {
            return $aResult['results']['0'];
        } else {
            return null;
        }

    }
    
    
    /*
     * 
     */
    private function _jxZendeskAddUserDetails( $aTickets ) 
    {
        $myConfig = oxRegistry::getConfig();
        
        $sUrl = $myConfig->getConfigParam('sJxZendeskConnectServerUrl') . '/rest/api/2/search';
        $sUsername = $myConfig->getConfigParam('sJxZendeskConnectUser');
        $sPassword = $myConfig->getConfigParam('sJxZendeskConnectPassword');

        /*echo '<pre>';
        print_r($aTickets);
        echo '</pre><hr>';/**/
        $aUserIds = array();
        foreach ($aTickets as $key => $aTicket) {
            //echo $aTicket['requester_id'].'<br>';
            if (!in_array($aTicket['requester_id'], $aUserIds)) {
                $aUserIds[] = $aTicket['requester_id']; 
            }
        }
        
        $sQueryParam = implode( ',', $aUserIds );
        //echo '<hr>/users/show_many.json?ids='.$sQueryParam.'<br>';

        $aResult = $this->_curlWrap( '/users/show_many.json?ids='.$sQueryParam, null, 'GET' );
        
        //$aResult = $this->_addUserDetails( $aResult );
        $aUsers = $aResult['users'];
        
        foreach ($aTickets as $key => $aTicket) {
            foreach ($aUsers as $aUser) {
                if ($aTicket['requester_id'] == $aUser['id']) {
                    $aTickets[$key]['requester_name'] = $aUser[name];
                    $aTickets[$key]['requester_email'] = $aUser[email];
                }
            }
        }
        
        /*echo '<pre>';
        print_r($aTickets);
        echo '</pre>';/**/
        
        return $aTickets;
    }
    
    
    private function _jxZendeskSearchIssues2() 
    {
        $myConfig = oxRegistry::getConfig();
        
        $sUrl = $myConfig->getConfigParam('sJxZendeskConnectServerUrl') . '/rest/api/2/search';
        $sProject = $myConfig->getConfigParam('sJxZendeskConnectProject');
        $sUsername = $myConfig->getConfigParam('sJxZendeskConnectUser');
        $sPassword = $myConfig->getConfigParam('sJxZendeskConnectPassword');
        $sFieldCustomerNumber = 'cf[' . $myConfig->getConfigParam('sJxZendeskConnectCustomerNumber') . ']';
        $sFieldCustomerEMail = 'cf[' . $myConfig->getConfigParam('sJxZendeskConnectCustomerEMail') . ']';

        $sCustomerNumber = oxRegistry::getConfig()->getRequestParameter( 'jxcustomerno' );

        $aData = array(
                    'jql' => 'project = ' . $sProject . ' AND status != Resolved AND status != Done',
                    'maxResults' => '100'
            );        

        $ch = curl_init();
  
        $aHeaders = array(
            'Accept: application/json',
            'Content-Type: application/json'
            );        

        /*echo '<pre>';
        print_r(json_decode(json_encode($aData),JSON_PRETTY_PRINT));
        echo '</pre>';/**/
  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeaders);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($aData));
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_USERPWD, "$sUsername:$sPassword" );

        $result = curl_exec($ch);
        $ch_error = curl_error($ch);

        if ($ch_error) {
            echo "cURL Error: $ch_error";
        } else {
            //echo $result;
            $aResult = json_decode($result,true);
            $iIssueCount = $aResult['total'];
            $aIssues = $aResult['issues'];
            /*echo '<pre>';
            print_r($aIssues);
            echo '</pre>';/**/
            
            $this->_aViewData["sIssueUrl"] = $myConfig->getConfigParam('sJxZendeskConnectServerUrl') . '/browse/';
            $this->_aViewData["iIssueCount"] = $iIssueCount;
            $this->_aViewData["aIssues"] = $aIssues;
        }

        curl_close($ch);
        
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
