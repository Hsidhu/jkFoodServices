<?php

namespace Igniter\MealPlan\Components;

use Exception;
use Igniter\MealPlan\Classes\CartManager;
use Igniter\Flame\Cart\Facades\Cart;
use Igniter\Flame\Exception\ApplicationException;
use Igniter\Local\Facades\Location;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Igniter\MealPlan\Models\MealPlan;
use Illuminate\Support\Facades\App;

class MealPlanCartBox extends \System\Classes\BaseComponent
{
    use \Igniter\Local\Traits\SearchesNearby;
    use \Main\Traits\UsesPage;
    use \Admin\Traits\HasDeliveryAreas;

    /**
     * @var \Igniter\MealPlan\Classes\CartManager
     */
    protected $cartManager;

    /**
     * @var \Igniter\Local\Classes\Location
     */
    protected $location;

    public function initialize()
    {
        $this->location = App::make('location');
        $this->cartManager = CartManager::instance();
    }

    public function defineProperties()
    {
        return [
            'showCartItemThumb' => [
                'label' => 'Show cart menu item image in the popup',
                'type' => 'switch',
                'default' => false,
                'validationRule' => 'required|boolean',
            ],
            'cartItemThumbWidth' => [
                'label' => 'Cart item image width',
                'type' => 'number',
                'span' => 'left',
                'validationRule' => 'nullable|integer',
            ],
            'cartItemThumbHeight' => [
                'label' => 'Cart item image height',
                'type' => 'number',
                'span' => 'right',
                'validationRule' => 'nullable|integer',
            ],
            'pageIsCheckout' => [
                'label' => 'Whether this component is loaded on the checkout page',
                'type' => 'switch',
                'default' => false,
                'validationRule' => 'required|boolean',
            ],
            'pageIsCart' => [
                'label' => 'Whether this component is loaded on the cart page',
                'type' => 'switch',
                'default' => false,
                'validationRule' => 'required|boolean',
            ],
            'hideZeroOptionPrices' => [
                'label' => 'Whether to hide zero prices on options',
                'type' => 'switch',
                'default' => false,
                'validationRule' => 'required|boolean',
            ],
            'checkoutPage' => [
                'label' => 'Checkout Page',
                'type' => 'select',
                'options' => [static::class, 'getThemePageOptions'],
                'default' => 'mealplancheckout'.DIRECTORY_SEPARATOR.'checkout',
                'validationRule' => 'required|regex:/^[a-z0-9\-_\/]+$/i',
            ],
            'localBoxAlias' => [
                'label' => 'Specify the LocalBox component alias used to refresh the localbox after the order type is changed',
                'type' => 'text',
                'default' => 'localBox',
                'validationRule' => 'required|regex:/^[a-z0-9\-_]+$/i',
            ],
        ];
    }

    public function onRun()
    {
        $this->addJs('js/cartbox.js', 'cart-box-js');
        $this->addJs('js/cartitem.js', 'cart-item-js');
        $this->addJs('js/cartbox.modal.js', 'cart-box-modal-js');
        $this->addJs('js/local.js', 'local-module-js');

        // Make the mapview assets available

        // from Admin\FormWidgets 
        if (strlen($key = setting('maps_api_key'))) {
            $url = 'https://maps.googleapis.com/maps/api/js?key=%s&libraries=geometry,places&callback=Function.prototype';
            $this->addJs(sprintf($url, $key),
                ['name' => 'google-maps-js', 'async' => null, 'defer' => null]
            );
        }

        $this->addJs('js/googleAutoFill.js', 'google-auto-file-js');
        // https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&callback=initAutocomplete&libraries=places&v=weekly"

        $this->updateCurrentOrderType();
        $this->prepareVars();
    }

