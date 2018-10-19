Wrapper NoMoreBounce API
================

A simple interface to NoMoreBounce APIs.


See [NoMoreBounce documentation](https://www.nomorebounce.com/docs/index.html) for info on the service.

Installation
------------

Install the package through [composer](http://getcomposer.org):

```bash
composer require brickheadz/php-nomorebounce
```

Make sure, that you include the composer [autoloader](https://getcomposer.org/doc/01-basic-usage.md#autoloading)
somewhere in your codebase.

Basic usage
-----------

- Create (or copy from examples folder) a credentials.json file with connector_id and token fields

```json
{
    "connector_id": <YOUR_CONNECTOR_ID>,
    "token": <YOUR_TOKEN>
}
```

- Create a new instance of NoMoreBounce and pass the path of credentials.json file

```php
use brickheadz\NoMoreBounce\NoMoreBounce;

// Save path to file into variable
$credential_path = __DIR__ . '/credentials.json';
// Instance NoMoreBounce class
$NoMoreBounce = new NoMoreBounce($credential_path);
// Call all needed method from $NoMoreBounce object
```

Examples
-----------

- Validate email :

```php
$response = $NoMoreBounce->checkEmail('testemail@gmail.com');
if ($response) {
    echo "Valid mailbox found";
} else {
    echo "No valid mailbox found";
}
```

- Create list of emails :

```php
$emailList = ['test1@gmail.com', 'test2@gmail.com'];

$response = $NoMoreBounce->createListWithEmails([$emailList])
```

Etc.

Known issues
-----------

- API account/credits is broken at the moment, the relative function is forced to thrown an exception.
- API check/ is a POST request not a GET one, the relative function will already use the right method.
