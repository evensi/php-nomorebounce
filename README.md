Wrapper NoMoreBounce API
================
[![Latest Stable Version](https://poser.pugx.org/brickheadz/php-nomorebounce/v/stable)](https://packagist.org/packages/brickheadz/php-nomorebounce)
[![License](https://poser.pugx.org/brickheadz/php-nomorebounce/license)](https://packagist.org/packages/brickheadz/php-nomorebounce)

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

- API account/credits is broken at the moment, if you call the relative function an Exception will thrown due to invalid response.
- API check/ is a POST request not a GET one, the relative function will already use the right method.

Contribute
-----------

We welcome any contribution to this library. Feel free to clone this repository, make the desired code changes, test locally (you need a token and connector_id)

```bash
$ composer dump-autoload
$ php  examples/test.php
```

and send a Pull Request.