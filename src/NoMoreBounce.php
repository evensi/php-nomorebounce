<?php
namespace brickheadz\NoMoreBounce;

use brickheadz\NoMoreBounce\Config\Credentials;
use brickheadz\NoMoreBounce\Config\Url;
use brickheadz\NoMoreBounce\Config\Endpoint;
use brickheadz\NoMoreBounce\Exception\ParamException;
use brickheadz\NoMoreBounce\Exception\ResponseException;
use brickheadz\NoMoreBounce\Exception\CredentialException;

class NoMoreBounce
{

    protected $credentials;

    /**
     * Constructor.
     *
     * @param string $credential_path
     * @throws CredentialException
     */
    public function __construct($credential_path)
    {
        $this->credentials = new Credentials($credential_path);
    }
    ############################################################################
    #   EMAIL
    ############################################################################

    /**
     * Check if a mail is secure or not.
     *
     * @param string $email     Email to check
     * @param bool $force_check If true erases the result (if checked in the past) and makes another check
     * @return bool             TRUE if secure, FALSE otherwise
     * @throws ParamException
     * @throws ResponseException
     */
    public function checkEmail(string $email, bool $force_check = FALSE)
    {
        if (empty(trim($email))) {
            throw new ParamException('Empty email provided');
        }

        $response = Request::executePostRequest(
            Request::preparePostRequest(
                Url::API_URL_V1,
                Endpoint::POST_EMAIL_CHECK,
                $this->credentials->getConnectorId(),
                $this->credentials->getRequestToken(),
                ['email' => $email, 'force_check' => $force_check]
            )
        );

        return Response::parseEmailCheckResponse($response);
    }
    ############################################################################
    #   ACCOUNT
    ############################################################################

    /**
     * @todo Check if the connect API is fixed
     * Return remaining credits (free credits and payed credits).
     *
     * @return int
     * @throws ResponseException
     */
    public function getAvailableCredits()
    {
        $response = Request::executeGetRequest(
            Request::prepareGetRequest(
                Url::API_URL_V1, Endpoint::GET_AVAILABLE_CREDITS,
                $this->credentials->getConnectorId(),
                $this->credentials->getRequestToken()
            )
        );

        return Response::parseAvailableCreditsResponse($response);
    }
    ############################################################################
    #   IMPORTER
    ############################################################################

    /**
     * Return all imported lists
     *
     * @return [mixed]   List of lists like:   [
     *                                              {
     *                                                "code": "General user list",
     *                                                "id": xxx,
     *                                                "last_modified": "10/19/18 09:15:01",
     *                                                "created": "10/19/18 09:15:01"
     *                                              }
     *                                            ]
     * @throws ResponseException
     */
    public function getAllLists()
    {
        $response = Request::executeGetRequest(
            Request::prepareGetRequest(
                Url::API_URL_V1, Endpoint::GET_LISTS,
                $this->credentials->getConnectorId(),
                $this->credentials->getRequestToken()
            )
        );

        return Response::parseGetAllListsResponse($response);
    }

    /**
     * Return all emails (and information) into specific list
     *
     * @param int $listId
     * @return [mixed]  List of emails like: {
     *                                          "status": 200,
     *                                          "count": 120,
     *                                          "num_pages": 10,
     *                                          "emails" : [{"email": xxx,
     *                                                    "msg": "Exists",
     *                                                    "last_check": "2018-03-03 10:00:00",
     *                                                    "created": "2018-03-03 10:00:00"}, ...]
     *                                        }
     * @throws ResponseException
     */
    public function getAllEmailsIntoList(int $listId)
    {
        if (!is_int($listId)) {
            throw new ParamException('Invalid list id provided');
        }

        $response = Request::executeGetRequest(
            Request::prepareGetRequest(
                Url::API_URL_V1,
                Endpoint::GET_EMAILS_INTO_LIST,
                $this->credentials->getConnectorId(),
                $this->credentials->getRequestToken(),
                ['list_id' => $listId]
            )
        );

        return Response::parseAllEmailsIntoListRequest($response);
    }

    /**
     * Return all statistics about specific list
     *
     * @param int $listId
     * @param int $page Page used to paginate results
     * @return [mixed]  List of statistics like: [
     *                                              {
     *                                                  "count": 29,
     *                                                  "msg": "Don't use it",
     *                                                  "result_type": 2
     *                                              },
     *                                              {
     *                                                  "count": 48,
     *                                                  "msg": "Mailbox is valid",
     *                                                  "result_type": 1
     *                                              },
     *                                          ...],
     * @throws ResponseException
     * @throws ParamException
     */
    public function getStatisticsAboutList(int $listId, int $page = 1)
    {
        if (!is_int($listId)) {
            throw new ParamException('Invalid list id provided');
        }
        if (!is_int($page)) {
            throw new ParamException('Invalid pagination value provided');
        }

        $response = Request::executeGetRequest(
            Request::prepareGetRequest(
                Url::API_URL_V1,
                Endpoint::GET_LIST_STAT,
                $this->credentials->getConnectorId(),
                $this->credentials->getRequestToken(),
                ['list_id' => $listId, 'page' => $page]
            )
        );

        return Response::parseAListStatisticsRequest($response);
    }

    /**
     * Create a new list that contains x emails
     *
     * @param int $listId       List where store emails
     * @param array $emails     List of emails MAX 1000
     * @return mixed            Results about creation like: {
     *                                                          "status": 200,
     *                                                          "list_id": xxx,
     *                                                          "imported": 200
     *                                                        }
     * @throws ResponseException
     * @throws ParamException
     */
    public function createListWithEmails(array $emails)
    {
        if (empty($emails)) {
            throw new ParamException('At least one email must be provided');
        }
        if (count($emails) > 1000) {
            throw new ParamException('Too many emails, max allowed is 1000');
        }

        $recipients = new \stdClass();
        $recipients->recipients = $emails;
        $params = ['recipients' => json_encode($recipients)];

        $response = Request::executePostRequest(
            Request::preparePostRequest(
                Url::API_URL_V1,
                Endpoint::POST_CREATE_LIST_WITH_EMAILS,
                $this->credentials->getConnectorId(),
                $this->credentials->getRequestToken(),
                $params
            )
        );

        return Response::createListWithEmailsRequest($response);
    }
}
