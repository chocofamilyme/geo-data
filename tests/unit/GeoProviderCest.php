<?php

namespace unit;

use Chocofamily\GeoData\Providers\IpApi;
use Chocofamily\GeoData\Providers\SypexGeo;

class GeoProviderCest
{
    /**
     * @dataProvider geoApiProvider
     *
     * @param \UnitTester $I
     *
     * @throws \JsonException
     */
    public function tryToGetData(\UnitTester $I, \Codeception\Example $data)
    {
        $I->wantTo($data['message']);

        $ip    = '185.97.113.204';
        $geoProvider = new $data['className']($ip);

        $result = $geoProvider->getDTO()->toArray();
        $I->assertArraySubset([
            'country' => 'Kazakhstan',
            'city'    => 'Almaty',
            'region'  => 'ALA',
        ], $result);
    }

    protected function geoApiProvider()
    {
        return [
            [
                'message' => 'Получить гео данные с IpApi',
                'className' => IpApi::class,
            ],
            [
                'message' => 'Получить гео данные с SypexGeo',
                'className' => SypexGeo::class,
            ],
        ];
    }

}
