<?php
/**
 * @package Chocolife.me
 * @author  Kamet Aziza <kamet.a@chocolife.kz>
 */

namespace Chocofamily\GeoData\Providers;

use Chocofamily\GeoData\DTO\GeoDTO;

interface GeoProviderInterface
{
    /**
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * @return GeoDTO
     */
    public function getData(): GeoDTO;
}
