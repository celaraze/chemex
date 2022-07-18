<?php

namespace Faker\Provider;

class Address extends Base
{
    protected static $citySuffix = ['Ville'];
    protected static $streetSuffix = ['Street'];
    protected static $cityFormats = [
        '{{firstName}}{{citySuffix}}',
    ];
    protected static $streetNameFormats = [
        '{{lastName}} {{streetSuffix}}',
    ];
    protected static $streetAddressFormats = [
        '{{buildingNumber}} {{streetName}}',
    ];
    protected static $addressFormats = [
        '{{streetAddress}} {{postcode}} {{city}}',
    ];

    protected static $buildingNumber = ['%#'];
    protected static $postcode = ['#####'];
    protected static $country = [];

    /**
     * @return string
     * @example 'town'
     *
     */
    public static function citySuffix()
    {
        return static::randomElement(static::$citySuffix);
    }

    /**
     * @return string
     * @example 'Avenue'
     *
     */
    public static function streetSuffix()
    {
        return static::randomElement(static::$streetSuffix);
    }

    /**
     * @return string
     * @example '791'
     *
     */
    public static function buildingNumber()
    {
        return static::numerify(static::randomElement(static::$buildingNumber));
    }

    /**
     * @return string
     * @example 86039-9874
     *
     */
    public static function postcode()
    {
        return static::toUpper(static::bothify(static::randomElement(static::$postcode)));
    }

    /**
     * @return string
     * @example 'Japan'
     *
     */
    public static function country()
    {
        return static::randomElement(static::$country);
    }

    /**
     * @return float[]
     * @example array('77.147489', '86.211205')
     *
     */
    public static function localCoordinates()
    {
        return [
            'latitude' => static::latitude(),
            'longitude' => static::longitude(),
        ];
    }

    /**
     * Uses signed degrees format (returns a float number between -90 and 90)
     *
     * @param float|int $min
     * @param float|int $max
     *
     * @return float
     * @example '77.147489'
     *
     */
    public static function latitude($min = -90, $max = 90)
    {
        return static::randomFloat(6, $min, $max);
    }

    /**
     * Uses signed degrees format (returns a float number between -180 and 180)
     *
     * @param float|int $min
     * @param float|int $max
     *
     * @return float
     * @example '86.211205'
     *
     */
    public static function longitude($min = -180, $max = 180)
    {
        return static::randomFloat(6, $min, $max);
    }

    /**
     * @return string
     * @example 'Sashabury'
     *
     */
    public function city()
    {
        $format = static::randomElement(static::$cityFormats);

        return $this->generator->parse($format);
    }

    /**
     * @return string
     * @example 'Crist Parks'
     *
     */
    public function streetName()
    {
        $format = static::randomElement(static::$streetNameFormats);

        return $this->generator->parse($format);
    }

    /**
     * @return string
     * @example '791 Crist Parks'
     *
     */
    public function streetAddress()
    {
        $format = static::randomElement(static::$streetAddressFormats);

        return $this->generator->parse($format);
    }

    /**
     * @return string
     * @example '791 Crist Parks, Sashabury, IL 86039-9874'
     *
     */
    public function address()
    {
        $format = static::randomElement(static::$addressFormats);

        return $this->generator->parse($format);
    }
}
