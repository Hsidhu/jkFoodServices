<?php

namespace Igniter\MealPlan\CartConditions;

use Admin\Models\Locations_model;
use Igniter\Flame\Cart\CartCondition;
use Igniter\Flame\Cart\Facades\Cart;
use Igniter\Local\Facades\Location;

class Delivery extends CartCondition
{
    public $priority = 100;

    protected $deliveryCharge = 0;

    protected $minimumOrder = 0;

    public function beforeApply()
    {
        $cartSubtotal = Cart::subtotal();
        $this->deliveryCharge = Location::coveredArea()->deliveryAmount($cartSubtotal);
    }

    public function getRules()
    {
        return [
            "{$this->deliveryCharge} >= 0",
        ];
    }

    public function getActions()
    {
        return [
            ['value' => "+{$this->deliveryCharge}"],
        ];
    }

    public function getValue()
    {
        return $this->calculatedValue > 0 ? $this->calculatedValue : lang('main::lang.text_free');
    }
}
