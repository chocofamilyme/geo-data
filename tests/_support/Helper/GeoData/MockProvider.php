<?php

namespace Helper\GeoData;

use Chocofamily\GeoData\Providers\AbstractGeoProvider;
use Chocofamily\GeoData\DTO\GeoDTO;

class MockProvider extends AbstractGeoProvider
{
    public function __construct($ipAddress)
    {
        $this->geoData = 'not empty';
    }

    /**
     * @return string
     */
    protected function getServiceLink(): string
    {
        return '';
    }

    /**
     * @return bool
     */
    protected function containsError(): bool
    {
        return false;
    }

    public function getDTO(): GeoDTO
    {
        return GeoDTO::fromArray([
            'country' => 'mockCountry',
            'city'    => 'mockCity',
            'region'  => 'mockRegion',
            'lat'     => 'mockLat',
            'lon'     => 'mockLon',
        ]);
    }
}
