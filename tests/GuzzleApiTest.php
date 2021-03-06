<?php

use CmdrSharp\GuzzleApi\Client as GuzzleClient;
use DMS\PHPUnitExtensions\ArraySubset\Assert;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use PHPUnit\Framework\TestCase;

class GuzzleApiTest extends TestCase
{
    use ArraySubsetAsserts;

    /** @var GuzzleClient */
    protected $client;

    public function setUp(): void
    {
        $client = new GuzzleClient();
        $this->client = $client->make('https://httpbin.org');
    }

    /** @test */
    function a_standard_get_is_working()
    {
        $response = $this->client->to('anything')->withBody([
            'foo' => 'bar',
            'baz' => 'qux',
        ])->asJson()->get()->getBody();

        Assert::assertArraySubset([
            'json' => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function a_valid_request_is_working()
    {
        $response = $this->client->to('anything')->withBody([
            'foo' => 'bar',
            'baz' => 'qux',
        ])->asJson()->request('get')->getBody();

        Assert::assertArraySubset([
            'json' => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function an_invalid_request_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->client->to('anything')->withBody([
            'foo' => 'bar',
            'baz' => 'qux',
        ])->asJson()->request('foobar')->getBody();
    }

    /** @test */
    function can_retrieve_the_raw_response_body()
    {
        $response = $this->client->to('robots.txt')->get()->getBody();
        $this->assertStringContainsStringIgnoringCase('User-agent: *', (string)$response);
    }

    /** @test */
    function query_parameters_in_urls_are_respected()
    {
        $response = $this->client->to('anything?foo=bar&baz=qux')->get()->getBody();

        Assert::assertArraySubset([
            'args' => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function query_parameters_in_urls_can_be_sent_together_with_array_parameters()
    {
        $response = $this->client->to('anything?foo=bar')->withBody([
            'baz' => 'qux',
        ])->asJson()->get()->getBody();

        Assert::assertArraySubset([
            'args' => [
                'foo' => 'bar',
            ],
            'json' => [
                'baz' => 'qux',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function post_content_can_be_json()
    {
        $response = $this->client->to('post')->withBody([
            'foo' => 'bar',
            'baz' => 'qux',
        ])->asJson()->post()->getBody();

        Assert::assertArraySubset([
            'json' => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function post_content_can_be_sent_as_form_params()
    {
        $response = $this->client->to('post')->withBody([
            'foo' => 'bar',
            'baz' => 'qux',
        ])->asFormParams()->post()->getBody();

        Assert::assertArraySubset([
            'form' => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function get_with_additional_headers()
    {
        $response = $this->client->to('get')->withHeaders([
            'Custom' => 'Header',
        ])->asJson()->get()->getBody();

        Assert::assertArraySubset([
            'headers' => [
                'Custom' => 'Header',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function get_with_appended_headers()
    {
        $response = $this->client->to('get')->withHeaders([
            'Custom' => 'Header',
        ])->addHeaders([
            'Custom2' => 'Header2',
        ])->asJson()->get()->getBody();

        Assert::assertArraySubset([
            'headers' => [
                'Custom' => 'Header',
                'Custom2' => 'Header2',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function can_get_options()
    {
        $options = $this->client->withOptions([
            'Foo' => 'Bar',
        ])->getOptions();

        $this->assertEquals($options, ['Foo' => 'Bar']);
    }

    /** @test */
    function post_with_additional_headers()
    {
        $response = $this->client->to('post')->withHeaders([
            'Custom' => 'Header',
        ])->asJson()->post()->getBody();

        Assert::assertArraySubset([
            'headers' => [
                'Custom' => 'Header',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function patch_requests_are_supported()
    {
        $response = $this->client->to('patch')->withBody([
            'foo' => 'bar',
            'baz' => 'qux',
        ])->asJson()->patch()->getBody();

        Assert::assertArraySubset([
            'json' => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function put_requests_are_supported()
    {
        $response = $this->client->to('put')->withBody([
            'foo' => 'bar',
            'baz' => 'qux',
        ])->asJson()->put()->getBody();

        Assert::assertArraySubset([
            'json' => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function delete_requests_are_supported()
    {
        $response = $this->client->to('delete?id=1')->asJson()->delete()->getBody();

        Assert::assertArraySubset([
            'args' => [
                'id' => 1,
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function query_parameters_are_respected_in_post_requests()
    {
        $response = $this->client->to('post?foo=bar')->withBody([
            'lorem' => 'ipsum',
            'bacon' => 'sandwich',
        ])->asJson()->post()->getbody();

        Assert::assertArraySubset([
            'args' => [
                'foo' => 'bar',
            ],
            'json' => [
                'lorem' => 'ipsum',
                'bacon' => 'sandwich',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function query_parameters_are_respected_in_patch_requests()
    {
        $response = $this->client->to('patch?foo=bar')->withBody([
            'lorem' => 'ipsum',
            'bacon' => 'sandwich',
        ])->asJson()->patch()->getbody();

        Assert::assertArraySubset([
            'args' => [
                'foo' => 'bar',
            ],
            'json' => [
                'lorem' => 'ipsum',
                'bacon' => 'sandwich',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function query_parameters_are_respected_in_put_requests()
    {
        $response = $this->client->to('put?foo=bar')->withBody([
            'lorem' => 'ipsum',
            'bacon' => 'sandwich',
        ])->asJson()->put()->getbody();

        Assert::assertArraySubset([
            'args' => [
                'foo' => 'bar',
            ],
            'json' => [
                'lorem' => 'ipsum',
                'bacon' => 'sandwich',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function query_parameters_are_respected_in_delete_requests()
    {
        $response = $this->client->to('delete?foo=bar')->withBody([
            'lorem' => 'ipsum',
            'bacon' => 'sandwich',
        ])->asJson()->delete()->getbody();

        Assert::assertArraySubset([
            'args' => [
                'foo' => 'bar',
            ],
            'json' => [
                'lorem' => 'ipsum',
                'bacon' => 'sandwich',
            ],
        ], (array)json_decode($response, true));
    }

    /** @test */
    function properly_passes_debug_option()
    {
        $logFile = './guzzle_client_debug_test.log';
        $logFileResource = fopen($logFile, 'w+');

        $this->assertTrue($logFileResource !== false);
        $this->assertTrue(file_exists($logFile));

        $this->client->debug($logFileResource)->to('post')->withBody([
            'foo' => 'bar',
            'key' => 'value',
        ])->asJson()->post()->getBody();

        fclose($logFileResource);
        $logFileContent = file_get_contents($logFile);

        $this->assertTrue($logFileContent !== false);
        $this->assertTrue(strlen($logFileContent) > 0);
        $this->assertTrue(unlink($logFile));
    }
}
