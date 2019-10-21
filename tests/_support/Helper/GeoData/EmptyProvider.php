<?php

namespace Helper\GeoData;

use Chocofamily\GeoData\Providers\AbstractGeoProvider;
use Chocofamily\GeoData\DTO\GeoDTO;

class EmptyProvider extends AbstractGeoProvider
{
    public function __construct($ipAddress)
    {
        $this->geoData = '';
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

    /**
     * @return GeoDTO
     */
    public function getData(): GeoDTO
    {
        return GeoDTO::fromArray([]);
    }
}
