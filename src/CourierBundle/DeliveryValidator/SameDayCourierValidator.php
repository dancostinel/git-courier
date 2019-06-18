<?php

namespace CourierBundle\DeliveryValidator;

use CourierBundle\DeliveryValidator\Exception\DeliveryNotSupported;
use DeliveryBundle\Entity\Delivery;

class SameDayCourierValidator
{
    /**
     * @param Delivery $delivery
     * @param ProductValidator $productValidator
     * @throws DeliveryNotSupported
     */
    public function supportsDelivery(Delivery $delivery, ProductValidator $productValidator)
    {
        $maxLength = 0;
        $maxWidth = 0;
        $maxHeight = 0;

        foreach ($delivery->products as $product) {
            $productValidator->hasValidType($product);
            $productValidator->hasValidWeight($product);
            $productValidator->hasValidDimensions($product);

            $maxLength = max($maxLength, $product->length);
            $maxWidth = max($maxWidth, $product->width);
            $maxHeight += $product->height;
        }

        $productValidator->hasValidVolume($maxLength, $maxWidth, $maxHeight);
    }
}
