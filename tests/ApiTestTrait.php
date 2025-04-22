<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Helmich\Psr7Assert\Psr7Assertions;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;

trait ApiTestTrait
{
    use Psr7Assertions;

    protected MockHandler $rootHandler;

    protected HubspotSearchApi $api;

    /**
     * @before
     */
    protected function setupMockApi(): void
    {
        $this->rootHandler = new MockHandler();
        $factory = new HubspotSearchApiFactory($this->rootHandler);
        $this->api = $factory->new('foo');
    }

    /**
     * @after
     */
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
        $this->assertRequestHasMethod($request, $expectedRequest->getMethod());
        $this->assertRequestHasUri($request, (string) $expectedRequest->getUri());
        $this->assertMessageHasHeader($request, 'Authorization', 'Bearer foo');
        $this->assertMessageHasHeader($request, 'User-Agent', 'GuzzleHttp/7');
        $this->assertSame((string) $expectedRequest->getBody(), (string) $request->getBody());
    }
}
