<?php
/**
 * Created by PhpStorm.
 * User: Cascada
 * Date: 4/7/2019
 * Time: 3:37 PM
 */

namespace ContractBundle\Stripe;

use Flosch\Bundle\StripeBundle\Stripe\StripeClient as BaseStripeClient;

class StripeClient extends BaseStripeClient
{
    public function __construct($stripeApiKey = 'sk_test_VN03E8Pk9mgk4PwapZUbIOfQ')
    {
        parent::__construct($stripeApiKey);

        return $this;
    }

}