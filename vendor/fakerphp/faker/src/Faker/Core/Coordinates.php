<?php

declare(strict_types=1);

namespace Faker\Core;

use Faker\Extension\Extension;

class Coordinates implements Extension
{
    /**
     * @return array{latitude: float, longitude: float}
     * @example array('77.147489', '86.211205')
     *
     */
    public function localCoordinates(): array
    {
        return [
            'latitude' => static::latitude(),
            'longitude' => static::longitude(),
        ];
    }

    /**
     * @return float Uses signed degrees format (returns a float number between -90 and 90)
     * @example '77.147489'
     *
     */
    public function latitude(float $min = -90.0, float $max = 90.0): float
    {
        if ($min < -90 || $max < -90) {
            throw new \LogicException('Latitude cannot be less that -90.0');
        }

        if ($min > 90 || $max > 90) {
            throw new \LogicException('Latitude cannot be greater that 90.0');
        }

        return $this->randomFloat(6, $min, $max);
    }

    private function randomFloat(int $nbMaxDecimals, float $min, float $max): float
    {
        if ($min > $max) {
            throw new \LogicException('Invalid coordinates boundaries');
        }

        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), $nbMaxDecimals);
    }

    /**
     * @return float Uses signed degrees format (returns a float number between -180 and 180)
     * @example '86.211205'
     *
     */
    public function longitude(float $min = -180.0, float $max = 180.0): float
    {
        if ($min < -180 || $max < -180) {
            throw new \LogicException('Longitude cannot be less that -180.0');
        }

        if ($min > 180 || $max > 180) {
            throw new \LogicException('Longitude cannot be greater that 180.0');
        }

        return $this->randomFloat(6, $min, $max);
    }
}
