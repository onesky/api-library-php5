<?php

namespace Onesky\Api\Tests;

use Onesky\Api\Client;

/**
 * Class to test the projects resource.
 *
 * See more about projects resource here:
 * https://github.com/onesky/api-documentation-platform/blob/master/resources/project.md
 *
 * @author Bernardo Silva <benny.stuff@gmail.com>
 */
class ProjectsApiTest extends \PHPUnit_Framework_TestCase
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
    public function testProjectsDetectMissingResourceAction()
    {
        $this->api->projects();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing parameter: project_group_id
     */
    public function testProjectsListDetectMissingGroupIdParam()
    {
        $this->api->projects('list');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Invalid authenticate data of api key or secret
     */
    public function testProjectsListDetectInvalidAuthentication()
    {
        $this->api->projects('list', ['project_group_id' => 123]);
    }


    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing parameter: project_id
     */
    public function testProjectsShowDetectMissingParam()
    {
        $this->api->projects('show');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Invalid authenticate data of api key or secret
     */
    public function testProjectsShowDetectInvalidAuthentication()
    {
        $this->api->projects('show', ['project_id' => 123]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing parameter: project_group_id
     */
    public function testProjectsCreateDetectMissingGroupIdParam()
    {
        $this->api->projects('create');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Invalid authenticate data of api key or secret
     */
    public function testProjectsCreateDetectMissingParam()
    {
        $this->api->projects('create', ['project_group_id' => 123]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing parameter: project_id
     */
    public function testProjectsUpdateDetectMissingParam()
    {
        $this->api->projects('update');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Invalid authenticate data of api key or secret
     */
    public function testProjectsUpdateDetectInvalidAuthentication()
    {
        $this->api->projects('update', ['project_id' => 123]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing parameter: project_id
     */
    public function testProjectsDeleteDetectMissingParam()
    {
        $this->api->projects('delete');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Invalid authenticate data of api key or secret
     */
    public function testProjectsDeleteDetectInvalidAuthentication()
    {
        $this->api->projects('delete', ['project_id' => 123]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing parameter: project_id
     */
    public function testProjectsLanguagesDetectMissingParam()
    {
        $this->api->projects('languages');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Invalid authenticate data of api key or secret
     */
    public function testProjectsLanguagesDetectInvalidAuthentication()
    {
        $this->api->projects('languages', ['project_id' => 123]);
    }
}
