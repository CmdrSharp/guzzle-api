<?php
declare(strict_types=1);

namespace CmdrSharp\GuzzleApi;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class Client implements RequestInterface
{
    /** @var GuzzleClient */
    protected $client;

    /** @var string */
    protected $uri;

    /** @var array */
    protected $body = [];

    /** @var array */
    protected $headers = [];

    /** @var array */
    protected $options = [];

    /** @var string */
    protected $format = 'body';

    /** @var bool|resource */
    protected $debug = false;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->client = new GuzzleClient();
    }

    /**
     * Create a new Guzzle Client specifying the Base URI.
     *
     * @param string $base_uri
     * @return RequestInterface
     */
    public function make(string $base_uri): RequestInterface
    {
        $this->client = new GuzzleClient(['base_uri' => $base_uri]);

        return $this;
    }

    /**
     * Specify the URI for the Request.
     *
     * @param string $uri
     * @return RequestInterface
     */
    public function to(string $uri): RequestInterface
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Specify the payload.
     *
     * @param mixed $body
     * @param array $headers
     * @param array $options
     * @return RequestInterface
     */
    public function with($body = [], array $headers = [], array $options = []): RequestInterface
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->options = $options;

        return $this;
    }

    /**
     * Specify the body for the request.
     *
     * @param mixed $body
     * @return RequestInterface
     */
    public function withBody($body = []): RequestInterface
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Append to existing body.
     *
     * @param array $body
     * @return RequestInterface
     */
    public function addBody(mixed $body = []): RequestInterface
    {
        if(is_array($body) && is_array($this->body)) {
            $this->body = array_merge($this->body, $body);
        }
    
        if(is_string($body) && is_string($this->body)) {
            $this->body .= $body;
        }

        return $this;
    }

    /**
     * Get existing body.
     *
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Specify the headers for the request.
     *
     * @param array $headers
     * @return RequestInterface
     */
    public function withHeaders(array $headers = []): RequestInterface
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Append to existing headers.
     *
     * @param array $headers
     * @return RequestInterface
     */
    public function addHeaders(array $headers = []): RequestInterface
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Get existing headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Specify the options for the request.
     *
     * @param array $options
     * @return RequestInterface
     */
    public function withOptions(array $options = []): RequestInterface
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Append to existing options.
     *
     * @param array $options
     * @return RequestInterface
     */
    public function addOptions(array $options = []): RequestInterface
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Get existing options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Specify the body to be Form Parameters.
     *
     * @return RequestInterface
     */
    public function asFormParams(): RequestInterface
    {
        $this->format = 'form_params';

        return $this;
    }

    /**
     * Specify the body to be JSON.
     *
     * @return RequestInterface
     */
    public function asJson(): RequestInterface
    {
        $this->format = 'json';

        return $this;
    }

    /**
     * Specify the body to be a string.
     *
     * @return RequestInterface
     */
    public function asString(): RequestInterface
    {
        $this->format = 'body';

        return $this;
    }

    /**
     * Toggle debugging.
     *
     * @param bool $debug
     * @return $this
     */
    public function debug($debug = true): RequestInterface
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * Send a GET Request.
     *
     * @return ResponseInterface
     */
    public function get(): ResponseInterface
    {
        return $this->makeRequest();
    }

    /**
     * Send a POST Request.
     *
     * @return ResponseInterface
     */
    public function post(): ResponseInterface
    {
        return $this->makeRequest('POST');
    }

    /**
     * Send a PUT Request.
     *
     * @return ResponseInterface
     */
    public function put(): ResponseInterface
    {
        return $this->makeRequest('PUT');
    }

    /**
     * Send a PATCH Request.
     *
     * @return ResponseInterface
     */
    public function patch(): ResponseInterface
    {
        return $this->makeRequest('PATCH');
    }

    /**
     * Send a DELETE Request.
     *
     * @return ResponseInterface
     */
    public function delete(): ResponseInterface
    {
        return $this->makeRequest('DELETE');
    }

    /**
     * @param string $method
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function request(string $method): ResponseInterface
    {
        if (!in_array(strtolower($method), ['get', 'post', 'put', 'patch', 'delete'])) {
            throw new \InvalidArgumentException('The specified method must be either GET, POST, PUT, PATCH or DELETE');
        }

        return $this->makeRequest($method);
    }

    /**
     * Sends the request.
     *
     * @param string $method
     * @return ResponseInterface
     */
    private function makeRequest(string $method = 'GET'): ResponseInterface
    {
        $requestParameters = [
            $this->format => $this->body,
            'headers' => $this->headers,
            'debug' => $this->debug,
        ];

        if ($this->options !== null) {
            $requestParameters = array_merge($requestParameters, $this->options);
        }

        $response = $this->client->request($method, $this->uri, $requestParameters);

        $this->debug = false;

        return $response;
    }
}
