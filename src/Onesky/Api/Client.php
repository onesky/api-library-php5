<?php

namespace Onesky\Api;

/**
 * Onesky API wrapper PHP5 library
 */

if (!function_exists('curl_init')) {
  throw new Exception('OneSky needs the CURL PHP extension.');
}

class Client
{
    /**
     * Onesky API endpoint
     */
    protected $_endpoint = 'https://platform.api.onesky.io/1';

    /**
     * Client authenticate token
     */
    protected $_apiKey = '';

    /**
     * Client authenticate secret
     */
    protected $_secret = '';

    /**
     * Resources with actions
     */
    protected $_resources = array(
        'project_groups' => array(
            'list'      => '/project-groups',
            'show'      => '/project-groups/:project_group_id',
            'create'    => '/project-groups',
            'delete'    => '/project-groups/:project_group_id',
            'languages' => '/project-groups/:project_group_id/languages',
        ),
        'projects'       => array(
            'list'      => '/project-groups/:project_group_id/projects',
            'show'      => '/projects/:project_id',
            'create'    => '/project-groups/:project_group_id/projects',
            'update'    => '/projects/:project_id',
            'delete'    => '/projects/:project_id',
            'languages' => '/projects/:project_id/languages',
        ),
        'files'          => array(
            'list'   => '/projects/:project_id/files',
            'upload' => '/projects/:project_id/files',
            'delete' => '/projects/:project_id/files',
        ),
        'translations'   => array(
            'export' => '/projects/:project_id/translations',
            'status' => '/projects/:project_id/translations/status',
        ),
        'import_tasks'   => array(
            'show' => '/projects/:project_id/import-tasks/:import_id'
        ),
        'quotations'     => array(
            'show' => '/projects/:project_id/quotations'
        ),
        'orders'         => array(
            'list'   => '/projects/:project_id/orders',
            'show'   => '/projects/:project_id/orders/:order_id',
            'create' => '/projects/:project_id/orders'
        ),
        'locales'        => array(
            'list' => '/locales'
        ),
        'project_types'  => array(
            'list' => '/project-types'
        ),
    );

    /**
     * Actions to use multipart to upload file
     */
    protected $_multiPartActions = array(
        'files' => array('upload'),
    );

    /**
     * Actions to use multipart to upload file
     */
    protected $_exportFileActions = array(
        'translations' => array('export'),
    );

    /**
     * Methods of actions mapping
     */
    protected $_methods = array(
        // 'get'    => array('list', 'show', 'languages', 'export', 'status'),
        'post'   => array('create', 'upload'),
        'put'    => array('update'),
        'delete' => array('delete'),
    );

