<?php

namespace CmdrSharp\GuzzleApi;

use Psr\Http\Message\ResponseInterface;

interface RequestInterface
{
    /**
     * @param string $base_uri
     * @return RequestInterface
     */
    public function make(string $base_uri): RequestInterface;

    /**
     * @param string $uri
     * @return RequestInterface
     */
    public function to(string $uri): RequestInterface;

    /**
     * @param array $body
     * @param array $headers
     * @param array $options
     * @return RequestInterface
     */
    public function with(array $body = [], array $headers = [], array $options = []): RequestInterface;

    /**
     * @param array $body
     * @return RequestInterface
     */
    public function withBody(array $body = []): RequestInterface;

    /**
     * @param array $body
     * @return RequestInterface
     */
    public function addBody(array $body = []): RequestInterface;

    /**
     * @return array
     */
    public function getBody(): array;

    /**
     * @param array $headers
     * @return RequestInterface
     */
    public function withHeaders(array $headers = []): RequestInterface;

    /**
     * @param array $headers
     * @return RequestInterface
     */
    public function addHeaders(array $headers = []): RequestInterface;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @param array $options
     * @return RequestInterface
     */
    public function withOptions(array $options = []): RequestInterface;

    /**
     * @param array $options
     * @return RequestInterface
     */
    public function addOptions(array $options = []): RequestInterface;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @return RequestInterface
     */
    public function asFormParams(): RequestInterface;

    /**
     * @return RequestInterface
     */
    public function asJson(): RequestInterface;

    /**
     * @param bool $debug
     * @return RequestInterface
     */
    public function debug($debug = true): RequestInterface;

    /**
     * @return ResponseInterface
     */
    public function get(): ResponseInterface;

    /**
     * @return ResponseInterface
     */
    public function post(): ResponseInterface;

    /**
     * @return ResponseInterface
     */
    public function put(): ResponseInterface;

    /**
     * @return ResponseInterface
     */
    public function patch(): ResponseInterface;

    /**
     * @return ResponseInterface
     */
    public function delete(): ResponseInterface;

    /**
     * @param string $method
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function request(string $method): ResponseInterface;
}
