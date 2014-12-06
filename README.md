api-library-php5
================

PHP5 library for OneSky API

## Requirements
- PHP 5.1 or higher
- CURL extension

## Installation
Download the [source file](/src/api-library-php5.php) and place it in your application

## How to use
**Include the class file**

    require_once('/path/to/api-library-php5.php');

**Create instance**

    $client = new Onesky_Api();

**Set API key and secret**

    $client->setApiKey('<api-key>')->setSecret('<api-secret>');

**Way to make request**

    // resource   => name of resource in camelcase with 's' tailing such as 'projectTypes', 'quotations', 'importTasks'
    // action     => action to take such as 'list', 'show', 'upload', 'export'
    // parameters => parameters passed in the request including URL param such as 'project_id', 'files', 'locale'
    $client->{resource}('{action}', array({parameters}));

**Sample request and get response in array**

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

    // create order
    $response = $client->orders('create', array(
        'project_id' => 999,
        'files'      => 'string.yml',
        'to_locale'  => 'de'
    ));
    $response = json_decode($response, true);

## TODO

- Test with PHPUnit
- Implement missing resources according to [Onesky API document](https://github.com/onesky/api-documentation-platform)
