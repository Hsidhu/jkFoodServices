<button
    class="btn btn-light btn-sm btn-cart"
        <?php if($menuItemObject->hasOptions): ?>
            data-cart-control="load-item"
        <?php else: ?>
            data-request="<?php echo e($updateCartItemEventHandler); ?>"
            data-request-data="menuId: '<?php echo e($menuItem->id); ?>', quantity: '1'"
            data-replace-loading="fa fa-spinner fa-spin"
        <?php endif; ?>
    data-menu-id="<?php echo e($menuItem->id); ?>"
    data-quantity="1"
>
    <i class="<?php echo \Illuminate\Support\Arr::toCssClasses(['fa fa-plus']) ?>"></i>
</button>

