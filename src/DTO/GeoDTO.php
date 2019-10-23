<?php
/**
 * @package Chocolife.me
 * @author  Kamet Aziza <kamet.a@chocolife.kz>
 */

namespace Chocofamily\GeoData\DTO;

final class GeoDTO
{
    /** @var string */
    public $country;

    /** @var string */
    public $city;

    /** @var string */
    public $region;

    /** @var string */
    public $lat;

    /** @var string */
    public $lon;

    /**
     * @param array $array
     *
     * @return GeoDTO
     */
    public static function fromArray(array $array): self
    {
        $self          = new self();
        $self->country = $array['country'] ?? null;
        $self->city    = $array['city'] ?? null;
        $self->region  = $array['region'] ?? null;
        $self->lat     = $array['lat'] ?? null;
        $self->lon     = $array['lon'] ?? null;

        return $self;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return (array) $this;
    }
}
