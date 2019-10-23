<?php
/**
 * @package Chocolife.me
 * @author  Kamet Aziza <kamet.a@chocolife.kz>
 */

namespace Chocofamily\GeoData;

use Chocofamily\GeoData\Config\Options;
use Chocofamily\GeoData\DTO\GeoDTO;
use Chocofamily\GeoData\Providers\GeoProviderInterface;
use Chocofamily\GeoData\Providers\IpApi;
use Chocofamily\GeoData\Providers\SypexGeo;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Phalcon\Cache\BackendInterface;

class GeoDataService
{
    /** @var BackendInterface */
    private $cache;

    /** @var array */
    private $config;

    /** @var ClientInterface */
    private $httpClient;

    private $geoProviders = [
        IpApi::class,
        SypexGeo::class,
    ];

    public function __construct(array $config, BackendInterface $cache, ClientInterface $httpClient = null)
    {
        $this->cache      = $cache;
        $this->config     = $config;
        $this->httpClient = $httpClient ?? new Client();
    }

    /**
     * @param string $ip
     *
     * @return GeoDTO|null
     */
    public function getGeoDTO(string $ip): ?GeoDTO
    {
        $key = $this->getCacheKey($ip);
        if ($geoDTO = $this->cache->get($key)) {
            return unserialize($geoDTO);
        }

        foreach ($this->geoProviders as $geoProviderClass) {
            /** @var GeoProviderInterface $geoProvider */
            $geoProvider = new $geoProviderClass($this->httpClient);

            if ($geoProvider->requestData($ip)) {
                $geoDTO = $geoProvider->getDTO();
                $this->cache->save($key, serialize($geoDTO), $this->getCacheTime());

                return $geoDTO;
            }
        }

        return null;
    }

    /**
     * @param string $ip
     *
     * @return string
     */
    private function getCacheKey(string $ip): string
    {
        $prefix = $this->config['cache_key_prefix'] ?? Options::CACHE_KEY_PREFIX;

        return $prefix.$ip;
    }

    /**
     * @return int
     */
    private function getCacheTime(): int
    {
        return $this->config['cache_time'] ?? Options::CACHE_TIME;
    }
}
