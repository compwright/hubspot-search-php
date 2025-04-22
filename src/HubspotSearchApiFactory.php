<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp;

use Compwright\EasyApi\ApiClient;
use Compwright\EasyApi\OperationRequestFactory;
use Compwright\ShouldRetry\RetryAfter;
use Compwright\ShouldRetry\ShouldRetry;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\HttpFactory;
use InvalidArgumentException;

class HubspotSearchApiFactory
{
    /** @var ?callable */
    private $rootHandler;

    public function __construct(?callable $rootHandler = null)
    {
        $this->rootHandler = $rootHandler;
    }

    private function operationRequestFactory(): OperationRequestFactory
    {
        $httpFactory = new HttpFactory();
        return new OperationRequestFactory($httpFactory, $httpFactory);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @see https://developers.hubspot.com/docs/guides/apps/authentication/intro-to-auth#private-app-access-tokens
     * @see https://developers.hubspot.com/docs/guides/apps/api-usage/usage-details#error-responses
     */
    public function new(string $token): HubspotSearchApi
    {
        if (!$token) {
            throw new InvalidArgumentException('$token is required');
        }

        $handler = HandlerStack::create($this->rootHandler);
        $handler->push(
            Middleware::retry(
                new ShouldRetry(),
                new RetryAfter()
            ),
            'retry'
        );

        return new HubspotSearchApi(
            new ApiClient(
                new Client([
                    'base_uri' => 'https://api.hubspot.com',
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ],
                    'handler' => $handler,
                ]),
                $this->operationRequestFactory()
            )
        );
    }
}
