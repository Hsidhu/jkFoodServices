
<?php $__currentLoopData = $menuItem->mealplan_addon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $mealPlanAddon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <div
        class="menu-option"
        data-control="item-option"
        data-option-type="<?php echo e($mealPlanAddon->meal_plan_option->display_type); ?>"
        data-option-minimum="<?php echo e(1); ?>"
        data-option-maximum="<?php echo e(8); ?>"
    >
        <div class="option option-<?php echo e($mealPlanAddon->meal_plan_option->display_type); ?>">
            <div class="option-details">
                <h5 class="mb-0">
                    <?php echo e($mealPlanAddon->meal_plan_option->name); ?>

                    <?php if( isset($mealPlanAddon->meal_plan_option->required ) && $mealPlanAddon->meal_plan_option->required == 1): ?>
                        <span
                            class="small pull-right text-muted"><?php echo app('translator')->get('igniter.cart::default.text_required'); ?></span>
                    <?php endif; ?>
                </h5>
                <?php if(1 > 0 || 8 > 0): ?>
                    <p class="mb-0"><?php echo sprintf(lang('igniter.cart::default.text_option_summary'), $mealPlanAddon->meal_plan_option->min_selected, $mealPlanAddon->meal_plan_option->max_selected); ?></p>
                <?php endif; ?>
            </div>

            <?php if(count($optionValues = $mealPlanAddon->meal_plan_option->meal_plan_option_values)): ?>
                <input
                    type="hidden"
                    name="menu_options[<?php echo e($index); ?>][meal_plan_option_id]"
                    value="<?php echo e($mealPlanAddon->meal_plan_option->id); ?>"
                />
                <div class="option-group">
                    <?php echo controller()->renderPartial('@item_option_'.$mealPlanAddon->meal_plan_option->display_type, [
                        'index' => $index,
                        'cartItem' => $cartItem,
                        'optionValues' => $optionValues->sortBy('value'),
                    ]); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

