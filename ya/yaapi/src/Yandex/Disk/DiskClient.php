<?php
/**
 * Yandex PHP Library
 *
 * @copyright NIX Solutions Ltd.
 * @link https://github.com/nixsolutions/yandex-php-library
 */

/**
 * @namespace
 */
namespace Yandex\Disk;

use Guzzle\Service\Client;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Yandex\Common\AbstractServiceClient;
use Yandex\Disk\Exception\DiskRequestException;

/**
 * Class DiskClient
 *
 * @category Yandex
 * @package Disk
 *
 * @author   Alexander Mitsura <mitsuraa@gmail.com>
 * @created  07.10.13 12:35
 */
class DiskClient extends AbstractServiceClient
{
    /**
     * @var string
     */
    private $version = 'v1';

    /**
     * @var string
     */
    protected $serviceDomain = 'webdav.yandex.ru';

    /**
     * @param string $version
     *
     * @return self
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @inheritdoc
     */
    public function getServiceUrl($resource = '')
    {
        return parent::getServiceUrl($resource) . '/' . $this->version;
    }

    /**
     * @param $path
     * @return string
     */
    public function getRequestUrl($path)
    {
        return parent::getServiceUrl() . $path;
    }

    /**
     * @param string $token access token
     */
    public function __construct($token = '')
    {
        $this->setAccessToken($token);
    }