    protected function prepareVars()
    {
        $this->page['showCartItemThumb'] = $this->property('showCartItemThumb', false);
        $this->page['cartItemThumbWidth'] = $this->property('cartItemThumbWidth');
        $this->page['cartItemThumbHeight'] = $this->property('cartItemThumbHeight');
        $this->page['pageIsCart'] = $this->property('pageIsCart');
        $this->page['pageIsCheckout'] = $this->property('pageIsCheckout');
        $this->page['hideZeroOptionPrices'] = (bool)$this->property('hideZeroOptionPrices');

        $this->page['checkoutEventHandler'] = $this->getEventHandler('onProceedToCheckout');
        $this->page['updateCartItemEventHandler'] = $this->getEventHandler('onUpdateCart');
        $this->page['updateCartItemQtyEventHandler'] = $this->getEventHandler('onUpdateItemQuantity');

        $this->page['loadCartItemEventHandler'] = $this->getEventHandler('onLoadItemPopup');
        $this->page['removeCartItemEventHandler'] = $this->getEventHandler('onUpdateItemQuantity');
        $this->page['removeConditionEventHandler'] = $this->getEventHandler('onRemoveCondition');
        $this->page['refreshCartEventHandler'] = $this->getEventHandler('onRefresh');

        $this->page['applyCouponEventHandler'] = $this->getEventHandler('onApplyCoupon');
        $this->page['applyTipEventHandler'] = $this->getEventHandler('onApplyTip');

        // location variables
        $this->page['location'] = $this->location;
        $this->page['locationCurrent'] = $this->location->current();

        // select order
        $this->page['orderTypeEventHandler'] = $this->getEventHandler('onChangeOrderType');
        $this->page['locationOrderTypes'] = $this->location->getOrderTypes();
        $this->page['currentOrderType'] = Location::orderType();

        // search delivery area
        $this->page['searchEventHandler'] = $this->getEventHandler('onSearchNearby');
        $this->page['pickerEventHandler'] = $this->getEventHandler('onSetSavedAddress');

        $this->page['searchQueryPosition'] = Location::instance()->userPosition();
        $this->page['searchDefaultAddress'] = $this->updateNearbyAreaFromSavedAddress(
            $this->getDefaultAddress()
        );

        $this->page['cart'] = $this->cartManager->getCart();

        // need to add address to cart
        // Location::getSession('searchQuery')
    }

    public function fetchPartials()
    {
        $this->prepareVars();

        return [
            '#cart-items' => $this->renderPartial('@items'),
            //'#cart-coupon' => $this->renderPartial('@coupon_form'),
            //'#cart-tip' => $this->renderPartial('@tip_form'),
            '#delivery-charge' => $this->renderPartial('@delivery_charge'),
            '#cart-totals' => $this->renderPartial('@totals'),
            '#cart-buttons' => $this->renderPartial('@buttons'),
            '[data-cart-total]' => currency_format(Cart::total()),
            '[data-cart-count]' => Cart::count(),
            '#notification' => $this->renderPartial('flash'),
        ];
    }

    public function onRefresh()
    {
        return $this->fetchPartials();
    }

    // Load meal add ons in popup
    public function onLoadItemPopup()
    {
        $menuItem = MealPlan::find(post('menuId'));

        $cartItem = null;
        if (strlen($rowId = post('rowId'))) {
            $cartItem = $this->cartManager->getCartItem($rowId);
            $menuItem = $cartItem->model;
        }

        $this->controller->pageCycle();

        return $this->renderPartial('@item_modal', [
            'formHandler' => $this->getEventHandler('onUpdateCart'),
            'cartItem' => $cartItem,
            'menuItem' => $menuItem,
        ]);
    }

    // update Item in cart
    public function onUpdateCart()
    {
        try {
            $postData = post();

            $this->cartManager->addOrUpdateCartItem($postData);

            $this->controller->pageCycle();

            return $this->fetchPartials();
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->alert($ex->getMessage());
        }
    }

    // update quantity
    public function onUpdateItemQuantity()
    {
        try {
            $action = (string)post('action');
            $rowId = (string)post('rowId');
            $quantity = (int)post('quantity');

            $this->cartManager->updateCartItemQty($rowId, $action ?: $quantity);

            $this->controller->pageCycle();

            return $this->fetchPartials();
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->alert($ex->getMessage());
        }
    }

    public function onRemoveItem()
    {
        return $this->onUpdateItemQuantity();
    }

    public function onRemoveCondition()
    {
        try {
            if (!strlen($conditionId = post('conditionId')))
                return;

            $this->cartManager->removeCondition($conditionId);
            $this->controller->pageCycle();

            return $this->fetchPartials();
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->alert($ex->getMessage());
        }
    }

    // Move to checkout page
    public function onProceedToCheckout()
    {
        try {
            if (!is_numeric($id = post('locationId')) || !($location = Location::getById($id)) || !$location->location_status)
                throw new ApplicationException(lang('igniter.local::default.alert_location_required'));
                
            if(Location::orderTypeIsDelivery() && empty($this->location->getSession('area')))
                throw new ApplicationException("Please enter delivery address!");

            Location::setCurrent($location);

            // move to checkout page
            $redirectUrl = $this->controller->pageUrl($this->property('checkoutPage'));

            return Redirect::to($redirectUrl);
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->alert($ex->getMessage());
        }
    }

