<?php

namespace unit;

use Chocofamily\GeoData\Config\Options;
use Chocofamily\GeoData\DTO\GeoDTO;
use Chocofamily\GeoData\GeoDataService;
use GuzzleHttp\Client;
use Helper\CacheMock;
use Helper\GeoData\EmptyProvider;
use Helper\GeoData\MockProvider;
use Helper\GeoData\NullProvider;
use Phalcon\Config;

class GeoDataServiceCest
{
    private $ipAddress;
    private $config;
    private $cache;

    public function _before()
    {
        $this->ipAddress = 'ipAddress';
        $this->cache     = new CacheMock();
        $this->config    = [];
    }

    /**
     * @param \UnitTester  $I
     * @param \Helper\Unit $helper
     *
     * @throws \ReflectionException
     */
    public function tryToGetGeoData(\UnitTester $I, \Helper\Unit $helper)
    {
        $geoDataService = new GeoDataService($this->config, $this->cache, new Client());
        $helper->invokeProperty($geoDataService, 'geoProviders', [
            MockProvider::class,
        ]);

        $geoData = $geoDataService->getGeoDTO($this->ipAddress)->toArray();
        $I->assertEquals([
            'country' => 'mockCountry',
            'city'    => 'mockCity',
            'region'  => 'mockRegion',
            'lat'     => 'mockLat',
            'lon'     => 'mockLon',
        ], $geoData);
    }

    public function tryToPassGeoDataIfServiceIsNotAvailable(\UnitTester $I, \Helper\Unit $helper)
    {
        $geoDataService = new GeoDataService($this->config, $this->cache);
        $helper->invokeProperty($geoDataService, 'geoProviders', [
            NullProvider::class,
            EmptyProvider::class,
        ]);

        $geoData = $geoDataService->getGeoDTO($this->ipAddress);
        $I->assertNull($geoData);
    }

    public function tryToGetGeoDataFromCache(\UnitTester $I, \Helper\Unit $helper)
    {
        $cacheData = GeoDTO::fromArray([
            'country' => 'cacheCountry',
            'city'    => 'cacheCity',
            'region'  => 'cacheRegion',
            'lat'     => 'cacheLat',
            'lon'     => 'cacheLon',
         ]);

        $this->cache->save(Options::CACHE_KEY_PREFIX.$this->ipAddress, serialize($cacheData));

        $geoDataService = new GeoDataService($this->config, $this->cache);
        $helper->invokeProperty($geoDataService, 'geoProviders', [
            MockProvider::class,
        ]);
        $geoData = $geoDataService->getGeoDTO($this->ipAddress);

        $I->assertEquals($cacheData, $geoData);
    }
}
