
<div
    data-control="cart-box"
    data-load-item-handler="{{ $loadCartItemEventHandler }}"
    data-update-item-handler="{{ $updateCartItemEventHandler }}"
    data-apply-coupon-handler="{{ $applyCouponEventHandler }}"
    data-apply-tip-handler="{{ $applyTipEventHandler }}"
    data-remove-item-handler="{{ $removeCartItemEventHandler }}"
    data-remove-condition-handler="{{ $removeConditionEventHandler }}"
    data-refresh-cart-handler="{{ $refreshCartEventHandler }}"
>
    <div id="cart-box" class="module-box">
        <div id="cart-items">
            @partial('@items')
        </div>

        <div id="cart-tip">
            <!-- @component('localSearch') -->
            @partial('@delivery_charge')
        </div>

        <div id="cart-totals">
            @partial('@totals')
        </div>

        <div id="cart-buttons" class="mt-3">
            @partial('@buttons')
        </div>
    </div>
</div>