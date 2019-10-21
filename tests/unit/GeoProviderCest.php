<?php

namespace unit;

use Chocofamily\GeoData\Providers\Ip;
use Chocofamily\GeoData\Providers\Sypex;

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

        $result = $geoProvider->getData()->toArray();
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
                'className' => Ip::class,
            ],
            [
                'message' => 'Получить гео данные с SypexGeoApi',
                'className' => Sypex::class,
            ],
        ];
    }

}
