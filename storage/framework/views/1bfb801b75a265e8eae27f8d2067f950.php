<?php $__currentLoopData = $menuItem->menu_options->sortBy('priority'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $menuOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div
        class="menu-option"
        data-control="item-option"
        data-option-type="<?php echo e($menuOption->display_type); ?>"
        data-option-minimum="<?php echo e($menuOption->min_selected); ?>"
        data-option-maximum="<?php echo e($menuOption->max_selected); ?>"
    >
        <div class="option option-<?php echo e($menuOption->display_type); ?>">
            <div class="option-details">
                <h5 class="mb-0">
                    <?php echo e($menuOption->option_name); ?>

                    <?php if($menuOption->required == 1): ?>
                        <span
                            class="small pull-right text-muted"><?php echo app('translator')->get('igniter.cart::default.text_required'); ?></span>
                    <?php endif; ?>
                </h5>
                <?php if($menuOption->min_selected > 0 || $menuOption->max_selected > 0): ?>
                    <p class="mb-0"><?php echo sprintf(lang('igniter.cart::default.text_option_summary'), $menuOption->min_selected, $menuOption->max_selected); ?></p>
                <?php endif; ?>
            </div>

            <?php if(count($optionValues = $menuOption->menu_option_values)): ?>
                <input
                    type="hidden"
                    name="menu_options[<?php echo e($index); ?>][menu_option_id]"
                    value="<?php echo e($menuOption->menu_option_id); ?>"
                />
                <div class="option-group">
                    <?php echo controller()->renderPartial('@item_option_'.$menuOption->display_type, [
                        'index' => $index,
                        'cartItem' => $cartItem,
                        'optionValues' => $optionValues->sortBy('priority'),
                    ]); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

