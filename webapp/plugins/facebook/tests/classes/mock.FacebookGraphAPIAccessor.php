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
 * Mock Facebook Graph API Accessor
 *
 * Reads test data files instead of the actual Facebook servers for the purposes of running tests.
 *
 * Copyright (c) 2009-2015 Gina Trapani
 *
 * @author Gina Trapani <ginatrapani[at]gmail[dot]com>
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2009-2015 Gina Trapani
 */
class FacebookGraphAPIAccessor {
    /**
     * @param str $path
     * @param str $access_token
     * @param array $params HTTP parameters to include on URL
     * @param str $fields Comma-delimited list of fields to return from FB API
     * @return array Decoded JSON response
     */
    public static function apiRequest($path, $access_token=null, $params=null, $fields=null) {
        //Set up URL
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

        $url = $path.'?'.$api_call_params_str;

        $FAUX_DATA_PATH = THINKUP_WEBAPP_PATH.'plugins/facebook/tests/testdata/';
        //$url = str_replace('https://graph.facebook.com/', '', $url);
        $url = str_replace('/', '_', $url);
        $url = str_replace('&', '-', $url);
        $url = str_replace('?', '-', $url);
        return self::decodeFileContents($FAUX_DATA_PATH.$url);
    }

    private static function decodeFileContents($file_path, $decode_json=true) {
        $debug = (getenv('TEST_DEBUG')!==false) ? true : false;
        if ($debug) {
            echo "READING LOCAL TEST DATA FILE: ".$file_path. '
';
        }
        if (file_exists($file_path)) {
            $contents = file_get_contents($file_path);
            if ($decode_json) {
                try {
                    return JSONDecoder::decode($contents);
                } catch (JSONDecoderException $e) {
                    return $contents;
                }
            } else {
                return $contents;
            }
        } else {
            if ($debug) {
                echo $file_path." does not exist.
";
            }
            return '';
        }
    }
}
