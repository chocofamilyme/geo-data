<?php
/**
 * @package Chocolife.me
 * @author  Kamet Aziza <kamet.a@chocolife.kz>
 */

namespace Chocofamily\GeoData;

use Chocofamily\GeoData\Config\Options;
use Chocofamily\GeoData\Providers\GeoProviderInterface;
use Chocofamily\GeoData\Providers\Ip;
use Chocofamily\GeoData\Providers\Sypex;
use Phalcon\Cache\BackendInterface;
use Phalcon\Config;

class GeoDataService
{
    const PARAMETERS = [
        'country',
        'city',
        'region',
        'lat',
        'lon',
    ];

    /** @var BackendInterface */
    private $cache;

    /** @var Config */
    private $config;

    private $geoClasses = [
        Ip::class,
        Sypex::class,
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
     * @return array
     */
    public function getGeoData(string $ip, $showOnly = self::PARAMETERS): array
    {
        $key = $this->getCacheKey($ip);
        if ($geoData = $this->cache->get($key)) {
            return $geoData;
        }

        foreach ($this->geoClasses as $geoClass) {
            /** @var GeoProviderInterface $geoApi */
            $geoApi = new $geoClass($ip);

            if ($geoApi->isAvailable()) {
                $geoData = $geoApi->getData()->toArray();
                $geoData = $this->filter($geoData, $showOnly);

                $this->cache->save($key, $geoData, $this->getCacheTime());

                return $geoData;
            }
        }

        return [];
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
