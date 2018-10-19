<?php
namespace silvergit\NoMoreBounce;

use Exception\ResponseException;
use Config\EmailStatus;

/**
 * This class will include all methods to validate 
 * (and eventually extract some parts) different response 
 * from NoMoreBounce APIs
 */
class Response
{

    /**
     * Validate and parse check response
     * 
     * @param string $json
     * @return boolean
     * @throws ResponseException
     */
    public static function parseEmailCheckResponse($json)
    {
        $decoded = static::validateAndDecodeResponse($json);
        switch ($decoded->smtp_state) {
            case EmailStatus::EMAIL_VERIFY_EXIST:
                // There's a valid mailbox
                return TRUE;
            case EmailStatus::EMAIL_VERIFY_FAIL:
                // There isn't a valid mailbox
                return FALSE;
            default:
                // Unrecognized status, for security reason return FALSE
                return FALSE;
        }
    }

    /**
     * Validate and parse account/credits response
     * 
     * @param string $json
     * @return int
     * @throws ResponseException
     */
    public static function parseAvailableCreditsResponse($json)
    {
        $decoded = static::validateAndDecodeResponse($json);
        return $decoded->credits;
    }

    /**
     * Validate and parse importer/lists response 
     * 
     * @param string $json
     * @return mixed
     * @throws ResponseException
     */
    public static function parseGetAllListsResponse($json)
    {
        $decoded = static::validateAndDecodeResponse($json);
        return $decoded->lists;
    }

    /**
     * Validate and parse importer/emails-list response
     * 
     * @param string $json
     * @return mixed
     * @throws ResponseException
     */
    public static function parseAllEmailsIntoListRequest($json)
    {
        $decoded = static::validateAndDecodeResponse($json);
        return $decoded;
    }

    /**
     * Validate and parse importer/stats response
     * 
     * @param string $json
     * @return mixed
     * @throws ResponseException
     */
    public static function parseAListStatisticsRequest($json)
    {
        $decoded = static::validateAndDecodeResponse($json);
        return (empty($decoded->stats) ? [] : $decoded->stats);
    }
    
    /**
     * Validate and parse importer/add response
     * 
     * @param string $json
     * @return mixed
     * @throws ResponseException
     */
    public static function createListWithEmailsRequest($json){
        $decoded = static::validateAndDecodeResponse($json);        
        return $decoded;
    }

    /**
     * Decode and check response
     * 
     * @param string $json
     * @return mixed
     * @throws ResponseException
     */
    protected static function validateAndDecodeResponse($json)
    {
        $decoded = json_decode($json);
        if (!$decoded) {
            throw new ResponseException('Unable to decode response', 1, $json);
        } elseif ($decoded->status !== 200 && $decoded !== 400) {
            throw new ResponseException('Error during request: ' . $decoded->msg, 2, $json);
        } else {
            return $decoded;
        }
    }
}
