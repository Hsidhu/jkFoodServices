<button
    class="btn btn-light btn-sm btn-cart"
        @if ($menuItemObject->hasOptions)
            data-cart-control="load-item"
        @else
            data-request="{{ $updateCartItemEventHandler }}"
            data-request-data="menuId: '{{ $menuItem->id }}', quantity: '1'"
            data-replace-loading="fa fa-spinner fa-spin"
        @endif
    data-menu-id="{{ $menuItem->id }}"
    data-quantity="1"
>
    <i @class(['fa fa-plus'])></i>
</button>
