<?php
   // dd($item);
   //exit;
   $item = $item->meal_plan_option;
?>
<div class="d-flex align-items-center">
    <div class="px-2">
        <?php if($item->display_type == 'radio'): ?>
            <i
                title="<?php echo e(sprintf(lang('admin::lang.menu_options.text_option_summary'), $item->display_type)); ?>"
                class="fa fa-dot-circle text-muted"
            ></i>
        <?php elseif($item->display_type == 'checkbox'): ?>
            <i
                title="<?php echo e(sprintf(lang('admin::lang.menu_options.text_option_summary'), $item->display_type)); ?>"
                class="fa fa-check-square text-muted"
            ></i>
        <?php elseif($item->display_type == 'select'): ?>
            <i
                title="<?php echo e(sprintf(lang('admin::lang.menu_options.text_option_summary'), $item->display_type)); ?>"
                class="fa fa-caret-square-down text-muted"
            ></i>
        <?php elseif($item->display_type == 'quantity'): ?>
            <i
                title="<?php echo e(sprintf(lang('admin::lang.menu_options.text_option_summary'), $item->display_type)); ?>"
                class="fa fa-plus-square text-muted"
            ></i>
        <?php else: ?>
            <?php echo e(sprintf(lang('admin::lang.menu_options.text_option_summary'), $item->display_type)); ?>

        <?php endif; ?>
    </div>
    <div class="px-2">
        <p class="card-title font-weight-bold mb-1"><?php echo e($item->name); ?></p>
        <?php $__currentLoopData = $item->meal_plan_option_values->sortBy('priority')->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuOptionValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span
                class="<?php echo \Illuminate\Support\Arr::toCssClasses(['badge border', 'bg-secondary border-secondary text-white' => 0]) ?>"
            ><?php echo e($menuOptionValue->value); ?></span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</div>
<?php /**PATH /Users/harrysingh/projects/mytasty/extensions/igniter/mealplan/views/mealplan/form/mealplan_addons.blade.php ENDPATH**/ ?>