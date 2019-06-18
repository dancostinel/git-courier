<?php

namespace CourierBundle\DeliveryValidator;

use DeliveryBundle\Entity\Delivery;

class FANCourierValidator
{
    public function supportsDelivery(Delivery $delivery)
    {
        foreach ($delivery->products as $product) {
            Validator::dry($product);

            Validator::maxWeight($product);

            Validator::maxLength($product);
        }

        Validator::volume();
    }
}
