<?php

namespace unit;

use Chocofamily\GeoData\Config\Options;
use Chocofamily\GeoData\GeoDataService;
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
        $this->config    = new Config();
    }

    /**
     * @param \UnitTester  $I
     * @param \Helper\Unit $helper
     *
     * @throws \ReflectionException
     */
    public function tryToGetGeoData(\UnitTester $I, \Helper\Unit $helper)
    {
        $geoDataService = new GeoDataService($this->config, $this->cache);
        $helper->invokeProperty($geoDataService, 'geoClasses', [
            MockProvider::class,
        ]);

        $geoData = $geoDataService->getGeoData($this->ipAddress);
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
        $helper->invokeProperty($geoDataService, 'geoClasses', [
            NullProvider::class,
            EmptyProvider::class,
        ]);

        $geoData = $geoDataService->getGeoData($this->ipAddress);
        $I->assertEmpty($geoData);
    }

    public function tryToFilterData(\UnitTester $I, \Helper\Unit $helper)
    {
        $geoDataService = new GeoDataService($this->config, $this->cache);
        $helper->invokeProperty($geoDataService, 'geoClasses', [
            MockProvider::class,
        ]);

        $geoData = $geoDataService->getGeoData($this->ipAddress, ['country', 'city']);
        $I->assertEquals([
            'country' => 'mockCountry',
            'city'    => 'mockCity',
        ], $geoData);
    }

    public function tryToGetGeoDataFromCache(\UnitTester $I, \Helper\Unit $helper)
    {
        $cacheData = [
            'country' => 'cacheCountry',
            'city'    => 'cacheCity',
            'region'  => 'cacheRegion',
            'lat'     => 'cacheLat',
            'lon'     => 'cacheLon',
        ];

        $this->cache->save(Options::PREFIX_CACHE_KEY.$this->ipAddress, $cacheData);

        $geoDataService = new GeoDataService($this->config, $this->cache);
        $helper->invokeProperty($geoDataService, 'geoClasses', [
            MockProvider::class,
        ]);
        $geoData = $geoDataService->getGeoData($this->ipAddress);

        $I->assertEquals($cacheData, $geoData);
    }
}
