<?php
namespace brickheadz\NoMoreBounce\Config;

use brickheadz\NoMoreBounce\Exception\CredentialException;

/**
 * Class to handle authentication settings
 */
class Credentials
{

    /** @var int    Connector ID */
    protected $CONNECTOR_ID;

    /** @var string Access token */
    protected $TOKEN;

    /**
     * Load authentication settings from json file stored into server
     * 
     * @param string $credential_path
     */
    public function __construct(string $credential_path)
    {
        $this->loadCredentials($credential_path);
    }

    /**
     * Return connector ID
     * 
     * @return int
     */
    public function getConnectorId()
    {
        return $this->CONNECTOR_ID;
    }

    /**
     * Return access token
     * 
     * @return string
     */
    public function getRequestToken()
    {
        return $this->TOKEN;
    }

    /**
     * Try to load (and set) authentication settings from file
     * 
     * @param string $credential_path
     * @throws CredentialException
     */
    protected function loadCredentials(string $credential_path)
    {
        if (!file_exists($credential_path)) {
            throw new CredentialException('Missing credential file');
        } elseif (($decoded = json_decode(file_get_contents($credential_path))) == FALSE) {
            throw new CredentialException("Can't decode credential file");
        } elseif (empty($decoded->token)) {
            throw new CredentialException("Missing 'token' value");
        } elseif (empty($decoded->connector_id)) {
            throw new CredentialException("Missing 'connector_id' value");
        } elseif (!is_int($decoded->connector_id)) {
            throw new CredentialException("Invalid 'connector_id' value");
        } else {
            $this->CONNECTOR_ID = $decoded->connector_id;
            $this->TOKEN = $decoded->token;
        }
    }
}
