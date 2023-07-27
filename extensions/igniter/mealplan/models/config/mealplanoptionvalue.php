<?php
$config['form']['fields'] = [

    'meal_plan_option_id' => [
        'type' => 'hidden',
    ],
    'value' => [
        'label' => 'Name e.g 8 oz',
        'type' => 'text',
    ],
    'price' => [
        'label' => 'price',
        'type' => 'currency',
    ],
    'priority' => [
        'label' => 'lang:admin::lang.menu_options.label_priority',
        'type' => 'hidden',
    ]
];

$config['form']['rules'] = [
    ['value', 'lang:admin::lang.menu_options.label_option_value', 'required|min:2|max:128'],
    ['price', 'lang:admin::lang.menu_options.label_option_price', 'required|numeric|min:0']
];

return $config;
