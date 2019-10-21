<?php
/**
 * @package Chocolife.me
 * @author  Kamet Aziza <kamet.a@chocolife.kz>
 */

namespace Chocofamily\GeoData\Providers;

use GuzzleHttp\Client;

abstract class AbstractGeoProvider implements GeoProviderInterface
{
    protected $geoData;

    /**
     * AbstractGeoData constructor.
     *
     * @param $ipAddress
     *
     * @throws \JsonException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct($ipAddress)
    {
        $client        = new Client();
        $response      = $client->request('GET', $this->getServiceLink().$ipAddress);
        $jsonGeoData   = $response->getBody()->getContents();
        $this->geoData = \json_decode($jsonGeoData, true);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            throw new \JsonException(json_last_error_msg());
        }
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
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
