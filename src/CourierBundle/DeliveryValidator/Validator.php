<?php

namespace CourierBundle\DeliveryValidator;

use CommonBundle\Entity\Product\Product;
use CourierBundle\DeliveryValidator\Exception\DeliveryNotSupported;

class Validator
{
    private const MAX_WEIGHT = 40000;
    private const MAX_PRODUCT_LENGTH = 2000;
    private const MAX_DELIVERY_VOLUME = 1000000000;

    private static $maxLength = 0;
    private static $maxWidth = 0;
    private static $maxHeight = 0;

    public static function dry(Product $product): void
    {
        if ($product->type !== Product::DRY) {
            throw new DeliveryNotSupported();
        }
    }

    public static function maxWeight(Product $product): void
    {
        if ($product->weight > self::MAX_WEIGHT) {
            throw new DeliveryNotSupported();
        }
    }

    public static function maxLength(Product $product): void
    {
        $dimmensions = [$product->length, $product->width, $product->height];
        rsort($dimmensions);
        list($length, $width, $height) = $dimmensions;
        if ($length > self::MAX_PRODUCT_LENGTH) {
            throw new DeliveryNotSupported();
        }

        static::$maxLength = max(static::$maxLength, $length);
        static::$maxWidth = max(static::$maxWidth, $width);
        static::$maxHeight += $height;
    }

    public static function volume(): void
    {
        $totalVolume = static::$maxLength*static::$maxWidth*static::$maxHeight;
        if ($totalVolume > self::MAX_DELIVERY_VOLUME) {
            throw new DeliveryNotSupported();
        }
    }
}