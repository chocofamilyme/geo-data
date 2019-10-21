<?php
/**
 * @package Chocolife.me
 * @author  Kamet Aziza <kamet.a@chocolife.kz>
 */

namespace Chocofamily\GeoData\Providers;

use Chocofamily\GeoData\DTO\GeoDTO;

class Ip extends AbstractGeoProvider
{
    /**
     * @return string
     */
    protected function getServiceLink(): string
    {
        return 'http://ip-api.com/json/';
    }

    /**
     * @return bool
     */
    protected function containsError(): bool
    {
        return $this->geoData['status'] === 'fail';
    }

    /**
     * @return GeoDTO
     */
    public function getData(): GeoDTO
    {
        return GeoDTO::fromArray($this->geoData);
    }
}
