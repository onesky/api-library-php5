<?php

namespace Onesky\Api\Tests;

use Onesky\Api\Client;

/**
 * Class to test the files resource.
 *
 * See more about files resource here:
 * https://github.com/onesky/api-documentation-platform/blob/master/resources/file.md
 *
 * @author Bernardo Silva <benny.stuff@gmail.com>
 */
class FilesApiTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Onesky\Api\Client $api */
    protected $api;

    public function setUp()
    {
        $this->api = new Client();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid resource action
     */
    public function testFilesDetectMissingResourceAction()
    {
        $this->api->files();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing parameter: project_id
     */
    public function testFilesListDetectMissingParam()
    {
        $this->api->files('list');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Invalid authenticate data of api key or secret
     */
    public function testFilesListDetectInvalidAuthentication()
    {
        $this->api->files('list', ['project_id' => 123]);
    }


    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing parameter: project_id
     */
    public function testFilesUploadDetectMissingParam()
    {
        $this->api->files('upload');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Invalid authenticate data of api key or secret
     */
    public function testFilesUploadDetectInvalidAuthentication()
    {
        $this->api->files('upload', ['project_id' => 123]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing parameter: project_id
     */
    public function testFilesDeleteDetectMissingParam()
    {
        $this->api->files('delete');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Invalid authenticate data of api key or secret
     */
    public function testFilesDeleteDetectInvalidAuthentication()
    {
        $this->api->files('delete', ['project_id' => 123]);
    }
}
