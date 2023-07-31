@php 
    $locationIsClosed = ($__SELF__->locationIsClosed() || $__SELF__->hasMinimumOrder());
@endphp
<button
    class="checkout-btn btn btn-primary btn-block btn-lg {{ ($cart->count() <= 0) ? 'disabled' : '' }} "
    data-attach-loading="disabled"
    @if ($pageIsCheckout && $cart->count() >= 1)
    data-checkout-control="confirm-checkout"
    data-request-form="#checkout-form"
    @elseif ($cart->count() >= 1)
    data-request="{{ $checkoutEventHandler }}"
    data-request-data="locationId: '{{ $__SELF__->getLocationId() }}'"
    @endif
>{{ $__SELF__->buttonLabel($checkout ?? null) }}</button>
