<div class="<?php echo e($pageIsCart ?: 'affix-cart d-none d-lg-block'); ?>">
    <div class="panel panel-cart">
        <div class="panel-body">
            <?php echo controller()->renderPartial($__SELF__.'::default'); ?>
        </div>
    </div>
</div>
<?php echo controller()->renderPartial($__SELF__.'::mobile'); ?>

