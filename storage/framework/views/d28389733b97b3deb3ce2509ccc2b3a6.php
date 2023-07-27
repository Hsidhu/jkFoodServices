<?php $__currentLoopData = $optionValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['form-check', 'py-2' => !$loop->first || !$loop->last]) ?>">
        <input
            type="radio"
            id="menuOptionRadio<?php echo e($menuOptionValueId = $optionValue->menu_option_value_id); ?>"
            class="form-check-input"
            name="menu_options[<?php echo e($index); ?>][option_values][]"
            value="<?php echo e($optionValue->menu_option_value_id); ?>"
            data-option-price="<?php echo e($optionValue->price); ?>"
            <?php if(($cartItem && $cartItem->hasOptionValue($menuOptionValueId)) || $optionValue->isDefault()): ?>
            checked="checked"
            <?php endif; ?>
        >

        <label
            class="form-check-label ps-2 w-100"
            for="menuOptionRadio<?php echo e($menuOptionValueId); ?>"
        >
            <?php echo e($optionValue->name); ?>

            <?php if($optionValue->price > 0 || !$hideZeroOptionPrices): ?>
                <span class="float-end fw-light"><?php echo app('translator')->get('main::lang.text_plus'); ?><?php echo e(currency_format($optionValue->price)); ?></span>
            <?php endif; ?>
        </label>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