    /**
     * Sends a request
     *
     * @param RequestInterface $request
     *
     * @throws \Exception|\Guzzle\Http\Exception\ClientErrorResponseException
     * @return Response
     */
    protected function sendRequest(RequestInterface $request)
    {
        try {
            $request = $this->prepareRequest($request);
            $response = $request->send();
        } catch (ClientErrorResponseException $ex) {

            $result = $request->getResponse();
            $code = $result->getStatusCode();
            $message = $result->getReasonPhrase();

            throw new DiskRequestException(
                'Service responded with error code: "' . $code . '" and message: "' . $message . '"'
            );
        }

        return $response;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function createDirectory($path = '')
    {
        $client = new Client($this->getServiceUrl());
        $request = $client->createRequest('MKCOL');
        $request->setPath($path);
        return (bool)$this->sendRequest($request);
    }

    /**
     * @param string $path
     * @param null $offset
     * @param null $amount
     * @return array
     */
    public function directoryContents($path = '', $offset = null, $amount = null)
    {
        $client = new Client($this->getServiceUrl());
        $request = $client->createRequest('PROPFIND');
        $request->setPath($path);
        $request->setHeader('Depth', '1');

        if (isset($offset, $amount)) {
            $request->getQuery()->set('offset', $offset)->set('amount', $amount);
        }

        $xml = $this->sendRequest($request)->xml()->children('DAV:');

        $contents = array();
        foreach ($xml as $element) {
            array_push(
                $contents,
                array(
                    'href' => $element->href->__toString(),
                    'status' => $element->propstat->status->__toString(),
                    'creationDate' => $element->propstat->prop->creationdate->__toString(),
                    'lastModified' => $element->propstat->prop->getlastmodified->__toString(),
                    'displayName' => $element->propstat->prop->displayname->__toString(),
                    'contentLength' => $element->propstat->prop->getcontentlength->__toString(),
                    'resourceType' => $element->propstat->prop->resourcetype->collection ? 'dir' : 'file',
                    'contentType' => $element->propstat->prop->getcontenttype->__toString()
                )
            );
        }
        return $contents;
    }

    /**
     * @return array
     */
    public function diskSpaceInfo()
    {
        $client = new Client($this->getServiceUrl());

        $body = '<?xml version="1.0" encoding="utf-8" ?><D:propfind xmlns:D="DAV:">
            <D:prop><D:quota-available-bytes/><D:quota-used-bytes/></D:prop></D:propfind>';

        $request = $client->createRequest(
            'PROPFIND',
            '/',
            array(
                'Depth' => '0'
            ),
            $body
        );
        $result = $this->sendRequest($request)->xml()->children('DAV:');
        $info = (array)$result->response->propstat->prop;
        return array(
            'usedBytes' => $info['quota-used-bytes'],
            'availableBytes' => $info['quota-available-bytes']
        );
    }

    /**
     * @param string $path
     * @param string $property
     * @param string $value
     * @param string $namespace
     * @return bool
     */
    public function setProperty($path = '', $property = '', $value = '', $namespace = 'default:namespace')
    {
        if (!empty($property) && !empty($value)) {
            $client = new Client($this->getServiceUrl());

            $body = '<?xml version="1.0" encoding="utf-8" ?><propertyupdate xmlns="DAV:" xmlns:u="'
                . $namespace . '"><set><prop><u:' . $property . '>' . $value . '</u:'
                . $property . '></prop></set></propertyupdate>';

            $request = $client->createRequest(
                'PROPPATCH',
                $path,
                array(
                    'Content-Length' => strlen($body),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ),
                $body
            );
            $response = $this->sendRequest($request)->xml()->children('DAV:')->response;
            $resultStatus = $response->propstat->status;
            if (strpos($resultStatus, '200 OK')) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $path
     * @param string $property
     * @param string $namespace
     * @return string|false
     */
    public function getProperty($path = '', $property = '', $namespace = 'default:namespace')
    {
        if (!empty($property)) {
            $client = new Client($this->getServiceUrl());

            $body = '<?xml version="1.0" encoding="utf-8" ?><propfind xmlns="DAV:"><prop><' . $property
                . ' xmlns="' . $namespace . '"/></prop></propfind>';

            $request = $client->createRequest(
                'PROPFIND',
                $path,
                array(
                    'Depth' => '1',
                    'Content-Length' => strlen($body),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ),
                $body
            );

            $result = $this->sendRequest($request);
            $response = $result->xml()->children('DAV:')->response;
            $resultStatus = $response->propstat->status;
            if (strpos($resultStatus, '200 OK')) {
                return (string)$response->propstat->prop->children();
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        $client = new Client($this->getServiceUrl());
        $request = $client->get('/?userinfo');
        $response = $this->sendRequest($request);
        $result = explode(":", $response->getBody(true));
        array_shift($result);
        return implode(':', $result);
    }

    /**
     * @param string $path
     * @param string $destination
     * @param string $name
     * @return array
     */
    public function getFile($path = '', $destination = '', $name = '')
    {
        $result = array();
        $client = new Client($this->getServiceUrl());
        $request = $client->createRequest('GET');
        $request->setPath($path);

        $response = $this->sendRequest($request);
        $headers = $response->getHeaders();
        foreach ($headers as $key => $value) {
            /* @var \Guzzle\Http\Message\Header\HeaderInterface $value */
            $result['headers'][$key] = $value->toArray();
        }
        $result['body'] = $response->getBody(true);
        return $result;
    }

    /**
     * @param string $path
     * @param string $destination
     * @param string $name
     * @return string|false
     */
    public function downloadFile($path = '', $destination = '', $name = '')
    {
        $client = new Client($this->getServiceUrl());
        $request = $client->createRequest('GET');
        $request->setPath($path);
        $response = $this->sendRequest($request);

        if ($name === '') {
            $matchResults = array();
            preg_match("/.*?filename=\"(.*?)\".*?/", $response->getContentDisposition(), $matchResults);
            $name = urldecode($matchResults[1]);
        }

        $file = $destination . $name;

        $result = file_put_contents($file, $response->getBody(true)) ? $file : false;

        return $result;
    }

    /**
     * @param string $path
     * @param array $file
     * @param array $extraHeaders
     * @return void
     */
    public function uploadFile($path = '', $file = null, $extraHeaders = null)
    {
        if (file_exists($file['path'])) {
            $headers = array(
                'Content-Length' => (string)$file['size']
            );
            $finfo = finfo_open(FILEINFO_MIME);
            $mime = finfo_file($finfo, $file['path']);
            $parts = explode(";", $mime);
            $headers['Content-Type'] = $parts[0];
            $headers['Etag'] = md5_file($file['path']);
            $headers['Sha256'] = hash_file('sha256', $file['path']);
            $headers['Host'] = $this->getServiceDomain();
            $headers['Accept'] = '*/*';
            $headers['Authorization'] = 'OAuth ' . $this->getAccessToken();
            $headers = isset($extraHeaders) ? array_merge($headers, $extraHeaders) : $headers;

            $client = new Client($this->getServiceUrl());
            $request = $client->createRequest(
                'PUT',
                $path . $file['name'],
                $headers,
                file_get_contents($file['path'])
            );
            $this->sendRequest($request);
        }
    }

    /**
     * @param $path
     * @param $size
     * @return array
     */
    public function getImagePreview($path, $size)
    {
        $result = array();
        $client = new Client($this->getServiceUrl());
        $request = $client->createRequest('GET');
        $request->setPath($path);
        $request->getQuery()->set('preview', null)->set('size', $size);
        $response = $this->sendRequest($request);
        $headers = $response->getHeaders();
        foreach ($headers as $key => $value) {
            $result['headers'][$key] = $value[0];
        }
        $result['body'] = $response->getBody(true);
        return $result;
    }

    /**
     * @param string $target
     * @param string $destination
     * @return bool
     */
    public function copy($target = '', $destination = '')
    {
        $client = new Client($this->getServiceUrl());
        $request = $client->createRequest('COPY');
        $request->setPath($target);
        $request->setHeader('Destination', $destination);
        return (bool)$this->sendRequest($request);
    }

    /**
     * @param string $path
     * @param string $destination
     * @return bool
     */
    public function move($path = '', $destination = '')
    {
        $client = new Client($this->getServiceUrl());
        $request = $client->createRequest('MOVE');
        $request->setPath($path);
        $request->setHeader('Destination', $destination);
        return (bool)$this->sendRequest($request);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function delete($path = '')
    {
        $client = new Client($this->getServiceUrl());
        $request = $client->createRequest('DELETE');
        $request->setPath($path);
        return (bool)$this->sendRequest($request);
    }

    /**
     * @param string $path
     * @return string
     */
    public function startPublishing($path = '')
    {
        $client = new Client($this->getServiceUrl());

        $body = '<propertyupdate xmlns="DAV:"><set><prop>
            <public_url xmlns="urn:yandex:disk:meta">true</public_url>
            </prop></set></propertyupdate>';

        $request = $client->createRequest(
            'PROPPATCH',
            $path,
            array(
                'Content-Length' => strlen($body)
            ),
            $body
        );
        $result = $this->sendRequest($request)->xml()->children('DAV:');
        $publicUrl = $result->response->propstat->prop->children()->public_url;
        return (string)$publicUrl;
    }

    /**
     * @param string $path
     * @return void
     */
    public function stopPublishing($path = '')
    {
        $client = new Client($this->getServiceUrl());

        $body = '<propertyupdate xmlns="DAV:"><remove><prop>
            <public_url xmlns="urn:yandex:disk:meta" />
            </prop></remove></propertyupdate>';

        $request = $client->createRequest(
            'PROPPATCH',
            $path,
            array(
                'Content-Length' => strlen($body)
            ),
            $body
        );
        $this->sendRequest($request);
    }

    /**
     * @param string $path
     * @return string|bool
     */
    public function checkPublishing($path = '')
    {
        $client = new Client($this->getServiceUrl());

        $body = '<propfind xmlns="DAV:"><prop><public_url xmlns="urn:yandex:disk:meta"/></prop></propfind>';

        $request = $client->createRequest(
            'PROPFIND',
            $path,
            array(
                'Content-Length' => strlen($body),
                'Depth' => '0'
            ),
            $body
        );
        $result = $this->sendRequest($request)->xml()->children('DAV:');
        $propArray = (array)$result->response->propstat->prop->children();
        return empty($propArray['public_url']) ? (bool)false : (string)$propArray['public_url'];
    }
}
