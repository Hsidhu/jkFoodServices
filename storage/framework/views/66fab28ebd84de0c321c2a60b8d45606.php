<?php if(!$hideSearch): ?>
    <div id="local-search-form">
        <?php echo controller()->renderPartial('@address_search_form'); ?>
    </div>

    <?php if($__SELF__->showDeliveryCoverageAlert()): ?>
        <p class="help-block text-center mt-1 mb-0"><?php echo app('translator')->get('igniter.local::default.text_delivery_coverage'); ?></p>
    <?php endif; ?>
<?php endif; ?>

