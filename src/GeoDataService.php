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

    private $geoProviders = [
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
            $geoProvider = new $geoProviderClass($ip);

            if ($geoProvider->isAvailable()) {
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
        $prefix = $this->config->get('cache_key_prefix', Options::CACHE_KEY_PREFIX);

        return $prefix.$ip;
    }

    /**
     * @return int
     */
    private function getCacheTime(): int
    {
        return $this->config->get('cache_time', Options::CACHE_TIME);
    }
}
