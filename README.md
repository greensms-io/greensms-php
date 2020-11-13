# greensms-php

## Documentation

The documentation for the GREENSMS API can be found [here][apidocs].

## Installation

```bash
composer require greeensms
```

## Sample Usage

Check out these [code examples](examples) to get up and running quickly.

```php

use GreenSms\GreenSms;

# Register at my.greeensms.ru first
$client = new GreenSms([
  'user' => 'test',
  'pass' => 'test'
]);


echo "Sms Request Id: " . $response->request_id;

```

### Environment Variables

`greensms-php` supports credential storage in environment variables. If no credentials are provided following env vars will be used: `GREENSMS_USER`/`GREENSMS_PASS` OR `GREENSMS_TOKEN`.

### Token Auth

```php

use GreenSms\GreenSms;

$tokenClient = new GreenSms([
  'token' => 'yourtoken'
]);

$response = $tokenClient->account->balance();
echo "Balance : " . $response->balance. "\n";


```

## Compatibility

`greensms-php` is compatible with PHP 5.5 and PHP 7+ onwards until the latest PHP Version

## Methods

- You can either use username/password combination or auth token to create an object with constructor
- Each API Function is available as `MODULE.FUNCTION()`
- Parameters for each API can be referred from [here][apidocs]
- Response keys by default are available in `snake_case`. If you want to use `camelCase`, then pass `'camelCaseResponse'= > true`, in the constructor

## Handling Exceptions

- Exceptions for all APIs are thrown with RestException class. It extends the default PHP Exception class.
- Each error, will have a message and code similar to PHP Exceptions.
- In case of _Validation Error_, additional params are available to show field-wise rule failures. Can be accessed by `$e->getParams()` method on the error object

## Getting help

If you need help installing or using the library, please contact us: [support@greensms.ru](mailto:support@greensms.ru).

If you've instead found a bug in the library or would like new features added, go ahead and open issues or pull requests against this repo!

## Contributing

Bug fixes, docs, and library improvements are always welcome. Please refer to our [Contributing Guide](CONTRIBUTING.md) for detailed information on how you can contribute.
If you're not familiar with the GitHub pull request/contribution process, [this is a nice tutorial](https://gun.io/blog/how-to-github-fork-branch-and-pull-request/).

### Getting Started

If you want to familiarize yourself with the project, you can start by [forking the repository](https://help.github.com/articles/fork-a-repo/) and [cloning it in your local development environment](https://help.github.com/articles/cloning-a-repository/). The project requires [Node.js](https://nodejs.org) to be installed on your machine.

After cloning the repository, install the dependencies by running the following command in the directory of your cloned repository:

```bash
composer require
```

GreenSMS has all the unit tests defined under **tests** folder with `*Test.php` extension. It uses PHPUnit, which is added as a dev dependency. You can run all the tests using the following command.

```bash
./vendor/bin/phpunit tests
```

[apidocs]: https://api.greensms.ru/
