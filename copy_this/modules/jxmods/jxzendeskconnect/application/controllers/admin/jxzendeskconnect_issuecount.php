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

        $this->_jxZendeskSearchIssues();

        return $this->_sThisTemplate;
    }
    
    
    private function _jxZendeskSearchIssues() 
    {
        $myConfig = oxRegistry::getConfig();
        
        $sUserMail = $myConfig->getRequestParameter( 'jxusername' );
        
        $sQueryParam = urlencode( 'type:ticket status<solved "' . $sUserMail . '"' );

        $aResult = $this->_curlWrap('/search.json?query='.$sQueryParam, null, 'GET');
        
        $iIssueCount = $aResult['count'];

        $this->_aViewData["iIssueCount"] = $iIssueCount;

    }
    
    private function _curlWrap($url, $json, $action)
    {
        $myConfig = oxRegistry::getConfig();
        $sUrl = $myConfig->getConfigParam('sJxZendeskConnectServerUrl') . '/api/v2';
        $sUsername = $myConfig->getConfigParam('sJxZendeskConnectUser');
        //$sPassword = $myConfig->getConfigParam('sJxZendeskConnectPassword');
        $sToken = $myConfig->getConfigParam('sJxZendeskConnectToken');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_URL, $sUrl.$url);
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
        $output = curl_exec($ch);
        if ($ch_error) {
            echo "cURL Error: $ch_error";
        }
        curl_close($ch);
        $decoded = json_decode($output, true);
        return $decoded;
    }    
}
