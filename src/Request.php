<?php
namespace brickheadz\NoMoreBounce;

use brickheadz\Exception\ResponseException;

class Request
{
    ############################################################################
    #   GET
    ############################################################################

    /**
     * Wrapper used to prepare and launch a get request:
     * - Format GET url (@see static::prepareGetRequest)
     * - Launch GET request (@see static::curl_file_get_content)
     *
     * @param string $apiUrl    Base api url @see Url
     * @param string $endpoint  API endpoint @see Endpoint
     * @param int $connectorId  Connector id @see Credentials
     * @param string $token     Request token @see Credentials
     * @param array $params     List of params (can be empty)
     * @return string           Response
     * @throws ResponseException
     */
    public static function prepareAndLaunchGetRequest(string $apiUrl, string $endpoint, int $connectorId, string $token, array $params = [])
    {
        $url = static::prepareGetRequest($apiUrl, $endpoint, $connectorId, $token, $params);
        return static::curl_file_get_content($url);
    }

    /**
     * Format provided data as url to make a GET request
     *
     * @param string $apiUrl    Base api url @see Url
     * @param string $endpoint  API endpoint @see Endpoint
     * @param int $connectorId  Connector id @see Credentials
     * @param string $token     Request token @see Credentials
     * @param array $params     List of params (can be empty)
     * @return string           Formatted url
     */
    protected static function prepareGetRequest(string $apiUrl, string $endpoint, int $connectorId, string $token, array $params = [])
    {
        // Format base url
        $requestUrl = "{$apiUrl}{$endpoint}{$connectorId}/?token={$token}";

        if (!empty($params)) {
            // Add provided params to request
            foreach ($params as $key => $value) {
                $requestUrl .= "&{$key}={$value}";
            }
        }

        return $requestUrl;
    }

    /**
     * Build a curl with the provided $url and return response
     *
     * @param string $url
     * @return string       Encoded response    
     * @throws ResponseException
     */
    protected static function curl_file_get_content(string $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $contents = curl_exec($ch);
        curl_close($ch);

        if (!$contents) {
            throw new ResponseException('No response from NoMoreBounce', 0, $url, FALSE);
        } else {
            return $contents;
        }
    }
    ############################################################################
    #   POST
    ############################################################################

    /**
     * Wrapper used to prepare and launch a get request:
     * - Format GET url (@see static::prepareGetRequest)
     * - Launch GET request (@see static::curl_file_get_content)
     * 
     * @param string $apiUrl    Base api url @see Url
     * @param string $endpoint  API endpoint @see Endpoint
     * @param int $connectorId  Connector id @see Credentials
     * @param string $token     Request token @see Credentials
     * @param array $params     List of params (can be empty)
     * @param array $opt        List of curl options (e.g. [CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']])
     * @return string           Response
     * @throws ResponseException
     */
    public static function prepareAndLaunchPostRequest(
    string $apiUrl, string $endpoint, int $connectorId, string $token, array $params = [], array $opt = [])
    {
        // Format base url
        $requestUrl = "{$apiUrl}{$endpoint}{$connectorId}/";

        $params['token'] = $token;
        // Url-ify the data for the POST
        $fields_string = http_build_query($params);

        return static::curl_file_post_content($requestUrl, $fields_string, $opt);
    }

    /**
     * Build a curl with the provided data and return response
     * 
     * @param string $url           Formatted url
     * @param string $fields_string Encoded http query with data to send
     * @param array $opt            List of curl_opt (for e.g.: [CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']])
     * @return string               Encoded response
     * @throws ResponseException
     */
    protected static function curl_file_post_content(string $url, string $fields_string, array $opt = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        if (!empty($fields_string)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        }
        // Eventually add options
        if (!empty($opt)) {
            foreach ($opt as $key => $value) {
                curl_setopt($ch, $key, $value);
            }
        }
        $contents = curl_exec($ch);
        curl_close($ch);

        $requestData = ['url' => $url, 'data' => $fields_string, 'opt' => $opt, 'response' => $contents];

        if (!$contents) {
            throw new ResponseException('No response from NoMoreBounce', 1, $requestData, FALSE);
        } else {
            return $contents;
        }
    }
}