    // Checkout button status
    public function buttonLabel($checkoutComponent = null)
    {   
        if($this->location->getOrderType()->getCode() == 'delivery' && empty(Location::coveredArea()->area_id)){
           // return "Address required for delivery option";
        }
        
        if (!$this->property('pageIsCheckout') && $this->cartManager->getCart()->count())
            return lang('igniter.cart::default.button_order').' · '.currency_format($this->cartManager->getCart()->total());

        if (!$this->property('pageIsCheckout'))
            return lang('igniter.cart::default.button_order');

        if ($checkoutComponent && !$checkoutComponent->canConfirmCheckout())
            return lang('igniter.cart::default.button_payment');

        return lang('igniter.cart::default.button_confirm');
    }

    public function getLocationId()
    {
        return Location::instance()->getId();
    }

    protected function updateCurrentOrderType()
    {
        if (!$this->location->current())
            return;

        $sessionOrderType = $this->location->getSession('orderType');
        if ($sessionOrderType && $this->location->hasOrderType($sessionOrderType))
            return;

        $defaultOrderType = $this->property('defaultOrderType');
        if (!$this->location->hasOrderType($defaultOrderType)) {
            $defaultOrderType = optional($this->location->getOrderTypes()->first(function ($orderType) {
                return !$orderType->isDisabled();
            }))->getCode();
        }

        if ($defaultOrderType)
            $this->location->updateOrderType($defaultOrderType);
    }

    public function onChangeOrderType()
    {
        try {
            if (!$this->location->current())
                throw new ApplicationException(lang('igniter.local::default.alert_location_required'));

            $orderType = $this->location->getOrderType(post('type'));
            if ($orderType->isDisabled())
                throw new ApplicationException($orderType->getDisabledDescription());

            $this->location->updateOrderType($orderType->getCode());

            $this->controller->pageCycle();

            return ($redirectUrl = input('redirect'))
                ? Redirect::to($this->controller->pageUrl($redirectUrl))
                : Redirect::back();
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else flash()->danger($ex->getMessage())->now();
        }
    }

    protected function updateNearbyAreaFromSavedAddress($address)
    {
        if (!$address instanceof Addresses_model)
            return $address;

        $searchQuery = format_address($address->toArray(), false);
        if ($searchQuery == Location::getSession('searchQuery'))
            return $address;

        try {
            $userLocation = $this->geocodeSearchQuery($searchQuery);

            Location::searchByCoordinates($userLocation->getCoordinates())
                ->first(function ($location) use ($userLocation) {
                    if ($area = $location->searchDeliveryArea($userLocation->getCoordinates())) {
                        Location::updateNearbyArea($area);

                        return $area;
                    }
                });
        }
        catch (Exception $ex) {
        }

        return $address;
    }

    public function showAddressPicker()
    {
        return $this->getDefaultAddress();
    }

    protected function getDefaultAddress()
    {
        return null;
        if (!is_null($this->defaultAddress))
            return $this->defaultAddress;

        return $this->defaultAddress = optional(Auth::customer())->address
            ?? optional($this->getSavedAddresses())->first();
    }

    public function onSetSavedAddress()
    {
        if (!$customer = Auth::customer())
            return null;

        if (!is_numeric($addressId = post('addressId')))
            throw new ApplicationException(lang('igniter.local::default.alert_address_id_required'));

        if (!$address = $customer->addresses()->find($addressId))
            throw new ApplicationException(lang('igniter.local::default.alert_address_not_found'));

        Customers_model::withoutEvents(function () use ($customer, $address) {
            $customer->address_id = $address->address_id;
            $customer->save();
        });

        $customer->reload();
        $this->controller->pageCycle();

        $this->prepareVars();

        return redirect()->back();
    }

    public function showDeliveryCoverageAlert()
    {
        if (!Location::orderTypeIsDelivery())
            return false;

        if (!Location::requiresUserPosition())
            return false;

        return Location::userPosition()->hasCoordinates()
            && !Location::checkDeliveryCoverage();
    }


    // if location is closed
    public function locationIsClosed()
    {
        return !Location::instance()->checkOrderTime() || Location::instance()->checkNoOrderTypeAvailable();
    }

    public function hasMinimumOrder()
    {
        return $this->cartManager->cartTotalIsBelowMinimumOrder()
            || $this->cartManager->deliveryChargeIsUnavailable();
    }

   
}
