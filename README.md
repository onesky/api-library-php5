[![Build Status](https://travis-ci.org/onesky/api-library-php5.svg)](https://travis-ci.org/onesky/api-library-php5)

# Onesky PHP5 API Library

PHP5 library for OneSky API

## Requirements
- PHP 5.1 or higher
- CURL extension

## Installation
Install the latest version with `composer require onesky/api-library-php5`

## How to use

**Create instance**

```php
use Onesky\Api\Client;

$client = new Client();
```

**Set API key and secret**

```php
$client->setApiKey('<api-key>')->setSecret('<api-secret>');
```

**Way to make request**

```php
// resource   => name of resource in camelcase with 's' tailing such as 'projectTypes', 'quotations', 'importTasks'
// action     => action to take such as 'list', 'show', 'upload', 'export'
// parameters => parameters passed in the request including URL param such as 'project_id', 'files', 'locale'
$client->{resource}('{action}', array({parameters}));
```

**Sample request and get response in array**

```php5
// list project groups
$response = $client->projectTypes('list');
$response = json_decode($response, true);

// show a project
$response = $client->projects('show', array('project_id' => 999));
$response = json_decode($response, true);

// upload file
$response = $client->files('upload', array(
    'project_id'  => 999,
    'file'        => 'path/to/string.yml',
    'file_format' => 'YAML',
    'locale'      => 'fr'
));
$response = json_decode($response, true);

// export translation
$response = $client->translations('export', array(
    'project_id' => 999,
    'locale'     => 'ja',
    'source_file_name' => 'string.yml'
));
file_put_contents('path/to/file', $response);

// create order
$response = $client->orders('create', array(
    'project_id' => 999,
    'files'      => 'string.yml',
    'to_locale'  => 'de'
));
$response = json_decode($response, true);
```

## TODO

- Test with PHPUnit
- Implement missing resources according to [Onesky API document](https://github.com/onesky/api-documentation-platform)
