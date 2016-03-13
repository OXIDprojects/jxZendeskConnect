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
    
    
    private function _jxZendeskSearchIssues() 
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
    
}
