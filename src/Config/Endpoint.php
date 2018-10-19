<?php
namespace brickheadz\NoMoreBounce\Config;

/**
 * Class to group all endpoint
 */
class Endpoint
{

    /** @var string Check email */
    const POST_EMAIL_CHECK = 'check/';

    /** @var string Return available credits */
    const GET_AVAILABLE_CREDITS = 'account/credits/';

    /** @var string Return all imported lists */
    const GET_LISTS = 'importer/lists/';

    /** @var string Return all email into specific list */
    const GET_EMAILS_INTO_LIST = 'importer/emails-list/';

    /** @var string Add email to specific list */
    const POST_CREATE_LIST_WITH_EMAILS = 'importer/add/';

    /** @var string Return all statistics about specific list */
    const GET_LIST_STAT = 'importer/stats/';

}
