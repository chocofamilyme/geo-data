<?php

namespace Chocofamily\GeoData\Providers;

use Chocofamily\GeoData\DTO\GeoDTO;

class SypexGeo extends AbstractGeoProvider
{
    /**
     * @return string
     */
    protected function getServiceLink(): string
    {
        return 'http://api.sypexgeo.net/json/';
    }

    /**
     * @return bool
     */
    protected function containsError(): bool
    {
        return empty($this->geoData['country'])
            && empty($this->geoData['city'])
            && empty($this->geoData['region']);
    }

    /**
     * @return GeoDTO
     */
    public function getDTO(): GeoDTO
    {
        return GeoDTO::fromArray([
            'country' => $this->geoData['country']['name_en'],
            'city'    => $this->geoData['city']['name_en'],
            'region'  => $this->getRegion(),
            'lat'     => $this->geoData['city']['lat'],
            'lon'     => $this->geoData['city']['lon'],
        ]);
    }

    /**
     * @return null|string
     */
    public function getRegion(): ?string
    {
        if (isset($this->geoData['region']['iso'])) {
            return $this->regionInitialsFiltering($this->geoData['region']['iso']);
        }

        return null;
    }

    /**
     * @param string $regionInitials
     *
     * @return null|string
     */
    private function regionInitialsFiltering(string $regionInitials): ?string
    {
        if (strpos($regionInitials, '-') !== false) {
            $regionInitialsArray = explode('-', $regionInitials);
            $lastInitials        = end($regionInitialsArray);

            return $lastInitials;
        }

        return $regionInitials;
    }
}
