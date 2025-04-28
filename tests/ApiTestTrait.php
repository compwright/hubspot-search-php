<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use Psr\Http\Message\RequestInterface;

trait ApiTestTrait
{
    protected MockHandler $rootHandler;

    protected HubspotSearchApi $api;

    #[Before]
    protected function setupMockApi(): void
    {
        $this->rootHandler = new MockHandler();
        $factory = new HubspotSearchApiFactory($this->rootHandler);
        $this->api = $factory->new('foo');
    }

    #[After]
    protected function resetMockApi(): void
    {
        $this->rootHandler->reset();
    }

    protected function getExpectedRequest(string $file): RequestInterface
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException('File does not exist: ' . $file);
        }

        $expectedRequest = Message::parseRequest(
            file_get_contents($file) ?: ''
        );

        $expectedRequest = $expectedRequest->withUri(
            $expectedRequest->getUri()->withScheme('https')
        );

        $body = (string) $expectedRequest->getBody();
        if (strlen($body) > 0) {
            // Compact JSON
            $json = json_encode(json_decode($body, false, 512, JSON_THROW_ON_ERROR));
            $expectedRequest = $expectedRequest->withBody(Utils::streamFor($json));
        }

        return $expectedRequest;
    }

    protected function getExpectedResponse(int $status, ?string $file = null): Response
    {
        $response = new Response($status);

        if ($file) {
            if (!file_exists($file)) {
                throw new InvalidArgumentException('File does not exist: ' . $file);
            }
            return $response->withBody(Utils::streamFor(fopen($file, 'r')));
        }

        return $response;
    }

    /**
     * @param mixed $request
     */
    protected function assertRequestMatchesExpected(RequestInterface $expectedRequest, $request): void
    {
        $this->assertInstanceOf(get_class($expectedRequest), $request);
        $this->assertEquals($expectedRequest->getMethod(), $request->getMethod());
        $this->assertEquals((string) $expectedRequest->getUri(), (string) $request->getUri());
        $this->assertEquals('Bearer foo', $request->getHeaderLine('Authorization'));
        $this->assertEquals('GuzzleHttp/7', $request->getHeaderLine('User-Agent'));
        $this->assertSame((string) $expectedRequest->getBody(), (string) $request->getBody());
    }
}
