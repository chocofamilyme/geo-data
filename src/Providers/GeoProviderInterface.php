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
     * @param string $ipAddress
     *
     * @return bool
     */
    public function requestData(string $ipAddress): bool;

    /**
     * @return GeoDTO
     */
    public function getDTO(): GeoDTO;
}
