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
     * Displays the open tickets of all customers
     */
    public function render() 
    {
        parent::render();

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

        $sQueryParam = urlencode( 'type:ticket status<solved' );
        $aResult = $this->_curlWrap( '/search.json?query='.$sQueryParam.'&sort_by=updated_at&sort_order=asc', null, 'GET' );
        
        $iIssueCount = $aResult['count'];
        
        $aTickets = $this->_jxZendeskAddUserDetails( $aResult['results'] );
        $aTickets = $this->_jxZendeskAddTimeDetails( $aTickets );
        
        $aAgent = $this->_jxZendeskSearchAgent();
        
        $this->_aViewData["sServerUrl"] = $myConfig->getConfigParam('sJxZendeskConnectServerUrl');
        $this->_aViewData["sAgentID"] = $aAgent['id'];
        $this->_aViewData["iIssueCount"] = $iIssueCount;
        $this->_aViewData["aIssues"] = $aTickets;

    }
    
    
    /*
     * 
     */
    private function _jxZendeskSearchAgent() 
    {
        $myConfig = oxRegistry::getConfig();
        
        $sAgentEMail = $myConfig->getConfigParam('sJxZendeskConnectAgentEMail');
        
        $sQueryParam = urlencode( 'type:user "' . $sAgentEMail . '"' );

        $aResult = $this->_curlWrap( '/search.json?query='.$sQueryParam, null, 'GET' );
        
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

        $aUserIds = array();
        foreach ($aTickets as $key => $aTicket) {
            if (!in_array($aTicket['requester_id'], $aUserIds)) {
                $aUserIds[] = $aTicket['requester_id']; 
            }
        }
        
        $sQueryParam = implode( ',', $aUserIds );

        $aResult = $this->_curlWrap( '/users/show_many.json?ids='.$sQueryParam, null, 'GET' );
        
        $aUsers = $aResult['users'];
        
        foreach ($aTickets as $key => $aTicket) {
            foreach ($aUsers as $aUser) {
                if ($aTicket['requester_id'] == $aUser['id']) {
                    $aTickets[$key]['requester_name'] = $aUser[name];
                    $aTickets[$key]['requester_email'] = $aUser[email];
                }
            }
        }
        
        return $aTickets;
    }
    
    
    /*
     * 
     */
    private function _jxZendeskAddTimeDetails( $aTickets ) 
    {
        $myConfig = oxRegistry::getConfig();

        foreach ($aTickets as $key => $aTicket) {
            $tUpdated = strtotime( $aTicket['updated_at'] );
            $tNow = strtotime( date('Y-m-d H:i:s') );
            $tDiff = $tNow - $tUpdated;
            $aTickets[$key]['time_past'] = $this->_transformTimePeriod($tDiff);
        }
        
        return $aTickets;
    }


    /*
     * 
     */
    function _transformTimePeriod($timePeriod)
    {
        $timeString = DATE( "z,G,i,s", $timePeriod );
        $aTimes = explode( ',', $timeString );
        foreach ($aTimes as $key => $value) {
            $aTimes[$key] = (int)$value;
        }
        
        $myConfig = oxRegistry::getConfig();
        $sTimeFormat = $myConfig->getConfigParam('sJxZendeskConnectTimeLast');    
        
        $timeDiff = '';
        switch ($sTimeFormat) {
            case 'dhm':
                if ( $aTimes[0] != 0 )
                    $timeDiff = sprintf('%1$dd &nbsp%2$02dh &nbsp%3$02dm', $aTimes[0], $aTimes[1], $aTimes[2]);
                elseif ( ($aTimes[0] == 0) and ($aTimes[1] != 0) )
                    $timeDiff = sprintf('%2$dh &nbsp;%3$02dm', $aTimes[0], $aTimes[1], $aTimes[2]);
                else
                    $timeDiff = sprintf('%3$dm', $aTimes[0], $aTimes[1], $aTimes[2]);
                
                break;

            case 'dhhmm':
                if ( $aTimes[0] != 0 )
                    $timeDiff = sprintf('%1$dd &nbsp;%2$02d:%3$02d', $aTimes[0], $aTimes[1], $aTimes[2]);
                elseif ( ($aTimes[0] == 0) and ($aTimes[1] != 0) )
                    $timeDiff = sprintf('%2$d:%3$02d', $aTimes[0], $aTimes[1], $aTimes[2]);
                else
                    $timeDiff = sprintf('%2$2d:%3$02d', $aTimes[0], $aTimes[1], $aTimes[2]);
                break;

            case 'hmm':
                $timeDiff = sprintf('%1$d:%2$02d', ($aTimes[0]*24+$aTimes[1]), $aTimes[2]);
                break;

            default:
                break;
        }
        
        return $timeDiff;
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
