<?php
/**
 * @package Chocolife.me
 * @author  Kamet Aziza <kamet.a@chocolife.kz>
 */

namespace Chocofamily\GeoData\Providers;

use GuzzleHttp\ClientInterface;

abstract class AbstractGeoProvider implements GeoProviderInterface
{
    /** @var array */
    protected $geoData;

    /** @var ClientInterface */
    protected $httpClient;

    /**
     * AbstractGeoData constructor.
     *
     * @param ClientInterface|null $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $ipAddress
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function requestData(string $ipAddress): bool
    {
        $response      = $this->httpClient->request('GET', $this->getServiceLink().$ipAddress);
        $jsonGeoData   = $response->getBody()->getContents();
        $this->geoData = \json_decode($jsonGeoData, true);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            throw new \JsonException(json_last_error_msg());
        }

        return $this->isAvailable();
    }

    /**
     * @return bool
     */
    protected function isAvailable(): bool
    {
        return isset($this->geoData) && !empty($this->geoData) && !$this->containsError();
    }

    /**
     * @return string
     */
    abstract protected function getServiceLink(): string;

    /**
     * @return bool
     */
    abstract protected function containsError(): bool;
}
