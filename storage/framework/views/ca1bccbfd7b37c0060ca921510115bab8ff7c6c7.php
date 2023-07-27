<!DOCTYPE html>
<html lang="<?php echo e(App::getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo app('translator')->get('system::lang.no_database.label'); ?></title>
    <link rel="shortcut icon" href="<?php echo e(asset('app/admin/assets/images/favicon.ico')); ?>" type="image/ico">
    <link href="<?php echo e(asset('app/admin/assets/css/static.css')); ?>" rel="stylesheet">
</head>
<body>
<article>
    <h2><?php echo app('translator')->get('system::lang.no_database.label'); ?></h2>
    <p class="lead"><?php echo app('translator')->get('system::lang.no_database.help'); ?></p>
</article>
</body>
</html>
<?php /**PATH /Users/harrysingh/projects/mytasty/app/system/views/no_database.blade.php ENDPATH**/ ?>