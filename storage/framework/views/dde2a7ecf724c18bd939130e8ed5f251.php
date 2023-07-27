<div id="menu<?php echo e($menuItem->menu_id); ?>" class="menu-item">
    <div class="d-flex flex-row">
        <?php if($showMenuImages == 1 && $menuItemObject->hasThumb): ?>
            <div
                class="col-3 p-0 me-3 menu-item-image align-self-center"
                style="
                    background: url('<?php echo e($menuItem->getThumb()); ?>') no-repeat center center;
                    background-size: cover;
                    width: <?php echo e($menuImageWidth); ?>px;
                    height: <?php echo e($menuImageHeight); ?>px;
                    ">
            </div>
        <?php endif; ?>

        <div class="menu-content flex-grow-1 me-3">
            <h6 class="menu-name"><?php echo e($menuItem->name); ?></h6>
            <p class="menu-desc text-muted mb-0">
                <?php echo nl2br($menuItem->description); ?>

            </p>
        </div>
        <div class="menu-detail d-flex justify-content-end col-3 p-0">

            <div class="menu-price pe-3">
                <b><?php echo currency_format($menuItem->price); ?></b>
            </div>

            <?php if(isset($updateCartItemEventHandler)): ?>
                <div class="menu-button">
                    <?php echo controller()->renderPartial('@button', ['menuItem' => $menuItem, 'menuItemObject' => $menuItemObject ]); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
</div>

