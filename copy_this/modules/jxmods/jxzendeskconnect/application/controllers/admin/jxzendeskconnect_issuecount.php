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

class jxzendeskconnect_issuecount extends oxAdminDetails {

    protected $_sThisTemplate = "jxzendeskconnect_issuecount.tpl";

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

        $this->_jxZendeskSearchIssues();

        return $this->_sThisTemplate;
    }
    
    
    private function _jxZendeskSearchIssues() 
    {
        $myConfig = oxRegistry::getConfig();
        
        $sUrl = $myConfig->getConfigParam('sJxZendeskConnectServerUrl') . '/api/v2/search.json?query=';
        $sProject = $myConfig->getConfigParam('sJxZendeskConnectProject');
        $sUsername = $myConfig->getConfigParam('sJxZendeskConnectUser');
        $sPassword = $myConfig->getConfigParam('sJxZendeskConnectPassword');
        $sToken = $myConfig->getConfigParam('sJxZendeskConnectToken');
        //$sFieldCustomerNumber = 'cf[' . $myConfig->getConfigParam('sJxZendeskConnectCustomerNumber') . ']';
        //$sFieldCustomerEMail = 'cf[' . $myConfig->getConfigParam('sJxZendeskConnectCustomerEMail') . ']';

        $sUserMail = $myConfig->getRequestParameter( 'jxusername' );
        //echo $sUserMail."#";

        /*$aData = array(
                    'jql' => $sFieldCustomerNumber . ' ~ ' . $sCustomerNumber . ' AND (status != Resolved AND status != Done)',
                    'maxResults' => '1'
            );*/
        
        /*$soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            echo "soxid=".$soxId;
            $oOrder = oxNew("oxorder");
            if ($oOrder->load($soxId)) {
                $oUser = $oOrder->getOrderUser();
                $sCustomerNumber = $oUser->oxuser__oxcustnr->value;
                $sCustomerEMail = $oOrder->oxorder__oxbillemail->value;
                echo 'u='.$sCustomerEMail;
            } else {
                $oUser = oxNew("oxuser");
                if ($oUser->load($soxId)) {
                    $sCustomerNumber = $oUser->oxuser__oxcustnr->value;
                    $sCustomerEMail = $oUser->oxuser__oxusername->value;
                    echo 'o='.$sCustomerEMail;
                }
            }
        }*/
        
        $sQueryParam = urlencode( 'type:ticket status<solved "' . $sUserMail . '"' );
        //--echo '<pre>'.$sUrl.'</pre>';

        //$aResult = $this->_curlWrap('/search.json?query='.urlencode('type:ticket status<solved "jobarthel@gmail.com"'), null, 'GET');
        //echo '/search.json?query='.$sQueryParam;
        $aResult = $this->_curlWrap('/search.json?query='.$sQueryParam, null, 'GET');
        
        /*echo '<pre>';
        print_r($aResult);
        echo '</pre>';*/
        $iIssueCount = $aResult['count'];

        $this->_aViewData["iIssueCount"] = $iIssueCount;

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
