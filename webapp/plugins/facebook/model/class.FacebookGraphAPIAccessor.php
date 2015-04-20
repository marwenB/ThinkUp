<?php
/**
 *
 * ThinkUp/webapp/plugins/facebook/model/class.FacebookGraphAPIAccessor.php
 *
 * LICENSE:
 *
 * This file is part of ThinkUp (http://thinkup.com).
 *
 * ThinkUp is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any
 * later version.
 *
 * ThinkUp is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with ThinkUp.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 *
 * Facebook Graph API Accessor
 *
 * Makes HTTP requests to the Facebook Graph API given a user access token.
 *
 * Copyright (c) 2009-2015 Gina Trapani
 *
 * @author Gina Trapani <ginatrapani[at]gmail[dot]com>
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2009-2015 Gina Trapani
 */
class FacebookGraphAPIAccessor {
    /**
     * Make a Graph API request.
     * @param str $path
     * @param str $access_token
     * @param array $params HTTP parameters to include on URL
     * @param str $fields Comma-delimited list of fields to return from FB API
     * @return array Decoded JSON response
     */
    public static function apiRequest($path, $access_token=null, $params=null, $fields=null) {
        //$api_domain = 'https://graph.facebook.com/v2.3/';
        $api_domain = 'https://graph.facebook.com/';

        //Set up URL parameters
        $api_call_params = $params;
        if (isset($access_token)) {
            //Add access_token
            $params['access_token'] = $access_token;
        }
        if (isset($fields)) {
            //Add fields
            $params['fields'] = $fields;
        }
        $api_call_params_str = http_build_query($params);

        $url = $api_domain.$path.'?'.$api_call_params_str;

        if (php_sapi_name() == "cli") {//Crawler being run at the command line
            $logger = Logger::getInstance();
            $logger->logInfo("Graph API call: ".$url, __METHOD__.','.__LINE__);
        }

        $result = Utils::getURLContents($url);
        try {
            return JSONDecoder::decode($result);
        } catch (JSONDecoderException $e) {
            return $result;
        }
    }
}
