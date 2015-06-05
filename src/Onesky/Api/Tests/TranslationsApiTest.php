<?php

namespace Onesky\Api\Tests;

use Onesky\Api\Client;

/**
 * Class to test the translations resource.
 *
 * See more about translations resource here:
 * https://github.com/onesky/api-documentation-platform/blob/master/resources/translation.md
 *
 * @author Bernardo Silva <benny.stuff@gmail.com>
 */
class TranslationsApiTest extends \PHPUnit_Framework_TestCase
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
    public function testTranslationsDetectMissingResourceAction()
    {
        $this->api->translations();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing parameter: project_id
     */
    public function testTranslationsExportDetectMissingParam()
    {
        $this->api->translations('export');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Invalid authenticate data of api key or secret
     */
    public function testTranslationsExportDetectInvalidAuthentication()
    {
        $this->api->translations('export', ['project_id' => 123]);
    }


    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing parameter: project_id
     */
    public function testTranslationsStatusDetectMissingParam()
    {
        $this->api->translations('status');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Invalid authenticate data of api key or secret
     */
    public function testTranslationsStatusDetectInvalidAuthentication()
    {
        $this->api->translations('status', ['project_id' => 123]);
    }

}