    /**
     * Default curl settings
     */
    protected $_curlSettings = array(
        CURLOPT_RETURNTRANSFER => true,
    );

    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
        return $this;
    }

    public function setSecret($secret)
    {
        $this->_secret = $secret;
        return $this;
    }

    /**
     * Retrieve resources
     * @return array
     */
    public function getResources()
    {
        return array_keys($this->_resources);
    }

    /**
     * Retrieve actions of a resource
     * @param  string $resource Resource name
     * @return array|null
     */
    public function getActionsByResource($resource)
    {
        if (!isset($this->_resources[$resource]))
            return null; // no resource found

        $actions = array();
        foreach ($this->_resources[$resource] as $action => $path)
            $actions[] = $action;

        return $actions;
    }

    /**
     * Retrieve the corresponding method to use
     * @param  string $action Action name
     * @return string
     */
    public function getMethodByAction($action)
    {
        foreach ($this->_methods as $method => $actions) {
            if (in_array($action, $actions))
                return $method;
        }

        return 'get';
    }

    /**
     * Determine if using mulit-part to upload file
     * @param  string  $resource Resource name
     * @param  string  $action   Action name
     * @return boolean
     */
    public function isMultiPartAction($resource, $action)
    {
        return isset($this->_multiPartActions[$resource]) && in_array($action, $this->_multiPartActions[$resource]);
    }

    /**
     * Determine if it is to export (download) file
     * @param  string  $resource Resource name
     * @param  string  $action   Action name
     * @return boolean
     */
    public function isExportFileAction($resource, $action)
    {
        return isset($this->_exportFileActions[$resource]) && in_array($action, $this->_exportFileActions[$resource]);
    }

    /**
     * For developers to initial request to Onesky API
     * 
     * Example:
     *     $onesky = new Onesky_Api();
     *     $onesky->setApiKey('<api-key>')->setSecret('<api-secret>');
     *     
     *     // To list project type
     *     $onesky->projectTypes('list');
     *
     *     // To create project
     *     $onesky->projects('create', array('project_group_id' => 999));
     *
     *     // To upload string file
     *     $onesky->files('upload', array('project_id' => 1099, 'file' => 'path/to/file.yml', 'file_format' => 'YAML'));
     * 
     * @param  string $fn_name Function name acted as resource name
     * @param  array  $params  Parameters passed in request
     * @return array  Response
     */
    public function __call($fn_name, $params)
    {
        // is valid resource
        $resource = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $fn_name)); // camelcase to underscore
        if (!in_array($resource, $this->getResources()))
            throw new BadMethodCallException('Invalid resource');

        // is valid action
        $action = array_shift($params); // action name
        if (!in_array($action, $this->getActionsByResource($resource)))
            throw new InvalidArgumentException('Invalid resource action');

        // parameters
        if (count($params) > 0) {
            $params = $this->_normalizeParams(array_shift($params));
        } else {
            $params = array();
        }

        // get request method
        $method = $this->getMethodByAction($action);

        // get path
        $path = $this->_getRequestPath($resource, $action, $params);

        // is multi-part
        $isMultiPart = $this->isMultiPartAction($resource, $action);

        // return response
        return $this->_callApi($method, $path, $params, $isMultiPart);
    }

    /**
     * Retrieve request path and replace variables with values
     * @param  string $resource Resource name
     * @param  string $action   Action name
     * @param  array  $params   Parameters
     * @return string Request path
     */
    private function _getRequestPath($resource, $action, &$params)
    {
        if (!isset($this->_resources[$resource]) || !isset($this->_resources[$resource][$action]))
            throw new UnexpectedValueException('Resource path not found');

        // get path
        $path = $this->_resources[$resource][$action];

        // replace variables
        $matchCount = preg_match_all("/:(\w*)/", $path, $variables);
        if ($matchCount) {
            foreach ($variables[0] as $index => $placeholder) {
                if (!isset($params[$variables[1][$index]]))
                    throw new InvalidArgumentException('Missing parameter: ' . $variables[1][$index]);

                $path = str_replace($placeholder, $params[$variables[1][$index]], $path);
                unset($params[$variables[1][$index]]); // remove parameter from $params
            }
        }

        return $path;
    }

    protected function _verifyTokenAndSecret()
    {
        if (empty($this->_apiKey) || empty($this->_secret))
            throw new UnexpectedValueException('Invalid authenticate data of api key or secret');
    }

    /**
     * Initial request to Onesky API
     * @param  string  $method
     * @param  string  $path
     * @param  array   $params
     * @param  boolean $isMultiPart
     * @param  boolean $isExportFile
     * @return array
     */
    private function _callApi($method, $path, $params, $isMultiPart)
    {
        // init session
        $ch = curl_init();

        // request settings
        curl_setopt_array($ch, $this->_curlSettings); // basic settings

        // url
        $url = $this->_endpoint . $path;
        if ($method == 'get') // ['post', 'put', 'delete']
            $url .= $this->_getAuthQueryStringWithParams($params);
        else
            $url .= $this->_getAuthQueryString();
        curl_setopt($ch, CURLOPT_URL, $url);

        // http header
        if (!$isMultiPart)
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

        // method specific settings
        switch ($method) {
            case 'post':
                curl_setopt($ch, CURLOPT_POST, 1);

                // requst body
                if ($isMultiPart) {
                    if (version_compare(PHP_VERSION, '5.5.0') === -1) {
                        // fallback to old method
                        $params['file'] = '@' . $params['file'];
                    } else {
                        // make use of CURLFile
                        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
                        $params['file'] = new \CURLFile($params['file']);
                    }
                    $postBody = $params;
                } else {
                    $postBody = json_encode($params);
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postBody);

                break;

            case 'put':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

                break;

            case 'delete':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

                break;
        }

        // execute request
        $response = curl_exec($ch);

        // error handling
        if ($response === false)
            throw new UnexpectedValueException(curl_error($ch));

        // close connection
        curl_close($ch);

        // return response
        return $response;
    }

    private function _getAuthQueryStringWithParams($params)
    {
        $queryString = $this->_getAuthQueryString();

        if (count($params) > 0)
            $queryString .= '&' . http_build_query($params);

        return $queryString;
    }

    private function _getAuthQueryString()
    {
        $this->_verifyTokenAndSecret();

        $timestamp = time();
        $devHash = md5($timestamp . $this->_secret);

        $queryString  = '?api_key=' . $this->_apiKey;
        $queryString .= '&timestamp=' . $timestamp;
        $queryString .= '&dev_hash=' . $devHash;

        return $queryString;
    }

    private function _normalizeParams(array $params)
    {
        // change boolean value to integer for curl
        foreach ($params as $key => $value) {
            if (is_bool($value)) {
                $params[$key] = (int)$value;
            }
        }

        return $params;
    }

}
