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
use Phalcon\Cache\BackendInterface;
use Phalcon\Config;

class GeoDataService
{
    /** @var BackendInterface */
    private $cache;

    /** @var Config */
    private $config;

    private $geoClasses = [
        IpApi::class,
        SypexGeo::class,
    ];

    public function __construct(Config $config, BackendInterface $cache)
    {
        $this->cache  = $cache;
        $this->config = $config;
    }

    /**
     * @param string $ip
     *
     * @param array  $showOnly
     *
     * @return GeoDTO|null
     */
    public function getGeoDTO(string $ip): ?GeoDTO
    {
        $key = $this->getCacheKey($ip);
        if ($geoData = $this->cache->get($key)) {
            return unserialize($geoData);
        }

        foreach ($this->geoClasses as $geoClass) {
            /** @var GeoProviderInterface $geoProvider */
            $geoProvider = new $geoClass($ip);

            if ($geoProvider->isAvailable()) {
                $geoData = $geoProvider->getData();
                $this->cache->save($key, serialize($geoData), $this->getCacheTime());

                return $geoData;
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
        $prefix = $this->config->get('cache_prefix_key', Options::PREFIX_CACHE_KEY);

        return $prefix.$ip;
    }

    /**
     * @return int
     */
    private function getCacheTime(): int
    {
        return $this->config->get('cache_time', Options::CACHE_TIME);
    }

    /**
     * @param array $geoData
     * @param array $showOnly
     *
     * @return array
     */
    private function filter(array $geoData, array $showOnly): array
    {
        return array_intersect_key($geoData, array_flip($showOnly));
    }
}
